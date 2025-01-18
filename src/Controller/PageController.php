<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends AbstractController
{

    #[Route('', name: 'app_page_principale')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_page');
    }

    #[Route('/home', name: 'app_page')]
    public function index2(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/CV', name: 'app_cv')]
    public function cv(): Response
    {
        return $this->render('page/CV.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
