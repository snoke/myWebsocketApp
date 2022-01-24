<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'AppController',
            'SERVER_URL' => $_ENV['SERVER_URL'],
            'WEBSOCKET_URL' => $_ENV['WEBSOCKET_URL'],
        ]);
    }
    /**
    * @IsGranted("ROLE_ADMIN")
    */
    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return new Response();
    }
}
