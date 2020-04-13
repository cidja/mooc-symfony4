<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article; // pour lui indiquer d'utiliser la class article
use App\Repository\ArticleRepository; //pour lui indiquer d'utiliser la class ArticleRepository

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
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article){ //crée une $article qui sera de type Article

        return $this->render("blog/show.html.twig", [
            "article" => $article
        ]);
    }
}
