<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi;
use Symfony\Component\Security\Core\User\UserInterface as User;

use Symfony\Component\Console\Command\Command;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
abstract Class SubscriberBroadcastCommand extends Command {
    
    private Collection $subscribers;

    public function __construct()
    {
        parent::__construct();
        $this->subscribers = new ArrayCollection();
    }

    /** 
     * @param User[] $users
    */
    public function setSubscribers(Collection $users) {
        $this->subscribers = new ArrayCollection();
        foreach($users as $user) {
            $this->addSubscriber($user);
        }
    }
    
    public function addSubscriber(User $user) {
       $this->subscribers->add($user);
   }

    /** 
     * @return User[]
    */
    public function getSubscribers() : ArrayCollection {
        return $this->subscribers;
    }
}