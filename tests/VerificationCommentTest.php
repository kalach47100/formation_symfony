<?php

namespace App\Tests;

use App\Entity\Comment;
use PHPUnit\Framework\TestCase;
use App\Service\VerificationComment;

class VerificationCommentTest extends TestCase
{
    protected $comment;
    protected function setUp():void
    {
        $this->comment = new Comment();
    }
    public function testContientMotInterdit()
    {
        $service = new VerificationComment();

        $this->comment->setContenu("ceci est un commentaire avec un mauvais");
        $result = $service->commentaireNonAutorise($this->comment);
        $this->assertTrue($result);
    }
    public function testNeContientMotInterdit()
    {
        $service = new VerificationComment();
        $this->comment->setContenu("ceci est un commentaire");
        $result = $service->commentaireNonAutorise($this->comment);
        $this->assertFalse($result);
    }
}
