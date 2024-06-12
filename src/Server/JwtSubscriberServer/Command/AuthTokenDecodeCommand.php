<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer\Command;

use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand as AbstractCommand;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 *
 */
#[AsCommand(
    name: 'auth:token:decode',
    description: 'Extracts Claim from provided Token',
)]
class AuthTokenDecodeCommand extends AbstractCommand
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $em;
    private JWTEncoderInterface $encoder;

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
            ->addArgument('token', InputArgument::REQUIRED, 'the token to extract the claim');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws JWTDecodeFailureException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $payload = $this->encoder->decode($token);

        $output->write(json_encode($payload));
        return Command::SUCCESS;
    }
}
