<?php
namespace App\Api;

use Symfony\Component\Security\Core\User\UserInterface as User;

use Symfony\Component\Console\Command\Command as Base;

abstract Class UserBroadcastCommand extends Base {
    
    private array $subscribers;

    public function __construct()
    {
        parent::__construct();
        $this->subscribers = [];
    }

    /** 
     * @param User[] $users
    */
    public function setSubscribers(array $users) {
        $this->subscribers = $users;
    }
    public function addSubscriber(User $user) {
       $this->subscribers[] = $user;
   }

    /** 
     * @return User[]
    */
    public function getSubscribers() : array {
        return $this->subscribers;
    }
}