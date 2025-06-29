<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\User;
use App\Entity\UserDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'users')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $count = $entityManager->getRepository(User::class)->count([]);
        $usersPerPage = 1;
        $users = $entityManager->getRepository(User::class)->findPaginated($id,$usersPerPage);
        $next = ($usersPerPage*$id) < $count;
        return $this->render('User/user.html.twig',["users" => $users, "next" => $next, "page" => $id]);
    }

    #[Route('/user/index/{id}', name: 'user_index')]
    public function userIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        return $this->render('User/user_index.html.twig',["user" => $user]);

    }
//
//    #[Route('/festival/{id}', name: 'festival')]
//    public function index(EntityManagerInterface $entityManager, int $id): Response
//    {
//        $count = $entityManager->getRepository(Festival::class)->count([]);
//        $festivalsPerPage = 10;
//        $festivals = $entityManager->getRepository(Festival::class)->findPaginated($id,$festivalsPerPage);
//        $next = ($festivalsPerPage*$id) < $count;
//        return $this->render('Festival/festival.html.twig',["festivals" => $festivals, "next" => $next, "page" => $id]);
//    }
//
//    #[Route('/festival/index/{id}', name: 'festival_index')]
//    public function festivalIndex(EntityManagerInterface $entityManager, int $id): Response
//    {
//        $festivals = $entityManager->getRepository(Festival::class)->find($id);
//        return $this->render('Festival/festival_index.html.twig',["festival" => $festivals]);
//
//    }
}
