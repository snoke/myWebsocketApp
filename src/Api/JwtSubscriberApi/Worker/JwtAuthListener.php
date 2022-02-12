<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi\Worker;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use \Ratchet\WebSocket\WsConnection;
use App\Entity\User;
//use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface as User;
use App\Api\Command\AuthLoginCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Api\JwtSubscriberApi\SubscriberBroadcastCommand;
use App\Api\JwtSubscriberApi\AuthenticatedUserClientCollection;
use App\Api\JsonApi\AbstractWorker as Worker;

use App\Api\JwtSubscriberApi\Command\AuthLoginCommand as LoginCommand;

use App\Api\JsonApi\JsonCommandResponse;
Class JwtAuthListener extends Worker {

     private Command $loginCommand;

     private JWTEncoderInterface $encoder;
     private EntityManagerInterface $em;
     private AuthenticatedUserClientCollection $userClients;

     private function extractUser(string $token): User 
     {
        $payload = $this->encoder->decode($token);
        return $this->em->getRepository(User::class)->findOneBy(["id"=>$payload['id']]);
     }
    public function __construct(EntityManagerInterface $em,JWTEncoderInterface $encoder,AuthenticatedUserClientCollection $userClients,LoginCommand $loginCommand)
    {
        $this->encoder=$encoder;
        $this->em=$em;
        $this->userClients=$userClients;

        $this->loginCommand = $loginCommand;
    }

    public function onClose(WsConnection $from)
    {
        $this->userClients->removeClient($from);
    }

    public function onMessage(WsConnection $from,Command $command,JsonCommandResponse $response)
    {
        if ($command::class==$this->loginCommand::class) {
            if ($response->getStatusCode()==Command::SUCCESS) {
                $user = $this->extractUser($response->getData());
                if ($user) {
                    $this->userClients->addClient($from,$user);
                } 
            }
       }   
    }

}