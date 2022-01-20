<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface as Encoder;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\ChatRoom;

#[Route('/api')]
class ApiController extends AbstractController

{
    /**
     * @var Security
     */
    private $security;

    private $entityTypes = [
        'User' => User::Class,
        'ChatRoom' => ChatRoom::Class
    ];

    public function __construct(Security $security,Encoder $encoder,EntityManagerInterface $em)
    {
       $this->security = $security;
       $this->encoder = $encoder;
       $this->em = $em;
    }

    #[Route('/me', name: 'api_me')]
    public function me(Request $request): Response
    {     
        $data = $this->encoder->decode(substr($request->headers->get('Authorization'),7));
        $user = $this->em->getRepository(User::Class)->findOneBy(['username' => $data["username"]]);
        $data['id'] = $user->getId();
        $data['chat_rooms'] =  array_filter($this->em->getRepository(ChatRoom::Class)->findAll(), function($obj) use ($user){
            foreach ($obj->participants as $participant) {
                if ($participant->getId() == $user->getId()) {
                    return true;
                }
            }
        });
        return $this->json($data);
    }

    #[Route('/{entity}.json', name: 'api_get', methods: 'GET')]
    public function apiGet(Request $request,$entity): Response
    {  
        $repository = $this->em->getRepository($this->entityTypes[$entity]);
        return $this->json($repository->findByLike($_GET));
    }

    #[Route('/register', name: 'api_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {     
        $post_data = json_decode($request->getContent(), true);
        $username = $post_data['username'];
        $password = $post_data['password'];

        // encode the plain password
        $user = new User();
        $user->setUsername($username);
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword(
        $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(true);
    }
}
