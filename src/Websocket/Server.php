<?php
namespace App\Websocket;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\BufferedOutput;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\User;

class Server implements MessageComponentInterface {
    protected $input;
    protected $output;
    protected $application;
    protected $chats;
    protected $messages;
    protected $encoder;
    protected $userClients;
    protected $clients;

    public function __construct($input,$output,$application,EntityManagerInterface $em,JWTEncoderInterface $encoder) {
        $this->input = $input;
        $this->output = $output;
        $this->application = $application;
        $this->em = $em;
        $this->encoder = $encoder;

        $this->userClients = [];
        $this->clients = new \SplObjectStorage;
        
        $this->io = new SymfonyStyle($this->input, $this->output);
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->consoleMessage("New connection from $conn->remoteAddress ($conn->resourceId)");
    }

    private function consoleMessage($data,$type='info') {
        if ($type=='info') {
            $this->io->info(json_encode($data));
        } elseif ($type='warning') {
            $this->io->warning(json_encode($data));
        } elseif ($type='error') {
            $this->io->error(json_encode($data));
        } elseif ($type='success') {
            $this->io->success(json_encode($data));
        } else {
            echo json_encode($data) . "\n";
        }
    }

    private function actionController($client,$action,$params) {
        $this->io->block(json_encode([$client->resourceId,$action,$params]), 'USER REQUEST', 'fg=blue', ' ', true);
        $arguments = new ArrayInput($params);
        $command = $this->application->find($action);
        $output = new BufferedOutput();
        $command->run($arguments, $output);
        $data = $output->fetch();
        $this->consoleMessage($data);
        return ["command"=>$action,"success"=>true,"data"=>$data];
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        
        $options = json_decode($msg, true); 
        
       //return success to sender
        $result = $this->actionController($from,$options['action'],$options['params']);
        $from->send(json_encode($result)); 

       //return participants
       if ($options['action']=='auth:login') {
            $payload = $this->encoder->decode($result['data']);
            $id = $payload['id'];
            $this->userClients[$from->resourceId] = [
                'client' =>$from,
                'userId' =>$id,
                'ip' =>$from->remoteAddress,
            ];
        }

        if ($options['action']=='chat:message:send') {
            $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=>$options['params']['chatId']]);
            $users = $chat->getUsers();
            foreach($this->userClients as $userClient) {
                foreach($users as $user) {
                    if ($user->getId()==$userClient['userId'] && $user->getId()!=$options['params']['senderId'] ) {
                        if ($userClient['client']) {
                            $userClient['client']->send(json_encode($result)); 
                        }
                    }
                }
            }
        }

        if ($options['action']=='chat:message:status') {
            $message = $this->em->getRepository(ChatMessage::class)->findOneBy(['id'=>$options['params']['messageId']]);
            $chat = $message->getChat();
            $users = $chat->getUsers();
            foreach($this->userClients as $userClient) {
                foreach($users as $user) {
                    if ($user->getId()==$message->getSender()->getId()) {
                        if ($userClient['client']) {
                            $userClient['client']->send(json_encode($result)); 
                        }
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->userClients[$conn->resourceId] = null;
        $this->userClients = array_filter($this->userClients);
        $this->consoleMessage("Connection dropped $conn->remoteAddress ($conn->resourceId)");
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}