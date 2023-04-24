<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Experience;
use App\Entity\Illustration;
use App\Form\ExperienceType;
use App\Repository\FriendRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ExperienceController extends AbstractController
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
     * @Route("/admin/retour-experience", name="admin_experience")
     */
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('admin/experience/index.html.twig', [
            'experiences' => $experienceRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/retour-experience/activer/{id}", name="admin_active_experience")
     */
    public function active(Experience $experience): Response
    {
        $experience->setActive(!$experience->getActive());

        $this->entityManager->persist($experience);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_experience');
    }

    /**
     * @Route("/admin/retour-experience/editer/{id}", name="admin_edit_experience")
     */
    public function edit(Experience $experience,Request $request): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setUser($this->getUser());
            $experience = $form->getData();

            $illustrations = $form->get('illustration')->getData();

            foreach ($illustrations as $illustration) {
                $file = md5(uniqid()) . '.' . $illustration->guessExtension();

                $illustration->move(
                    $this->getParameter('experiences_directory'),
                    $file
                );

                $ill = new Illustration();
                $ill->setName($file);
                $experience->addIllustration($ill);
            }

            $this->entityManager->persist($experience);
            $this->entityManager->flush();

            $this->addFlash('message', "Le retour d'expérience a bien été mis à jour.");

            return $this->redirectToRoute('admin_experience');
        }

        return $this->render('admin/experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/retour-experience/supprimer/{id}", name="admin_delete_experience")
     */
    public function delete(Experience $experience): Response
    {
        $this->entityManager->remove($experience);
        $this->entityManager->flush();

        $this->addFlash('message', "Le retour d'expérience a bien été supprimé");

        return $this->redirectToRoute('admin_experience');
    }
}