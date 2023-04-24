<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Classes\Mail;
use App\Form\RegisterType;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/inscription", name="register")
     * @throws Exception
     */
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $user->setToken($this->generateToken());

            $uploadFile = $form['picture']->getData();

            if ($uploadFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/profiles';

                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName).'-'.uniqid().'.'.$uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );

                $user->setPicture($newFilename);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $url = $this->generateUrl('confirm_email', [ 'token' => $user->getToken() ]);
            $mail = new Mail();
            $mail->send(
                    $user->getEmail(),
                    $user->getFirstname(),
                    'Merci de confirmer votre email',
                    'Bonjour '.$user->getFirstname().'<br>
                    Afin de confirmer votre inscription merci de <a href="http://beta2.generation-boomerang.com'.$url.'">cliquer ici</a>.'
                )
            ;
                       
            return $this->redirectToRoute('success_register');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/inscription/attente-de-validation", name="success_register")
     */
    public function success(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user');
        }

        return $this->render('register/success.html.twig');
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_email")
     * @param string $token
     * @return RedirectResponse
     */
    public function confirmEmail(string $token): RedirectResponse
    {
        $user = $this->userRepository->findOneBy(["token" => $token]);

        if ($user) {
            $user->setToken(null);
            $user->setActive(1);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        } else {
            return $this->redirectToRoute('register');
        }
    }

        /**
     * @Route("/inscrivez-vous", name="registration")
     */

     public function registration(): Response
     {
         if ($this->getUser()) {
             return $this->redirectToRoute('user');
         }
 
         return $this->render('register/redirect.html.twig');
     }


    /**
     * @return string
     * @throws Exception
     */
    private function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

}
