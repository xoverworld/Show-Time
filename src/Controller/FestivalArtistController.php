<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\FestivalArtist;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FestivalArtistController extends AbstractController
{
    #[Route('/festival-artist/{id}', name: 'deleteFestivalArtist')]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {
        $festivalArtist = $entityManager->getRepository(FestivalArtist::class)->find($id);
        $fId = $festivalArtist->getFestival()->getId();
        $entityManager->remove($festivalArtist);
        $entityManager->flush();

        return $this->render('FestivalArtist/deletedFestivalArtist.html.twig', ["festivalID"=>$fId]);
    }
}
