<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Article; //bien renseigné l'utilisation de la classe article 
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create("fr_FR");


        //Créer 3 catégories fakées
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category   ->setTitle($faker->sentence())
                        ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Créer entre 4 et 6 articles
            for($j = 0; $j <= mt_rand(4, 6); $j++){
                $article = new Article();

                $content = '<p>';
                $content .= join($faker->paragraphs(5), '</p><p>'); // .= rajoute après la variable
                $content .= '</p>';
                //ce qu'on fait crée '<p>' on fait ensuite un join(de $faker qui est un array, et qu'on join avec </p><p>) puis on clos avec </p>
                //peut s'écrire comme ça aussi  : 
                // $content = '<p>' . join($faker->paragraphs(5), '</p><p>'). '</p>';

                $article    -> setTitle($faker->sentence())
                            -> setContent($content)
                            -> setImage($faker->imageUrl())
                            -> setCreatedAt($faker->dateTimeBetween('-6 months')) //  va créer des dates aléatoires entre -6mois et aujourd'hui 
                            -> setCategory($category);

                $manager->persist($article); //pour demander a manager de perister ma requête

                //On donne des commentaires à l'article
                for($k=1; $k <= mt_rand(4,10); $k++){
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>'). '</p>'; //= à L32

                    $now = new \DateTime(); // on crée un nouveau objet Date
                    $interval = $now->diff($article->getCreatedAt()); // on fait la diff entre $now et la date de création de $article
                    $days = $interval->days; // on prends l'interval
                    $minimum = '-' . $days . ' days'; // exemple va renvoyer -100 days (utile pour )


                    $comment    -> setAuthor($faker->name) // méthode pour nom au hasard
                                -> setContent($content) 
                                -> setCreatedAt($faker->dateTimeBetween($minimum))
                                -> setArticle($article); // sélectionne l'article 

                    $manager->persist($comment); // on fait persister ça

                }
            }
        }

        $manager->flush(); // indique à manager de réellement "balancer" la requête
    }
}
