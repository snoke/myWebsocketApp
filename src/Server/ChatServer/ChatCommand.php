<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer;

use App\Entity\User;
use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand as AbstractCommand;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
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
     * @return ?User
     * @throws JWTDecodeFailureException
     */
    protected function getUserByToken($token): ?User
    {

        $payload = $this->encoder->decode($token);
        return $this->em->getRepository(User::class)->findOneBy(['id' => $payload['id']]);
    }
}