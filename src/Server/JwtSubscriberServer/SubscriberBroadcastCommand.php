<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer;

use App\Server\JsonWebsocketServer\CommandException;
use App\Server\JsonWebsocketServer\JsonCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface as User;

/**
 * SubscriberBroadcastCommand
 */
abstract Class SubscriberBroadcastCommand extends JsonCommand
{
    private Collection $subscribers;

    public function __construct()
    {
        parent::__construct();
        $this->subscribers = new ArrayCollection();
    }

    /**
     * @param Collection $users
     */
    public function setSubscribers(Collection $users): void
    {
        $this->subscribers = new ArrayCollection();
        foreach ($users as $user) {
            $this->addSubscriber($user);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    public function addSubscriber(User $user): void
    {
        $this->subscribers->add($user);
    }

    /**
     * @return Collection
     */
    public function getSubscribers(): Collection
    {
        return $this->subscribers;
    }
}