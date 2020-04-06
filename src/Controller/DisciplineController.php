<?php

namespace App\Controller;

use App\Entity\Discipline;
use App\Form\DisciplineType;
use App\Repository\DisciplineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DisciplineController extends AbstractController
{
    /**
     * @Route("/discipline", name="discipline")
     */
    public function index(DisciplineRepository $repo)
    {
        $disciplines = $repo -> findAll();
        return $this->render('discipline/index.html.twig', [
            'disciplines' => $disciplines,
        ]);
    }

    /**
     * @Route("/discipline/new",name="discipine_new")
     * @Route("/discipline/edit/{id}",name="discipline_edit")
     */
    public function new(Discipline $discipline = null, Request $request)
    {
        if (!$discipline) {
            $discipline = new Discipline();
        }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($discipline);
            $manager->flush();
            $this->addFlash("success", "Discipline sauvegardé");
            return $this->redirectToRoute('discipline');
        }
        return $this->render('discipline/discipline_new.html.twig', [
            'formDiscipline' => $form->createView(),
            'editTitle' => $discipline->getId() != null,
            'editMode' => $discipline->getId() != null
        ]);
    }
    
    /**
     * @Route("/discipline/delete/{id}",name="discipline_delete")
     */
    public function delete(Discipline $discipline = null)
    {
        if ($discipline != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($discipline);
            $manager->flush();

            $this->addFlash("success", "Discipline supprimé");
        } else {
            $this->addFlash("danger", "Discipline pas trouvé");
        }
        return $this->redirectToRoute('discipline');
    }
}
