<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Festival;
use App\Entity\FestivalArtist;
use App\Form\FestivalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FestivalController extends AbstractController
{
    const FESTIVALS_PER_PAGE = 4;
    #[Route('/festivals', name: 'festival')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $count = $entityManager->getRepository(Festival::class)->count([]);
        $festivals = $entityManager->getRepository(Festival::class)->findPaginated($page,self::FESTIVALS_PER_PAGE);
        $next = (self::FESTIVALS_PER_PAGE*$page) < $count;
        return $this->render('Festival/festival.html.twig',["festivals" => $festivals, "next" => $next, "page" => $page]);
    }

    #[Route('/festivals/{id}', name: 'festival_index')]
    public function festivalIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        $festivals = $entityManager->getRepository(Festival::class)->find($id);
        return $this->render('Festival/festival_index.html.twig',["festival" => $festivals]);

    }

    #[Route('/festivals/create', name: 'festival_create', priority: 2)]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $festival = new Festival();
        $form =  $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $festival = $form->getData();
            $entityManager->persist($festival);
            $entityManager->flush();

            return $this->redirectToRoute('festival');
//            return $this->render('Festival/festival_success.html.twig');
        }
        return $this->render('Festival/festival_create.html.twig', ['form'=>$form]);
    }

    #[Route('/festivals/delete/{id}', name: 'festival_delete', methods: ['POST'])]
    public function userDelete(EntityManagerInterface $entityManager, int $id): Response
    {
        $festival = $entityManager->getRepository(Festival::class)->find($id);
        foreach($festival->getFestivalArtists() as $festivalArtist)
        {
            $entityManager->remove($festivalArtist);
            $entityManager->flush();
        }
        $entityManager->remove($festival);
        $entityManager->flush();
        return $this->render('Festival/festivalDeleted.html.twig');
    }

    #[Route('/festivals/update/{id}', name: 'festival_update', methods: ['GET','POST'])]
    public function userUpdate(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $festival = $entityManager->getRepository(Festival::class)->find($id);
        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('Festival/festivalUpdate.html.twig',["festival"=>$festival,"form" => $form->createView()]);
    }


    #[Route('/add/festivalArtist', name: 'festivalArtist')]
    public function addArtistToFestival(EntityManagerInterface $entityManager): Response
    {
        $f = $entityManager->getRepository(Festival::class)->find(4);

        $a = $entityManager->getRepository(Artist::class)->find(2);

        $festivalArtist = new FestivalArtist();
        $festivalArtist->setStageName("stage1");
        $f->addFestivalArtist($festivalArtist);
        $a->addFestivalArtist($festivalArtist);
//        dd($festivalArtist);

        $entityManager->persist($festivalArtist);
        $entityManager->flush();

        return $this->redirectToRoute('festival');

    }


}
