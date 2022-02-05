<?php
namespace App\Api;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use App\Entity\User;
use App\Api\Command\AuthLoginCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Api\SubscriberBroadcastCommand;
use App\Api\AuthenticatedUserClientCollection;
use App\Api\WorkerInterface;

 Class JwtAuthListener implements WorkerInterface {

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

    public function work($from,SubscriberBroadcastCommand $command,JsonCommandResponse $response)
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