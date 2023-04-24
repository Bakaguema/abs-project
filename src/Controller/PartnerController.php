<?php

namespace App\Controller;

use App\Entity\User;
use App\Classes\Mail;
use App\Form\RegisterType;
use App\Entity\UserPartner;
use App\Form\SearchUserType;
use App\Form\RegisterPartnerType;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\UserRepository;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PartnerController extends AbstractController
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
     * @Route("/partenaires", name="partner")
     */
    public function index(PartnerRepository $partnerRepository,Request $request): Response
    {
        $user = $this ->getUser();
        
        $partners = $partnerRepository->findall(['name' => 'desc']);

        $searchForm = $this->createForm(SearchUserType::class);
        $searchForm->handleRequest($request);
        $searchCriteria = $searchForm->getData();

        $partners = $partnerRepository->search($searchCriteria);
        if($searchForm->isSubmitted() && $searchForm->isValid()){
            return $this->render('partner/index.html.twig', [
                'partners' => $partners,
                'searchForm' => $searchForm->createView()
            ]);
        }
                return $this->render('partner/index.html.twig', [
                'partners'=>$partners,
                'searchForm' => $searchForm->createView()
            ]); 
        }

    /**
     * @Route("partner/inscription", name="partner_register")
     * @throws Exception
     */
    public function register(Request $request, UserPasswordHasherInterface $hasher): Response
    {       

        $user = new User();
        $form = $this->createForm(RegisterPartnerType::class, $user);

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
            $user->setRoles(["ROLE_SUB"]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $url = $this->generateUrl('confirm_email', [ 'token' => $user->getToken() ]);
            $mail = new Mail();
            $mail->send(
                    $user->getEmail(),
                    $user->getFirstname(),
                    'Merci de confirmer votre email',
                    'Bonjour '.$user->getFirstname().'<br>
                    Afin de confirmer votre inscription merci de <a href="http://generation-boomerang.com'.$url.'">cliquer ici</a>.'
                )
            ;

            return $this->redirectToRoute('success_register');
        }

        return $this->render('userpartner/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

        /**
     * @return string
     * @throws Exception
     */
    private function generateToken(): string
    {
        

        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=',
            
        
    
    );
    }


        /**
     * @Route("/inscription/attente-de-validation", name="success_register")
     */
    public function success(): Response
    {
        $user = $this ->getUser();
        
        
        $user->$roles[] = 'ROLE_PARTNER';

        if ($this->getUser()) {
            return $this->redirectToRoute('user');
        }



        return $this->render('register/success.html.twig');
    }


    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout( ): void
    {
        

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
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


}

