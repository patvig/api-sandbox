<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

use App\Service\MailService;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\Persistence\ManagerRegistry;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['email'], $data['password'])) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si l'utilisateur existe déjà
        if ($entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']])) {
            return new JsonResponse(['error' => 'User already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setUsername($data['nom']);
        $user->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        // Validation
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        // Sauvegarde en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }

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

    #[Route('/api/requestResetPassword', name: 'requestResetPassword', methods: 'post')]
    public function requestResetPassword(
        ManagerRegistry $doctrine,
        Request $request,
        TokenGeneratorInterface $tokenGenerator,
        MailService $mailService,
    ): JsonResponse {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;

        try {
            $user = $this->userRepository->findByEmail($email);
            if (empty($user)) {
                return $this->json(
                    ['message' => 'Email inconnu'],
                    Response::HTTP_NOT_FOUND
                );
            }

            // Générer un token sécurisé
            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);
            $em->flush();

            // Envoyer un email avec le lien de réinitialisation
            $email = (new TemplatedEmail())
                ->from(new Address('patrice.vigouroux@abpost.fr'))
                ->subject("Demande de réinitialisation de mot de passe")
                ->to($user->getEmail())
                ->htmlTemplate('emails/reset_password.html.twig')
                ->context([
                    'resetToken' => $token,
                    'resetUrl' => 'http://localhost:4200/resetpassword/' . $token . '/' . $email
                ]);

            $mailService->sendEmail($email);

            return $this->json(['message' => 'Lien de réinitialisation envoyé']);
        } catch (Exception $e) {
            return $this->json(['message' => "Erreur lors de la demande : " . $e->getMessage()], 500);
        }
    }

    #[Route('/api/resetpassword', name: 'resetPassword', methods: 'post')]
    public function resetPassword(
        ManagerRegistry $doctrine,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
    ): JsonResponse {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $password = $decoded->password;
        $token = $decoded->token;
        $email = $decoded->email;

        try {
            $user = $this->userRepository->findByEmail($email);
            if (empty($user)) {
                return $this->json(
                    ['message' => 'Uitilisateur inconnu'],
                    Response::HTTP_NOT_FOUND
                );
            }

            if ($user->getResetToken() != $token) {
                return $this->json(
                    ['message' => 'token invalide'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $user->setResetToken(null);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $em->flush();

            return $this->json(['message' => 'Mot de passe mis à jour']);
        } catch (Exception $e) {
            return $this->json(['message' => "Erreur lors du changement de mot de passe : " . $e->getMessage()], 500);
        }
    }
}
