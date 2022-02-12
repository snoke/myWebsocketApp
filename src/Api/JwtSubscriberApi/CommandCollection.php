<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi;

use App\Api\JsonApi\CommandCollection as Base;
use App\Api\JwtSubscriberApi\Command\AuthLoginCommand;
use App\Api\JwtSubscriberApi\Command\AuthRegisterCommand;
use App\Api\JwtSubscriberApi\Command\AuthTokenDecodeCommand;

class CommandCollection extends Base {
    
    public function __construct(
        AuthLoginCommand $authLogin,
        AuthRegisterCommand $authRegister,
        AuthTokenDecodeCommand $authTokenDecode,
        ) {
            parent::__construct();
            $this->addCommand($authLogin);
            $this->addCommand($authRegister);
            $this->addCommand($authTokenDecode);
    }
}