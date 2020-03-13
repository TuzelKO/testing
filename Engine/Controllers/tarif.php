<?php


namespace Engine\Controllers;


use Engine\Libs\Controller;
use Engine\Libs\DB;
use Engine\Objects\Service;
use Engine\Objects\User;
use Engine\Objects\Tarif as TarifObject;

class tarif extends Controller {

    public function run() {

        if(strtoupper($_SERVER['REQUEST_METHOD']) !== "PUT")
            self::sendResponse(self::RESULT_ERROR);

        if(!isset($this->path[0]) || $this->path[0] !== "users")
            self::sendResponse(self::RESULT_ERROR);

        if(!isset($this->path[1]) || !User::InputFilter(User::FILTER_ID, $this->path[1]))
            self::sendResponse(self::RESULT_ERROR);

        if(!User::IsExists($this->path[1]))
            self::sendResponse(self::RESULT_ERROR);

        if(!isset($this->path[2]) || $this->path[2] !== "services")
            self::sendResponse(self::RESULT_ERROR);

        if(!isset($this->path[3]) || !Service::InputFilter(Service::FILTER_ID, $this->path[1]))
            self::sendResponse(self::RESULT_ERROR);

        if(!$service = Service::GetByIdAndUser($this->path[3], $this->path[1]))
            self::sendResponse(self::RESULT_ERROR);

        if(!TarifObject::InputFilter(TarifObject::FILTER_ID, $this->request['tarif_id']))
            self::sendResponse(self::RESULT_ERROR);

        if(!self::CanUpdateServiceOnThisTarif($service->getTarifId(), $this->request['tarif_id']))
            self::sendResponse(self::RESULT_ERROR);

        if(!$service->setTarifId($this->request['tarif_id']))
            self::sendResponse(self::RESULT_ERROR);

        self::sendResponse(self::RESULT_SUCCESS);


    }

    private function CanUpdateServiceOnThisTarif(int $old, int $new) {

        $objects = TarifObject::GetObjects(
            "SELECT `" . TarifObject::FIELD_GROUP_ID . "` 
            FROM `" . TarifObject::STORAGE . "` 
            WHERE `" . TarifObject::FIELD_ID . "` = '" . DB::InputEscape($old) ."'"
        );

        if($objects === false) return false;
        if(count($objects) != 1) return false;

        if(empty($groupId = $objects[0]->getGroupId())) return false;

        $objects = TarifObject::GetObjects(
            "SELECT `" . TarifObject::FIELD_ID . "` 
            FROM `" . TarifObject::STORAGE . "` 
            WHERE `" . TarifObject::FIELD_ID . "` = '" . DB::InputEscape($new) . "' 
            AND `" . TarifObject::FIELD_GROUP_ID . "` = '" . DB::InputEscape($groupId) ."'"
        );

        if($objects === false) return false;
        if(count($objects) != 1) return false;

        return true;

    }


}