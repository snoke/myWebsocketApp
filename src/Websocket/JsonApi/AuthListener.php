<?php
namespace App\Websocket\JsonApi;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use App\Entity\User;
use App\Websocket\JsonApi\Command\AuthLoginCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;


 Class AuthListener {
     private $encoder;
     private $em;

     private function extractUser($data): User 
     {
        $payload = $this->encoder->decode($data);
        $id = $payload['id'];
        return $this->em->getRepository(User::class)->findOneBy(["id"=>$id]);
     }
    public function __construct(EntityManagerInterface $em,JWTEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
        $this->em=$em;
    }
    public function listen($command,$statusCode,$data)
    {
        if ($command::class==AuthLoginCommand::class) {
            if ($statusCode==Command::SUCCESS) {
                return $this->extractUser($data);
            }
       } 
    }

}