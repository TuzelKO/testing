<?php


namespace Engine\Libs;


class Factory {

    private $controller;
    private $class;
    private $path;

    /**
     * Factory constructor.
     */
    function __construct(){

        $url = strtok($_SERVER["REQUEST_URI"],'?');
        $url = trim($url, "/");

        $url = str_replace("\\", "/", $url);
        $this->path = explode("/", $url);
        $this->controller = end($this->path);


        $this->class =  "Engine\\Controllers\\" . $this->controller;

    }

    /**
     * @return bool
     */
    public function isExists(){

        if(class_exists($this->class, true)){
            return true;
        }else{
            return false;
        }

    }

    /**
     * @return bool
     */
    public function exec(){

        $class = new $this->class($this->path);

        if(!($class instanceof Controller)){
            return false;
        }

        if(!method_exists($this->class, "run")){
            return false;
        }

        $class->run();

        return true;

    }

}