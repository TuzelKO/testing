<?php


namespace Engine\Objects;

use Engine\Libs\DB;

class User {

    const STORAGE           = "users";

    const FIELD_ID          = "ID";
    const FIELD_LOGIN       = "login";
    const FIELD_NAME_LAST   = "name_last";
    const FIELD_NAME_FIRST  = "name_first";

    const FILTER_ID         = "/^[\d]+$/i";
    // More if required

    private $id           = 0;
    private $login        = "";
    private $nameLast     = "";
    private $nameFirst    = "";

    public function __construct(array $raw){

        if(isset($raw[self::FIELD_ID]))
            $this->id = (int) $raw[self::FIELD_ID];

        if(isset($raw[self::FIELD_LOGIN]))
            $this->login = (string) $raw[self::FIELD_LOGIN];

        if(isset($raw[self::FIELD_NAME_LAST]))
            $this->nameLast = (string) $raw[self::FIELD_NAME_LAST];

        if(isset($raw[self::FIELD_NAME_FIRST]))
            $this->nameFirst = (string) $raw[self::FIELD_NAME_FIRST];

    }

    public static function InputFilter($filter, &$value){

        if(empty($value)) return false;

        switch ($filter){
            case self::FILTER_ID:
                return (bool) preg_match($filter, $value);

            default:
                return false;
        }

    }

    /**
     * @param $query
     * @return User[]|bool
     */
    public static function GetObjects($query) {

        $db = DB::GetInstance();

        $result = $db->query($query);

        if($result === false) return false;

        $result = $result->fetch_all(MYSQLI_ASSOC);

        if(empty($result)) return false;

        if($result === false) return false;

        foreach ($result as $pointer => $raw) {

            $result[$pointer] = new self($raw);

        }

        return $result;

    }

    public static function IsExists($id) {

        $db = DB::GetInstance();

        $result = $db->query(
            "SELECT * FROM `".self::STORAGE."` WHERE  `".self::FIELD_ID."` = '".DB::InputEscape($id)."' LIMIT 1"
        );

        if($result === false) return false;
        if($result->num_rows != 1) return false;

        return true;

    }

}