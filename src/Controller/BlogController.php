<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType; //utilisé pour la classe L44
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // utilisé pour l'appel de la classe de la soumission du formulaire
use App\Entity\Article; // pour lui indiquer d'utiliser la class article
use App\Repository\ArticleRepository; //pour lui indiquer d'utiliser la class ArticleRepository

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; //pour function create

use App\Form\ArticleType; //appel de la classe article type pour function form L45


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo) //va comprendre qu'il a besoin d'une instance de la classe ArticleRepository
    {
       // $repo = $this->getDoctrine()->getRepository(Article::class);   cette ligne est devenu inutile
        $articles = $repo->findAll(); // va réupérer l'ensemble des données de la class Article
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            "articles"        => $articles
        ]);
    }


    /**
    * @Route("/", name="home")
    */
    public function home(){
        return $this->render("blog/home.html.twig");
    }
    
    /**
     * @Route("blog/new", name="blog_create")
     * @Route("blog/{id}/edit", name="blog_edit") //on crée une seconde route 
     */
    public function form(Article $article = null,Request $request, EntityManagerInterface $manager){

        if(!$article){ //si $article n'existe pas on le crée
            $article= new Article();
            
        }
        
        /* $form = $this->createFormBuilder($article)
                ->add('title')
                ->add('content')
                ->add('image')
                ->getForm(); */

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){ //si l'article n'a pas d'id donc nouveau alors date de création sinon rien 
                $article->setCreatedAt(new \DateTime());
            }
           

            $manager->persist($article);

            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        };
        return $this->render("blog/create.html.twig", [
            'formArticle'   => $form->createView(),
            'editMode'      => $article->getId() !== null //si !==null = true donc on est en editmode
        ]);
    }



    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article){ //crée une $article qui sera de type Article

        return $this->render("blog/show.html.twig", [
            "article" => $article
        ]);
    }

}
