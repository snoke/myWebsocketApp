<?php
namespace App\Websocket;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\BufferedOutput;
use App\Repository\ChatRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class Server implements MessageComponentInterface {
    protected $clients;
    protected $application;
    protected $input;
    protected $output;
    protected $chats;
    protected $encoder;
    protected $userClients;

    public function __construct($input,$output,$application,ChatRepository $chats,JWTEncoderInterface $encoder) {
        $this->clients = new \SplObjectStorage;
        $this->application = $application;
        $this->input = $input;
        $this->output = $output;
        $this->chats = $chats;
        $this->encoder = $encoder;
        $this->userClients = [];
        
        $this->io = new SymfonyStyle($this->input, $this->output);
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connectioarran to send messages to later
        $this->clients->attach($conn);
        $this->consoleMessage("New connection from " . $conn->resourceId);
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

       //emit to participants
       if ($options['action']=='auth:login') {
            $payload = $this->encoder->decode($result['data']);
            $id = $payload['id'];
           // $this->userClients[$id] =  $from;
            $this->userClients[$from->resourceId] = [
                'client' =>$from,
                'userId' =>$id
            ];
        }

       //emit to participants
        if ($options['action']=='app:chat:send') {
            $chat = $this->chats->findOneBy(['id'=>$options['params']['chatId']]);
            $users = $chat->getUsers();
            

            foreach($this->userClients as $userClient) {
                foreach($users as $user) {
                    if ($user->getId()==$userClient['userId'] && $user->getId()!=$options['params']['senderId'] ) {
                        $userClient['client']->send(json_encode($result)); 
                    }
                }


            }
            //TODO: MAP USERS TO CLIENTS
        }

        /*
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
        */
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}