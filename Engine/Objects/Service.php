<?php


namespace Engine\Objects;

use Engine\Libs\DB;

class Service {

    const STORAGE           = "services";

    const FIELD_ID          = "ID";
    const FIELD_USER_ID     = "user_id";
    const FIELD_TARIF_ID    = "tarif_id";
    const FIELD_PAYDAY      = "payday";

    const FILTER_ID         = "/^[\d]+$/i";
    // More if required


    private $id           = 0;
    private $userId       = 0;
    private $tarifId      = 0;
    private $payday       = "";

    /**
     * Service constructor.
     * @param array $raw
     */
    public function __construct(array $raw){

        if(isset($raw[self::FIELD_ID]))
            $this->id = (int) $raw[self::FIELD_ID];

        if(isset($raw[self::FIELD_USER_ID]))
            $this->userId = (int) $raw[self::FIELD_USER_ID];

        if(isset($raw[self::FIELD_TARIF_ID]))
            $this->tarifId = (int) $raw[self::FIELD_TARIF_ID];

        if(isset($raw[self::FIELD_PAYDAY]))
            $this->payday = (string) $raw[self::FIELD_PAYDAY];

    }

    /**
     * @return int
     */
    public function getTarifId() {
        return $this->tarifId;
    }

    public function setTarifId($value) {

        if(empty($this->id)) return false;

        $db = DB::GetInstance();
        if(!$db->query(
            "UPDATE `".self::STORAGE."` 
            SET `".self::FIELD_TARIF_ID."` = '".DB::InputEscape($value)."' 
            WHERE `".self::FIELD_ID."` = '".$this->id."'"
        )) return false;

        $this->tarifId = $value;

        return true;

    }

    /**
     * @param $filter
     * @param $value
     * @return bool
     */
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
     * @return Service[]|bool
     */
    public static function GetObjects($query)
    {

        $db = DB::GetInstance();

        $result = $db->query($query);

        if ($result === false) return false;

        $result = $result->fetch_all(MYSQLI_ASSOC);

        if (empty($result)) return false;

        if ($result === false) return false;

        foreach ($result as $pointer => $raw) {

            $result[$pointer] = new self($raw);

        }

        return $result;

    }

    /**
     * @param $objectId
     * @param $userId
     * @return bool|Service
     */
    public static function GetByIdAndUser($objectId, $userId) {

        $objects = Service::GetObjects(
            "SELECT * FROM `" . self::STORAGE . "` 
            WHERE `" . self::FIELD_ID . "` = " . DB::InputEscape($objectId) . " 
            AND `" . self::FIELD_USER_ID . "` = " . DB::InputEscape($userId)
        );

        if($objects === false) return false;
        if(count($objects) > 1) return false;

        return $objects[0];

    }

}