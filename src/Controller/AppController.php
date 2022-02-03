<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Api\ChatApi;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Route("/{route}", name="index_route", requirements={"route"="^.+"})
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
            'config' => [ 
                'websocket_url' => $_ENV['WEBSOCKET_URL'],
            ]
        ]);
    }
}
