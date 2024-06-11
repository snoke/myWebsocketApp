<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\ChatApi;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

use Symfony\Component\Console\Input\InputArgument;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use App\Api\JwtSubscriberApi\SubscriberBroadcastCommand as AbstractCommand;

/**
 *
 */
abstract class ChatCommand extends AbstractCommand
{
    protected JWTEncoderInterface $encoder;
    protected EntityManagerInterface $em;
    protected SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, JWTEncoderInterface $encoder)
    {

        parent::__construct();

        $this->encoder = $encoder;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('token', InputArgument::REQUIRED, 'user token');
    }

    /**
     * @param $token
     * @return User|object|null
     * @throws JWTDecodeFailureException
     */
    protected function getUserByToken($token)
    {

        $payload = $this->encoder->decode($token);
        return $this->em->getRepository(User::class)->findOneBy(['id' => $payload['id']]);
    }
}