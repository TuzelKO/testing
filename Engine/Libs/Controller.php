<?php


namespace Engine\Libs;

class Controller {

    const RESULT_SUCCESS = "ok";
    const RESULT_ERROR   = "error";

    protected $path;
    protected $request;

    /**
     * Controller constructor.
     * @param array $path
     */
    public function __construct(array $path) {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $this->path = $path;

        $this->request = self::ParseRequest();

    }

    public function run() {}

    /**
     * @return array
     */
    private static function ParseRequest() {

        if($tmp = json_decode(file_get_contents('php://input'), true)){
            return $tmp;
        }else{
            return [];
        }

    }

    /**
     * @param string $result
     * @param array $data
     */
    public static function sendResponse(string $result, array $data = []) {

        exit(json_encode(["result" => $result] + $data));

    }


}