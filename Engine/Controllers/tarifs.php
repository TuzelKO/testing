<?php


namespace Engine\Controllers;


use Engine\Libs\Controller;
use Engine\Objects\Service;
use Engine\Objects\User;
use Engine\Objects\Tarif;

class tarifs extends Controller {

    public function run(){

        if(strtoupper($_SERVER['REQUEST_METHOD']) !== "GET")
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

        if(!$tarif = Tarif::GetById($service->getTarifId()))
            self::sendResponse(self::RESULT_ERROR);

        if(!$tarifsInGroup = Tarif::GetByGroup($tarif->getGroupId()))
            self::sendResponse(self::RESULT_ERROR);

        foreach ($tarifsInGroup as $pointer => $object) {
            $tarifsInGroup[$pointer] = [
                'ID' => $object->getID(),
                'title' => $object->getTitle(),
                'price' => $object->getPrice(),
                'pay_period' => $object->getPayPeriod(),
                'new_payday' => $object->getNextPayday(),
                'speed' => $object->getSpeed()
            ];
        }

        $tarif = [
            'tarifs' => [
                'title' => $tarif->getTitle(),
                'link' => $tarif->getLink(),
                'speed' => $tarif->getSpeed(),
                'tarifs' => $tarifsInGroup
            ]
        ];

        self::sendResponse(self::RESULT_SUCCESS, $tarif);

    }

}