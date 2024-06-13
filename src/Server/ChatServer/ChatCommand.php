<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer;

use App\Entity\User;
use App\Server\JsonWebsocketServer\CommandException;
use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand as AbstractCommand;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * ChatCommand
 */
abstract class ChatCommand extends AbstractCommand
{
    protected JWTEncoderInterface $encoder;
    protected EntityManagerInterface $em;
    protected SerializerInterface $serializer;

    /**
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param JWTEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, JWTEncoderInterface $encoder)
    {

        parent::__construct();

        $this->encoder = $encoder;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('token', InputArgument::REQUIRED, 'user token');
    }


    /**
     * @param $token
     * @return User|null
     * @throws CommandException
     */
    protected function getUserByToken($token): ?\Symfony\Component\Security\Core\User\UserInterface
    {
        try {
            $payload = $this->encoder->decode($token);
        } catch(JWTDecodeFailureException $e) {
            throw new CommandException('invalid token', 401);
        }
        return $this->em->getRepository(User::class)->findOneBy(['id' => $payload['id']]);
    }

    /**
     * @param string $token
     * @return User
     * @throws CommandException
     */
    protected function authorize(string $token): User
    {
        $user = $this->getUserByToken($token);
        if (!$user) {
            throw new CommandException('Unauthorized', 401);
        }
        return $user;
    }
}