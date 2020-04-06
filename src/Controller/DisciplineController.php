<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisciplineController extends AbstractController
{
    /**
     * @Route("/discipline", name="discipline")
     */
    public function index()
    {
        return $this->render('discipline/index.html.twig', [
            'controller_name' => 'DisciplineController',
        ]);
    }
}
