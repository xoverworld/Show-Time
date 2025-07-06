<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\Purchased;
use App\Entity\User;
use App\Form\TicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TicketController extends AbstractController
{
    #[Route('/festivals/{id}/buy-ticket', name: 'festivals_ticket')]
    public function index(int $id, Request $request, Festival $festival, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(TicketType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = $form->get('count')->getData();
            $user = $this->getUser();

            for ($i = 0; $i < $count; $i++) {
                $ticket = new Purchased();
                $festival->addPurchasedTicket($ticket);
                $userDetails = $user->getUserDetails();
                $userDetails->addPurchasedTicket($ticket);
                $entityManager->persist($ticket);
            }

            $entityManager->flush();

            $this->addFlash('success', "$count ticket(s) purchased!");
            return $this->redirectToRoute('festival_index', ['id' => $festival->getId()]);
        }

        return $this->render('ticket/buy.html.twig', [
            'form' => $form->createView(),
            'festival' => $festival,
        ]);
    }

    #[Route('/users/{id}/tickets', name: 'show-tickets')]
    public function seeTickets(User $user, Security $security): Response
    {
        if ($this->getUser() !== $user && !$security->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $tickets = $user->getUserDetails()->getPurchasedTickets();

        return  $this->render('ticket/see-tickets.html.twig', ['tickets' => $tickets,]);
    }
}
