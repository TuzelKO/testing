<?php


namespace Engine\Libs;


class DB extends \mysqli {

    protected static $_instance;

    /**
     * @return DB
     */
    public static function GetInstance() {

        if(empty(self::$_instance)){
            self::$_instance = new self(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        }

        return self::$_instance;

    }

    /**
     * @param $value
     * @return string
     */
    public static function InputEscape($value){

        return self::GetInstance()->escape_string($value);

    }

}