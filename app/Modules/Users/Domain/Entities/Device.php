<?php

namespace App\Modules\Users\Domain\Entities;

class Device {

    public $deviceToken;
    public $deviceType;
    public $fcmToken;

    public function __construct (string $deviceToken, string $deviceType, string $fcmToken) {
        $this->fcmToken = $fcmToken;
        $this->deviceToken = $deviceToken;
        $this->deviceType = $deviceType;
    }

    public function toArray () {
        return (array) $this;
    }
}