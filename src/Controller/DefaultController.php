<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Console\Input\ArrayInput;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Websocket\Command\AuthLoginCommand;
class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'AppController',
            'config' => [ 
                // DO NOT PUT SENSITIVE DATA HERE, THIS CAN BE MODIFIED CLIENT SIDE
                'websocket_url' => $_ENV['WEBSOCKET_URL'],
            ]
        ]);
    }
}
