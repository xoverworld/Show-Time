<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {

        return $this->render('main/index.html.twig');
    }


    #[Route('/test', name: 'test', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('main/test.html.twig');
    }

    #[Route('/test', name: 'testPost', methods: ['POST'])]
    public function about_post(): Response
    {
        return $this->render('main/testPost.html.twig');
    }
}
