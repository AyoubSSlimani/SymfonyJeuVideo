<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ContactController constructor.
     * @param ContactRepository $contactRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ContactRepository $contactRepository, EntityManagerInterface $em)
    {
        $this->contactRepository = $contactRepository;
        $this->em = $em;
    }


    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {
//        $user = $this->getUser();

//        if ($user != null){
//
//        }
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump($form);
        }

//        if()
            return $this->render('contact/index.html.twig', [
                'controller_name' => 'ContactController',
                'form' => $form->createView(),
                'formulaire' => $form,
            ]);

    }

    /**
     * @Route("/admin/messages", name="admin_messages")
     */
    public function afficheMessage(): Response
    {
        $contactEntities = $this->contactRepository->findAll();

        return $this->render('admin_contact/index.html.twig', [
            'controller_name' => 'AdminTicketController',
            'contactEntities' => $contactEntities
        ]);
    }
}
