<?php
namespace App\Api\Worker;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use App\Entity\User;
use App\Api\Command\AuthLoginCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Api\SubscriberBroadcastCommand;
use App\Api\AuthenticatedUserClientCollection;
use App\Api\AbstractWorker as Worker;

use \Ratchet\WebSocket\WsConnection;
use App\Api\JsonCommandResponse;
 Class JwtAuthListener extends Worker {

     const LOGIN_COMMAND = AuthLoginCommand::class;

     private JWTEncoderInterface $encoder;
     private EntityManagerInterface $em;
     private AuthenticatedUserClientCollection $userClients;

     private function extractUser(string $token): User 
     {
        $payload = $this->encoder->decode($token);
        return $this->em->getRepository(User::class)->findOneBy(["id"=>$payload['id']]);
     }

    public function __construct(EntityManagerInterface $em,JWTEncoderInterface $encoder,AuthenticatedUserClientCollection $userClients)
    {
        $this->encoder=$encoder;
        $this->em=$em;
        $this->userClients=$userClients;
    }

    public function onClose(WsConnection $from)
    {
        $this->userClients->removeClient($from);
    }

    public function onMessage(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $response)
    {
        if ($command::class==self::LOGIN_COMMAND) {
            if ($response->getStatusCode()==Command::SUCCESS) {
                $user = $this->extractUser($response->getData());
                if ($user) {
                    $this->userClients->addClient($from,$user);
                } 
            }
       }   
    }

}