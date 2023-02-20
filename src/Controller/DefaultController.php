<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\VerificationComment;
use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="liste_articles", methods={"GET"})
     */

    public function listeArticles(ArticleRepository $articleRepository):Response
    {
        // $articles = $articleRepository->findBy([
        //     // 'titre' => "Article n°1",
        // ], [
        //     'dateCreation' => "ASC",
        // ]);
        // $articles = $articleRepository->findByDateCreation(new \DateTime());
        $articles = $articleRepository->findAll();
        // dump($articles);die;
        
        return $this->render('default/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{id}", name="vue_article", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */

    public function vueArticle(Request $request, Article $article, EntityManagerInterface $manager, VerificationComment $verifService, FlashBagInterface $session) //, $id
    {
        // $article = $articleRepository->findByDateCreation(new \DateTime());
        // dump($article);die;
        // $article = $articleRepository->find($id);
        // dump($article);die;
       
        $comment = new Comment();
        $comment->setArticle($article);
        $form =$this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            // dump($verifService->commentaireNonAutorise($comment));die;
            if($verifService->commentaireNonAutorise($comment) === false) 
            {
                $manager->persist($comment);
                $manager->flush();
                return $this->redirectToRoute('vue_article', ['id' => $article->getId()]);
            } else{
                $session->add("danger", "Le commentaire contient un mot interdit");
            }
            
        }
        return $this->render('default/vue.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/ajouter", name="ajout_article")
     */
    public function ajouter(Request $request, EntityManagerInterface $manager) //CategoryRepository $categoryRepository, 
    {
        // $form =$this->createFormBuilder()
        //     ->add('titre', TextType::class, [
        //         'label' => "Titre de l'article"
        //     ])
        //     ->add('contenu', TextareaType::class)
        //     ->add('dateCreation', DateType::class, [
        //         'widget' => 'single_text',
        //     ])
        //     ->getForm();
        $article = new Article();

        $form =$this->createForm(ArticleType::class, $article);

        // $form =$this->createForm(ArticleType::class, $article, [
        //     'method' => 'GET',
        //     'csrf_protection' => false,
        // ]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            // $article = new Article();
            // $article->setTitre($form->get('titre')->getData());
            // $article->setContenu($form->get('contenu')->getData());
            // $article->setDateCreation($form->get('dateCreation')->getData());

            // $category = $categoryRepository->findOneBy([
            //     'name' => 'Sport'
            // ]);

            // $article->addCategory($category);

            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('liste_articles');
        }

        return $this->render("default/ajout.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
