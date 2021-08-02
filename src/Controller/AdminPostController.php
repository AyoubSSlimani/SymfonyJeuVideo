<?php


namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPostController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * AdminPostController constructor.
     * @param EntityManagerInterface $em
     * @param PostRepository $postRepository
     */
    public function __construct(EntityManagerInterface $em, PostRepository $postRepository)
    {
        $this->em = $em;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index(): Response
    {
        $hasAccess = $this->isGranted("ROLE_WORKER");
        dump($hasAccess);
        return $this->redirectToRoute('admin_post');
    }

    /**
     * @Route("/admin/allPost", name="admin_read_post")
     */
    public function worker(): Response
    {
        $postEntity = $this->postRepository->findAll();
        $hasAccess = $this->isGranted("ROLE_WORKER");
        dump($postEntity);
        return $this->render('layout/create-post.html.twig', [
            'controller_name' => 'AdminPostController',
            'postEntities' => $postEntity,
        ]);
    }

    /**
     * @Route("/admin/post-create", name="admin_create_post")
     */
    public function createPost(Request $request): Response
    {
        dump($request);

        $postEntity = new Post();
        $form = $this->createForm(PostType::class, $postEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $postEntity->setCreatedAt(new \DateTime());
            //RECUPERER UTILISTAUER
            $user = $this->getUser();
            $postEntity->setUser($user);
            $postEntity->setStatus('new');
            $postEntity->setNumberView('');


            $this->em->persist($postEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_post');
        }

        return $this->render('layout/creationPost.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/post-edit/{id}", name="admin_edit_post")
     */
    public function editPost(Request $request, $id): Response
    {

        $postEntity = $this->postRepository->find($id);
        $form = $this->createForm(PostType::class, $postEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dump($postEntity);
            $this->em->persist($postEntity);
            $this->em->flush();
            return $this->redirectToRoute('admin_read_post');
        }

        return $this->render('layout/creationPost.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/post-delete/{id}", name="admin_delete_post")
     */
    public function deletePost(Request $request, $id): Response
    {

        $postEntity = $this->postRepository->find($id);

        dump($postEntity);
        $this->em->remove($postEntity);
        $this->em->flush();


        return $this->redirectToRoute('admin_read_post');
    }


    public function addView($id): Response
    {
        $user = $this->getUser();
        $postEntity = $this->postRepository->find($id);

        dump($user);
        $this->em->persist($postEntity);
        $this->em->flush();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'AdminPostController',
            'postEntities' => $postEntity
        ]);
    }

}