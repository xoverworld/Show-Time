<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\UserDetailsType;
use App\Form\UserType;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    CONST USERS_PER_PAGE = 4;
    #[Route('/users', name: 'users')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->getUser() == null || $this->getUser()->getRoles()[0]=='ROLE_USER'){
            return $this->redirectToRoute('homepage');
        }
        $page = $request->query->getInt('page', 1);
        $count = $entityManager->getRepository(User::class)->count();
        $users = $entityManager->getRepository(User::class)->findPaginated($page,self::USERS_PER_PAGE);
        $next = (self::USERS_PER_PAGE*$page) < $count;
        return $this->render('User/user.html.twig',["users" => $users, "next" => $next, "page" => $page]);
    }

    #[Route('/users/{id}', name: 'user_index')]
    public function userIndex(EntityManagerInterface $entityManager, int $id): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('homepage');
        }
        if($this->getUser()->getRoles()[0]=='ROLE_ADMIN' || $this->getUser()->getId()==$id){
        $user = $entityManager->getRepository(User::class)->find($id);
        return $this->render('User/user_index.html.twig',["user" => $user]);
        }
        return $this->redirectToRoute('homepage');
    }
    #[Route('/users/create', name: 'new_user', priority: 2)]
    public function newUser(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        if($this->getUser() != null && $this->getUser()->getRoles()[0]=='ROLE_USER'){
            return $this->redirectToRoute('homepage');
        }
        $user = new  User();
        $form =  $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user = $this->registration($passwordHasher,$user);
            $user->setRole('ROLE_USER');
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
//            return $this->render('Festival/festival_success.html.twig');
        }
        return $this->render('User/user_create.html.twig', ['form'=>$form]);
    }

    public function registration(UserPasswordHasherInterface $passwordHasher, User $user): User
    {

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);
        return $user;
    }

    #[Route('/users/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function userDelete(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        foreach ($user->getUserDetails()->getPurchasedTickets() as $ticket) {
            $entityManager->remove($ticket);
        }
        $entityManager->remove($user);
        $entityManager->flush();
//        return $this->redirectToRoute('users');
        return $this->render('User/userDeleted.html.twig');
    }

    #[Route('/users/update/{id}', name: 'user_update', methods: ['GET','POST'])]
    public function userUpdate(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('homepage');
        }
        if($this->getUser()->getRoles()[0]=='ROLE_ADMIN' || $this->getUser()->getId()==$id) {
            $user = $entityManager->getRepository(User::class)->find($id);
            $form = $this->createForm(UserUpdateType::class, $user);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
            }

            return $this->render('User/user_update.html.twig', ["user" => $user, "form" => $form->createView()]);
        }
        return $this->redirectToRoute('homepage');
    }

    #[Route('/changePassword/{id}', name: 'user_change_password', methods: ['POST'])]
    public  function userShow(EntityManagerInterface $entityManager, int $id,UserPasswordHasherInterface $passwordHasher): Response
    {
        $newPassword = $_POST["newPassword"];
        $user = $entityManager->getRepository(User::class)->find($id);
        $user->setPassword($newPassword);
        $user = $this->registration($passwordHasher,$user);
        $entityManager->flush();
        return $this->redirectToRoute('user_update', ['id' => $id]);
    }
}
