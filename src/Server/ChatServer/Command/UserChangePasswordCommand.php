<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\User;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
#[AsCommand(
    name: 'user:change:password',
    description: 'change password',
)]
class UserChangePasswordCommand extends AbstractCommand
{
    private UserPasswordHasherInterface $passwordHasher;
    protected EntityManagerInterface $em;
    private HttpClientInterface $client;

    /**
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param JWTEncoderInterface $encoder
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param HttpClientInterface $client
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, JWTEncoderInterface $encoder, UserPasswordHasherInterface $userPasswordHasher, HttpClientInterface $client)
    {

        parent::__construct($em, $serializer, $encoder);
        $this->passwordHasher = $userPasswordHasher;
        $this->client = $client;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('oldpassword', InputArgument::REQUIRED, 'old password')
            ->addArgument('password', InputArgument::REQUIRED, 'new password')
            ->addArgument('password2', InputArgument::REQUIRED, 'new password repeat');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws JWTDecodeFailureException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) {
            return 401;
        }
        $oldpassword = $input->getArgument('oldpassword');
        $password = $input->getArgument('password');
        $password2 = $input->getArgument('password2');

        $response = $this->client->request(
            'POST',
            $_ENV['SERVER_URL'] . '/api/login_check',
            ['headers' => [
                'Accept' => 'application/json',
            ],
                'json' => [
                    'username' => $user->getUsername(),
                    'password' => $oldpassword,
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();

        if ($statusCode != 200) {
            $output->write('wrong password');
            return Command::FAILURE;
        }


        if ($password !== $password2) {
            $output->write('passwords are not identical');
            return Command::FAILURE;
        }
        if (strlen($password) < 4) {
            $output->write('password is too short');
            return Command::FAILURE;
        }

        $payload = $this->encoder->decode($token);
        $user = $this->em->getRepository(User::class)->findOneBy(['id' => $payload['id']]);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $password
            )
        );
        $this->em->persist($user);
        $this->em->flush();
        $io = new SymfonyStyle($input, $output);
        $output->write('password changed');
        return Command::SUCCESS;
    }
}
