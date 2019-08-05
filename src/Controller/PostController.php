<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Library\FileUploader;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    public function index(PostRepository $post)
    {
        $posts = $post->findAll();
        return $this->render('views/admin/pages/post/index.html.twig', [ 'posts' => $posts ]);
    }

    public function create(Request $request, FileUploader $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $entity_manager = $this->getDoctrine()->getManager();
            $file = $request->files->get('post')['attachment'];
            if( $file ) {
                $name = $fileUploader->upload($file);
                $post->setImage( $name );
            }
            $entity_manager->persist($post);
            $entity_manager->flush();
            $this->addFlash('success', 'New Post Has been created successfully' );
            return $this->redirect( $this->generateUrl( 'post.index' ) );
        }
        return $this->render('views/admin/pages/post/create.html.twig', [ 'form' => $form->createView() ]);
    }

    public function show($id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException('The Post does not exist');
        }
        return $this->render('views/admin/pages/post/show.html.twig', [ 'post' => $post ]);
    }

    public function edit($id, PostRepository $postRepository, Request $request, FileUploader $fileUploader)
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException('The Post does not exist');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $entity_manager = $this->getDoctrine()->getManager();
            $file = $request->files->get('post')['attachment'];
            if( $file ) {
                $name = $fileUploader->upload($file);
                $post->setImage( $name );
            }
            $entity_manager->persist($post);
            $entity_manager->flush();
            $this->addFlash('success', 'New Post Has been created successfully' );
            return $this->redirect( $this->generateUrl( 'post.index' ) );
        }
        return $this->render('views/admin/pages/post/edit.html.twig', [ 'form' => $form->createView() ]);
    }

    public function delete($id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException('The Post does not exist');
        }
        $entity_manager = $this->getDoctrine()->getManager();
        $entity_manager->remove($post);
        $entity_manager->flush();
        $this->addFlash('success', 'Post was deleted successfully');
        return $this->redirect( $this->generateUrl( 'post.index' ) );
    }
}
