<?php


namespace App\Controller;


use App\Enum\TicketEnum;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTicketController extends AbstractController
{
    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AdminTicketController constructor.
     * @param TicketRepository $ticketRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(TicketRepository $ticketRepository, EntityManagerInterface $em)
    {
        $this->ticketRepository = $ticketRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/ticket", name="admin_ticket")
     */
    public function index(): Response
    {
        $ticketEntities = $this->ticketRepository->findAll();

        return $this->render('admin_ticket/index.html.twig', [
            'controller_name' => 'AdminTicketController',
            'ticketEntities' => $ticketEntities
    ]);
    }

    /**
     * @Route("/admin/ticket-detail/{id}", name="admin_ticket_detail")
     */
    public function detailTicket(string $id)
    {
        $ticket = $this->ticketRepository->find($id);
        $ticket->setStatus(TicketEnum::STATUS_TICKET_READ);
        $this->em->persist($ticket);
        $this->em->flush();

        return $this->render('admin_ticket/ticket_detail.html.twig', [
            'controller_name' => 'AdminTicketController',
            'ticketEntities' => $ticket
        ]);
    }

    /**
     * @Route("/admin/ticket-treated/{id}", name="admin_ticket_treated")
     */
    public function treatTicket($id){
        $ticket = $this->ticketRepository->find($id);
        $ticket->setStatus(TicketEnum::STATUS_TICKET_TREATED);
        $ticket->setTreatedAT(new \DateTime());
        $this->em->persist($ticket);
        $this->em->flush();

        return $this->redirectToRoute('admin_ticket');

    }


}