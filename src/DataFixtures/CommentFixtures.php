<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use App\Entity\Article;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1;$i<=10;$i++) {
            $comment = new Comment();
            $comment->setAuthor("Moi");
            $comment->setContenu("Contenu du commentaire ");
            $comment->setDateComment(new \DateTime());
            $comment->setArticle($this->getReference('article-1'));
            $manager->persist($comment);       
        }
        $manager->flush();
    }

    public function getDependencies() 
    {
        return [
            ArticleFixtures::class
        ];
    }
}
