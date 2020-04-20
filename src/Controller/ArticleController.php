<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/",name="homepage")
     */
    public function homePage(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->render('homepage/homepage.html.twig', [
            'title' => 'Articles',
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/article-new", name="article_new")
     */
    public function articleNew(Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        $formData = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formData);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article-edit/{id}", name="article_edit")
     */
    public function articleEdit(Request $request, EntityManagerInterface $em, Article $article)
    {

        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        $formData = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formData);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render('article/edit.html.twig', [
            'title' => 'ArticleController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/article-view", name="article_view")
     */
    public function articleView(ArticleRepository $articleRepository, Request $request)
    {
        $q = $request->request->get('chosen');
        $article = $articleRepository->findOneBy(['id' => $q]);

        return $this->render('article/view.html.twig', [
            'title' => 'Article view',
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article-delete", name="article_delete", methods={"POST"})
     */
    public function articleDelete(ArticleRepository $articleRepository, Request $request, EntityManagerInterface $em)
    {
        $q = $request->request->get('delete_id');
        $article = $articleRepository->findOneBy(['id' => $q]);
        $em->remove($article);
        $em->flush();
//        return $this->render('article/view.html.twig', [
//            'title' => 'Article view',
//            'article' => $article,
//        ]);
    }
}
