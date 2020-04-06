<?php

namespace App\Controller;

use App\Entity\Athlete;
use App\Form\AthleteType;
use App\Repository\AthleteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AthleteController extends AbstractController
{
    /**
     * @Route("/athlete", name="athlete")
     */
    public function index(AthleteRepository $repo)
    {
        $athletes = $repo->findAll();

        return $this->render('athlete/index.html.twig', [
            'athletes' => $athletes,
        ]);
    }

    /**
     * @Route("/athlete/new",name="athlete_new")
     * @Route("/athlete/edit/{id}",name="athlete_edit")
     */
    public function new(Athlete $athlete = null, Request $request, TranslatorInterface $translator)
    {
        if ($athlete == null) {
            $athlete = new Athlete();
        }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AthleteType::class, $athlete);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get('photo')->getData();

            if ($fichier) {

                $nomFicher = uniqid() . '.' . $fichier->guessExtension();

                try {

                    $fichier->move(
                        $this->getParameter('upload_dir'),
                        $nomFicher
                    );
                } catch (FileExeption $e) {
                    $this->addFlash("danger", "Impossible d'uploader le fichier");
                    return $this->redirectToRoute('athlete');
                }

                $athlete->setPhoto($nomFicher);
            }
            $manager->persist($athlete);
            $manager->flush();
            $this->addFlash("success", "Athlete sauvegardé");
        }

        return $this->render('athlete/athlete_new.html.twig', [
            'formAthlete' => $form->createView(),
        ]);

    }

    /**
     * @Route("/athlete/delete/{id}",name="athlete_delete")
     */
    public function delete(Athlete $athlete = null, TranslatorInterface $translator)
    {
        if ($athlete != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($athlete);
            $manager->flush();

            $this->addFlash("success", "Athlete supprimé");
        } else {
            $this->addFlash("danger", "Athlete pas trouvé");
        }
        return $this->redirectToRoute('athlete');
    }
}
