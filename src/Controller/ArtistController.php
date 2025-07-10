<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArtistController extends AbstractController
{
    const ARTISTS_PER_PAGE = 6;

    #[Route('/artists', name: 'artist')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $count = $entityManager->getRepository(Artist::class)->count([]);
        $artists = $entityManager->getRepository(Artist::class)->findPaginated($page,self::ARTISTS_PER_PAGE);
        $next = (self::ARTISTS_PER_PAGE*$page) < $count;
        return $this->render('Artist/artist.html.twig',["artists" => $artists, "next" => $next, "page" => $page]);
    }

    #[Route('/artists/{id}', name: 'artist_index')]
    public function artistIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        $artists = $entityManager->getRepository(Artist::class)->find($id);
        return $this->render('Artist/artist_index.html.twig',["artist" => $artists]);

    }

    #[Route('/artists/new', name: 'artist_new',priority: 2)]
    public  function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->getUser() == null || $this->getUser()->getRoles()[0] !== 'ROLE_ADMIN')
        {
            return $this->redirectToRoute('artist');
        }
        $artist = new Artist();
        $form =  $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $artist = $form->getData();
            $entityManager->persist($artist);
            $entityManager->flush();
            $this->addFlash('success', 'New Artist created successfully');
            return $this->redirectToRoute('artist');
        }
        return $this->render('Artist/artist_new.html.twig',["form" => $form->createView()]);
    }

    #[Route('/artists/delete/{id}', name: 'artist_delete', methods: ['POST'])]
    public function userDelete(EntityManagerInterface $entityManager, int $id): Response
    {

        $artist = $entityManager->getRepository(Artist::class)->find($id);
        foreach($artist->getFestivalArtists() as $festivalArtist)
        {
            $entityManager->remove($festivalArtist);
            $entityManager->flush();
        }
        $entityManager->remove($artist);
        $entityManager->flush();
        return $this->render('Artist/artistDeleted.html.twig');
    }

    #[Route('/artists/update/{id}', name: 'artist_update', methods: ['GET','POST'])]
    public function userUpdate(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        if($this->getUser() == null ||$this->getUser()->getRoles()[0] !== 'ROLE_ADMIN')
        {
            return $this->redirectToRoute('artist_index',['id' => $id]);
        }
        $artist = $entityManager->getRepository(Artist::class)->find($id);
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Artist updated successfully');
        }

        return $this->render('Artist/artistUpdate.html.twig',["artist"=>$artist,"form" => $form->createView()]);
    }
    #[Route('/artists/{id}/image', name: 'artists_image', methods: ['GET','POST'])]
    public function artistImage(int $id, Artist $artist) : Response
    {
        if($this->getUser() == null || $this->getUser()->getRoles()[0] !== 'ROLE_ADMIN')
        {
            return $this->redirectToRoute('artist_index', ['id' => $id]);
        }
        return $this->render('Artist/addDescription.html.twig', ['artist'=>$artist]);
    }

    #[Route('/add-image/{id}', name: 'add_image', methods: ['POST'])]
    public function addImage(EntityManagerInterface $entityManager,int $id): Response
    {
        $artist = $entityManager->getRepository(Artist::class)->find($id);
        $image = $_POST['image'];
        $artist->setImageLink($image);
        $entityManager->persist($artist);
        $entityManager->flush();

        return $this->redirectToRoute('artist_index', ['id' => $artist->getId()]);
    }
}
