<?php

namespace App\Controller;

use App\Entity\PostCategory;
use App\Form\PostCategoryType;
use App\Repository\PostCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminPostCategoryController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PostCategoryRepository
     */
    private $postCategoryRepository;

    /**
     * AdminPostCategoryController constructor.
     * @param EntityManagerInterface $em
     * @param PostCategoryRepository $postCategoryRepository
     */
    public function __construct(EntityManagerInterface $em, PostCategoryRepository $postCategoryRepository)
    {
        $this->em = $em;
        $this->postCategoryRepository = $postCategoryRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $hasAccess = $this->isGranted("ROLE_WORKER");
        dump($hasAccess);
        return $this->render('home/indexAdmin.html.twig', [
            'controller_name' => 'AdminPostCategoryController',
        ]);
    }

    /**
     * @Route("/admin/allPostCategory", name="admin_read_postCategory")
     */
    public function worker(): Response
    {
        $postCategoryEntity = $this->postCategoryRepository->findAll();
        $hasAccess = $this->isGranted("ROLE_WORKER");
        dump($postCategoryEntity);
        return $this->render('layout/create-postcategory.html.twig', [
            'controller_name' => 'AdminPostCategoryController',
            'postCategoryEntities' => $postCategoryEntity,
        ]);
    }

    /**
     * @Route("/admin/postCategory-create", name="admin_create_postCategory")
     */
    public function createPostCategory(Request $request): Response
    {
        dump($request);

        $postCategoryEntity = new PostCategory();
        $form = $this->createForm(PostCategoryType::class, $postCategoryEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
//            dump($postCategoryEntity);
            $this->em->persist($postCategoryEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_postCategory');
        }

        return $this->render('layout/creation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/postCategory-edit/{id}", name="admin_edit_postCategory")
     */
    public function editPostCategory(Request $request, $id): Response
    {

        $postCategoryEntity = $this->postCategoryRepository->find($id);
        $form = $this->createForm(PostCategoryType::class, $postCategoryEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump($postCategoryEntity);
            $this->em->persist($postCategoryEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_postCategory');
        }

        return $this->render('layout/creation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/postCategory-delete/{id}", name="admin_delete_postCategory")
     */
    public function deletePostCategory(Request $request, $id): Response
    {

        $postCategoryEntity = $this->postCategoryRepository->find($id);

            dump($postCategoryEntity);
            $this->em->remove($postCategoryEntity);
            $this->em->flush();


        return $this->redirectToRoute('admin_read_postCategory');
    }

}
