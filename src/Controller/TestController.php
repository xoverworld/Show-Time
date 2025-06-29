<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test')]
    public function index(): Response
    {
        $i = [1,2,3,4,5];
        $s = 'asdasd';
        return $this->render('test.html.twig', ['i' => $i, "s" => $s]);
    }

    #[Route('/notTest', name:"no Test")]
    public function notTest(): Response
    {
        return $this->render('notTest.html.twig');
    }
}
