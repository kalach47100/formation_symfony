<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i<=10;$i++) {
            $article = new Article();
            $article->setTitre("Article n°".$i);
            $article->setContenu("Contenu de l'article n°".$i);
            $date = new \DateTime();
            $date->modify('-'.$i.' days');
            $article->setDateCreation($date);

            $this->addReference('article-'.$i, $article);

            $manager->persist($article);
        }
        

        $manager->flush();
    }
}
