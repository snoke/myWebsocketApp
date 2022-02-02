<?php
namespace App\Websocket;

use Symfony\Component\Console\Command\Command as Base;

abstract Class WebsocketCommand extends Base {

    private $subscribers;

    public function __construct()
    {
        parent::__construct();
        $this->subscribers = [];
    }

    public function addSubscriber( $user) {
       $this->subscribers[] = $user;
   }

    public function getSubscribers() {
        return $this->subscribers;
    }
}