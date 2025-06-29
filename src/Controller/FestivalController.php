<?php

namespace App\Controller;

use App\Entity\Festival;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FestivalController extends AbstractController
{
    #[Route('/festival/{id}', name: 'festival')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $count = $entityManager->getRepository(Festival::class)->count([]);
        $festivalsPerPage = 10;
        $festivals = $entityManager->getRepository(Festival::class)->findPaginated($id,$festivalsPerPage);
        $next = ($festivalsPerPage*$id) < $count;
        return $this->render('Festival/festival.html.twig',["festivals" => $festivals, "next" => $next, "page" => $id]);
    }

    #[Route('/festival/index/{id}', name: 'festival_index')]
    public function festivalIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        $festivals = $entityManager->getRepository(Festival::class)->find($id);
        return $this->render('Festival/festival_index.html.twig',["festival" => $festivals]);

    }
}
