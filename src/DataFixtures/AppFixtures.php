<?php

namespace App\DataFixtures;

use App\Entity\Article; //bien renseigné l'utilisation de la classe article 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $article = new Article();
            $article    -> setTitle("Titre de l'article N°$i")
                        -> setContent("<p>Contenu de l'article N°$i</p>")
                        -> setImage("http://placehold.it/350x150")
                        -> setCreatedAt(new \DateTime()); //  \DateTime() pour dire que DateTime fait partie de la base php
            $manager->persist($article); //pour demander a manager de perister ma requête
        }
        $manager->flush(); // indique à manager de réellement "balancer" la requête
    }
}
