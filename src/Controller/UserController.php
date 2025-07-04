<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\UserDetailsType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    CONST USERS_PER_PAGE = 4;
    #[Route('/users', name: 'users')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $count = $entityManager->getRepository(User::class)->count();
        $users = $entityManager->getRepository(User::class)->findPaginated($page,self::USERS_PER_PAGE);
        $next = (self::USERS_PER_PAGE*$page) < $count;
        return $this->render('User/user.html.twig',["users" => $users, "next" => $next, "page" => $page]);
    }

    #[Route('/users/{id}', name: 'user_index')]
    public function userIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        return $this->render('User/user_index.html.twig',["user" => $user]);

    }
    #[Route('/users/create', name: 'new_user', priority: 2)]
    public function newUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = new  User();
        $form =  $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
//            return $this->render('Festival/festival_success.html.twig');
        }
        return $this->render('User/user_create.html.twig', ['form'=>$form]);
    }

    #[Route('/users/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function userDelete(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($user);
        $entityManager->flush();
//        return $this->redirectToRoute('users');
        return $this->render('User/userDeleted.html.twig');
    }

    #[Route('/users/update/{id}', name: 'user_update', methods: ['GET','POST'])]
    public function userUpdate(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('User/user_update.html.twig',["user"=>$user,"form" => $form->createView()]);
    }
}
