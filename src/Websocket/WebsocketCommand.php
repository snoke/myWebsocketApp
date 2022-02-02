<?php
namespace App\Websocket;

use App\Entity\User;
use Symfony\Component\Console\Command\Command as Base;

abstract Class WebsocketCommand extends Base {

    private $subscribers;

    public function __construct()
    {
        parent::__construct();
        $this->subscribers = [];
    }

    public function setSubscribers( $users) {
        $this->subscribers = $users;
    }
    public function addSubscriber(User $user) {
       $this->subscribers[] = $user;
   }

    public function getSubscribers() {
        return $this->subscribers;
    }
}