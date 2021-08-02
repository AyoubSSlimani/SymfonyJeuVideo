<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Enum\TicketEnum;
use App\Form\TicketType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * HomeController constructor.
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(PostRepository $postRepository, EntityManagerInterface $em)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
    }


    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $postEntity = $this->postRepository->findAll();
        $hasAccess = $this->isGranted("ROLE_WORKER");
        dump($postEntity);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'postEntities' => $postEntity,
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function worker(): Response
    {
        $hasAccess = $this->isGranted("ROLE_WORKER");
        dump($hasAccess);

        return $this->render('layout/admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function readContent($id): Response
    {
        $postEntity = $this->postRepository->find($id);
        $postEntity->setNumberView($postEntity->getNumberView() +1);
        $this->em->persist($postEntity);
        $this->em->flush();
        dump($postEntity);
        return $this->render('layout/post-content.html.twig', [
            'controller_name' => 'HomeController',
            'postEntities' => $postEntity,
        ]);
    }
//
//    /**
//     * @Route("/", name="home")
//     */
//    public function contact(Request $request): Response
//    {
//        $user = $this->getUser();
//
//        $ticketEntity = new Ticket();
//        $form = $this->createForm(TicketType::class, $ticketEntity);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $ticketEntity->setCreatedAt(new \DateTime());
//            $ticketEntity->setUser($user);
//            $ticketEntity->setStatus(TicketEnum::STATUS_TICKET_UNREAD);
//
//            $this->em->persist($ticketEntity);
//            $this->em->flush();
//        }
//
//
//
//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//            'form' => $form->createView(),
//        ]);
//    }
}
