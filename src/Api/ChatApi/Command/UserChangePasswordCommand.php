<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\ChatApi\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\User;

use App\Api\ChatApi\ChatCommand as AbstractCommand;
#[AsCommand(
    name: 'user:change:password',
    description: 'change password',
)]
class UserChangePasswordCommand extends AbstractCommand
{    
    private $passwordHasher;
    protected $em;

    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer,JWTEncoderInterface $encoder,UserPasswordHasherInterface $userPasswordHasher,HttpClientInterface $client)
    {
        
        parent::__construct($em, $serializer,$encoder);
        $this->passwordHasher = $userPasswordHasher;
        $this->client = $client;
    }

    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('oldpassword', InputArgument::REQUIRED, 'old password')
            ->addArgument('password', InputArgument::REQUIRED, 'new password')
            ->addArgument('password2', InputArgument::REQUIRED, 'new password repeat')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) { return 401; }
        $oldpassword = $input->getArgument('oldpassword');
        $password = $input->getArgument('password');
        $password2 = $input->getArgument('password2');

        $response = $this->client->request(
            'POST',
            $_ENV['SERVER_URL'] . '/api/login_check',
            [    'headers' => [
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
        
        if ($statusCode!=200) {
            $output->write("wrong password");
            return Command::FAILURE;
        }



        if ($password!==$password2) {
            $output->write("passwords are not identical");
            return Command::FAILURE;
        }
        if (strlen($password)<4) {
            $output->write("password is too short");
            return Command::FAILURE;
        }

        $payload = $this->encoder->decode($token);
        $user = $this->em->getRepository(User::class)->findOneBy(['id'=>$payload["id"]]);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $password
            )
        );
        $this->em->persist($user);
        $this->em->flush();
        $io = new SymfonyStyle($input, $output);
        $output->write("password changed");
        return Command::SUCCESS;
    }
}
