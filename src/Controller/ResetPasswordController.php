<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        $notification = null;

        if ($this->getUser()) {
            return $this->redirectToRoute('user');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new DateTime());

                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
                $scheme = $request->getSchemeAndHttpHost();
                $url = $this->generateUrl('update_password', ['token' => $reset_password->getToken()]);

                $content = "Bonjour " . $user->getFirstname() . "<br>
                            Vous avez demandé à réinitialiser votre mot de passe sur le site Generation Boomerang <br><br>
                            Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$scheme . $url . "'>mettre à jour votre mot de passe</a>.";
                $mail = new Mail();
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstname() . ' ' . $user->getLastname(),
                    'Réinitialiser votre mot de passe',
                    $content
                );

                $notification = "Un email pour la réinitialisation de votre mot de passe vous a été envoyé à l'adresse " . $user->getEmail() . ".";
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
                $notification = "Cette email nous est inconnue. Nous vous invitons à vous inscrire afin de profiter à notre platfrome.";
            }
        }
        return $this->render('reset_password/index.html.twig', [
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function update(Request $request, $token, UserPasswordHasherInterface $hasher): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new DateTime();

        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();
            $password = $hasher->hashPassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}