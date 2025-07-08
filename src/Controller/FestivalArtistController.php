<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\FestivalArtist;
use App\Form\FestivalArtistType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FestivalArtistController extends AbstractController
{
    #[Route('/festival-artist/{id}', name: 'deleteFestivalArtist', methods: ['POST'])]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {
        $festivalArtist = $entityManager->getRepository(FestivalArtist::class)->find($id);
        $fId = $festivalArtist->getFestival()->getId();
        $entityManager->remove($festivalArtist);
        $entityManager->flush();

        return $this->render('FestivalArtist/deletedFestivalArtist.html.twig', ["festivalID"=>$fId]);
    }

    #[Route('/festivals/{id}/add-artist', name: 'addFestivalArtist')]
    public function addFestivalArtist(Festival $festival,EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->getUser() == null)
        {
            return $this->redirectToRoute('homepage');
        }
        if($this->getUser()->getRoles()[0] !== "ROLE_ADMIN")
        {
            return $this->redirectToRoute('festival');
        }
        $festivalArtist = new FestivalArtist();
        $festivalArtist->setFestival($festival);
        $form=$this->createForm(FestivalArtistType::class,$festivalArtist);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($festivalArtist);
            $entityManager->flush();

            $this->addFlash('success', 'Artist added to festival!');
            return $this->redirectToRoute('festival_index', ['id'=>$festival->getId()]);
        }


        return $this->render('FestivalArtist/createFestivalArtist.html.twig',['form'=>$form, 'festival' => $festival,]);
    }
}
