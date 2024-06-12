<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer\Worker;

use App\Entity\User;
use App\Server\JsonWebsocketServer\AbstractWorker as Worker;
use App\Server\JsonWebsocketServer\JsonCommandResponse;
use App\Server\JwtSubscriberServer\AuthenticatedUserClientCollection;
use App\Server\JwtSubscriberServer\Command\AuthLoginCommand as LoginCommand;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Console\Command\Command;

//use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface as User;

/**
 *
 */
Class JwtAuthListener extends Worker
{

    private Command $loginCommand;

    private JWTEncoderInterface $encoder;
    private EntityManagerInterface $em;
    private AuthenticatedUserClientCollection $userClients;

    /**
     * @param string $token
     * @return User
     * @throws JWTDecodeFailureException
     */
    private function extractUser(string $token): User
    {
        $payload = $this->encoder->decode($token);
        return $this->em->getRepository(User::class)->findOneBy(['id' => $payload['id']]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param JWTEncoderInterface $encoder
     * @param AuthenticatedUserClientCollection $userClients
     * @param LoginCommand $loginCommand
     */
    public function __construct(EntityManagerInterface $em, JWTEncoderInterface $encoder, AuthenticatedUserClientCollection $userClients, LoginCommand $loginCommand)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->userClients = $userClients;

        $this->loginCommand = $loginCommand;
    }

    /**
     * @param WsConnection $from
     * @return void
     */
    public function onClose(WsConnection $from): void
    {
        $this->userClients->removeClient($from);
    }

    /**
     * @param WsConnection $from
     * @param Command $command
     * @param JsonCommandResponse $response
     * @return void
     */
    public function onMessage(WsConnection $from, Command $command, JsonCommandResponse $response): void
    {
        if ($command::class == $this->loginCommand::class) {
            if ($response->getStatusCode() == Command::SUCCESS) {
                $user = $this->extractUser($response->getData());
                if ($user) {
                    $this->userClients->addClient($from, $user);
                }
            }
        }
    }

}