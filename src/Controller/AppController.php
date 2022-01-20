<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

    /**
    * @IsGranted("ROLE_ADMIN")
    */
#[Route('/app')]
class AppController extends AbstractController
{
    #[Route('/', name: 'app')]
    #[Route('/{route}', name: 'vue_pages')]
    public function index(): Response
    {   
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
