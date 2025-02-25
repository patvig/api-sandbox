<?php

namespace App\Controller;

use App\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Repository\EvenementRepository;

class UserController extends AbstractController
{

    #[Route('/user/getInfo', name: 'app_user getInfo')]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }      
        
        return $this->json([
             'id'  => $user->getId(),
             'email'  => $user->getUserIdentifier(),
             'username' => $user->getUsername()
        ]);
    }

    #[Route('/user/getUserEvenements', name: 'app_user getUserEvenements')]
    public function getUserEvenements(SerializerInterface $serializer, #[CurrentUser] ?User $user, EvenementRepository $evenementRepository): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }      
        //dd($evenementRepository->findAll());
        $Evenements = $evenementRepository->findBy(['userId'=>$user->getId()]);
        
        $response = $serializer->serialize($Evenements, 'json');
        return new Response($response, 200, ['Content-Type' => 'application/json']);
    }
}
