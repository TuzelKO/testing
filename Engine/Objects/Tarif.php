<?php


namespace Engine\Objects;

use Engine\Libs\DB;

class Tarif {

    const STORAGE           = "tarifs";

    const FIELD_ID          = "ID";
    const FIELD_GROUP_ID    = "pay_period";
    const FIELD_TITLE       = "title";
    const FIELD_PRICE       = "price";
    const FIELD_LINK        = "link";
    const FIELD_SPEED       = "speed";
    const FIELD_PAY_PERIOD  = "pay_period";

    const FILTER_ID         = "/^[\d]+$/i";
    // More if required

    private $id           = 0;
    private $groupId      = 0;
    private $title        = "";
    private $price        = 0;
    private $link         = "";
    private $speed        = 0;
    private $payPeriod    = 0.0000;

    /**
     * Tarif constructor.
     * @param array $raw
     */
    public function __construct(array $raw){

        if(isset($raw[self::FIELD_ID]))
            $this->id = (int) $raw[self::FIELD_ID];

        if(isset($raw[self::FIELD_GROUP_ID]))
            $this->groupId = (int) $raw[self::FIELD_GROUP_ID];

        if(isset($raw[self::FIELD_TITLE]))
            $this->title = (string) $raw[self::FIELD_TITLE];

        if(isset($raw[self::FIELD_PRICE]))
            $this->price = (float) $raw[self::FIELD_PRICE];

        if(isset($raw[self::FIELD_LINK]))
            $this->link = (string) $raw[self::FIELD_LINK];

        if(isset($raw[self::FIELD_SPEED]))
            $this->speed = (int) $raw[self::FIELD_SPEED];

        if(isset($raw[self::FIELD_PAY_PERIOD]))
            $this->payPeriod = (int) $raw[self::FIELD_PAY_PERIOD];

    }

    public function getID() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getSpeed() {
        return $this->speed;
    }

    /**
     * @return int
     */
    public function getPayPeriod() {
        return $this->payPeriod;
    }

    public function getNextPayday() {
        return date('UO', strtotime(date("Y-m-d").' + ' . $this->payPeriod . ' month'));
    }

    /**
     * @param $filter
     * @param $value
     * @return bool
     */
    public static function InputFilter($filter, &$value) {

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
     * @return Tarif[]|bool
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

    /**
     * @param $id
     * @return bool|Tarif
     */
    public static function GetById($id) {

        $objects = self::GetObjects(
            "SELECT * FROM `".self::STORAGE."` WHERE `".self::FIELD_ID."` = '".DB::InputEscape($id)."'"
        );

        if(!$objects) return false;

        return $objects[0];

    }

    /**
     * @param $groupId
     * @return bool|Tarif[]
     */
    public static function GetByGroup($groupId) {

        return self::GetObjects(
            "SELECT * FROM `".self::STORAGE."` WHERE `".self::FIELD_GROUP_ID."` = '".DB::InputEscape($groupId)."'"
        );

    }

}