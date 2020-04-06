<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    /**
     * @Route("/country", name="country")
     */
    public function index(CountryRepository $repo)
    {
        $countries = $repo -> findAll();
        return $this->render('country/index.html.twig', [
            'countries' => $countries,
        ]);
    }

    /**
     * @Route("/country/new",name="country_new")
     * @Route("/country/edit/{id}",name="country_edit")
     */
    public function save(Country $country = null, Request $request)
    {
        if (!$country) {
            $country = new Country();
        }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('flag')->getData();

            if ($fichier) {

                $nomFicher = uniqid() . '.' . $fichier->guessExtension();

                try {

                    $fichier->move(
                        $this->getParameter('upload_dir'),
                        $nomFicher
                    );
                } catch (FileExeption $e) {
                    $this->addFlash("danger", "Impossible d'uploader le fichier");
                    return $this->redirectToRoute('country');
                }

                $country->setFlag($nomFicher);
            }
            $manager->persist($country);
            $manager->flush();
            $this->addFlash("success", "Pays sauvegardé");
            return $this->redirectToRoute('country');
        }
        return $this->render('country/country_new.html.twig', [
            'formCountry' => $form->createView(),
            'editTitle' => $country->getId() != null,
            'editMode' => $country->getId() != null
        ]);
    }

    /**
     * @Route("/country/delete/{id}",name="country_delete")
     */
    public function delete(Country $country = null)
    {
        if ($country != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($country);
            $manager->flush();

            $this->addFlash("success", "Pays supprimé");
        } else {
            $this->addFlash("danger", "Pays pas trouvé");
        }
        return $this->redirectToRoute('country');
    }
}
