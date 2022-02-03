<?php
namespace App\Api;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use App\Entity\User;
use App\Api\Command\AuthLoginCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Api\UserBroadcastCommand;

 Class UserAuthListener {

     const LOGIN_COMMAND = AuthLoginCommand::class;

     private JWTEncoderInterface $encoder;
     private EntityManagerInterface $em;

     private function extractUser(string $token): User 
     {
        $payload = $this->encoder->decode($token);
        return $this->em->getRepository(User::class)->findOneBy(["id"=>$payload['id']]);
     }

    public function __construct(EntityManagerInterface $em,JWTEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
        $this->em=$em;
    }

    public function listen(UserBroadcastCommand $command,JsonCommandResponse $response)
    {
        if ($command::class==self::LOGIN_COMMAND) {
            if ($response->getStatusCode()==Command::SUCCESS) {
                return $this->extractUser($response->getData());
            }
       } 
    }

}