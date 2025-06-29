<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Festival;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist/{id}', name: 'artist')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $count = $entityManager->getRepository(Artist::class)->count([]);
        $artistsPerPage = 10;
        $artists = $entityManager->getRepository(Artist::class)->findPaginated($id,$artistsPerPage);
        $next = ($artistsPerPage*$id) < $count;
        return $this->render('Artist/artist.html.twig',["artists" => $artists, "next" => $next, "page" => $id]);
    }

    #[Route('/artist/index/{id}', name: 'artist_index')]
    public function artistIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        $artists = $entityManager->getRepository(Artist::class)->find($id);
        return $this->render('Artist/artist_index.html.twig',["artist" => $artists]);

    }
}
