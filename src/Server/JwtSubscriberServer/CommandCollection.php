<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer;

use App\Server\JsonWebsocketServer\CommandCollection as Base;
use App\Server\JwtSubscriberServer\Command\AuthLoginCommand;
use App\Server\JwtSubscriberServer\Command\AuthRegisterCommand;
use App\Server\JwtSubscriberServer\Command\AuthTokenDecodeCommand;

/**
 * CommandCollection
 */
class CommandCollection extends Base
{

    /**
     * @param AuthLoginCommand $authLogin
     * @param AuthRegisterCommand $authRegister
     * @param AuthTokenDecodeCommand $authTokenDecode
     */
    public function __construct(
        AuthLoginCommand       $authLogin,
        AuthRegisterCommand    $authRegister,
        AuthTokenDecodeCommand $authTokenDecode,
    )
    {
        parent::__construct();
        $this->addCommand($authLogin);
        $this->addCommand($authRegister);
        $this->addCommand($authTokenDecode);
    }
}