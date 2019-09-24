<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\Entity;
use tbollmeier\webappfound\db\EntityDefinition;

class CommentDef extends EntityDefinition
{
    public function __construct() {
        
        parent::__construct("comments");
        
        $this
            ->newField("body")
                ->add()
            ->newField("createdAt")
                ->setDbAlias("created_at")
                ->setConvToDb([DateTimeUtil::class, "dateTimeToDb"])
                ->setConvFromDb([DateTimeUtil::class, "dateTimeFromDb"])
                ->add()
            ->newField("updatedAt")
                ->setDbAlias("updated_at")
                ->setConvToDb([DateTimeUtil::class, "dateTimeToDb"])
                ->setConvFromDb([DateTimeUtil::class, "dateTimeFromDb"])
                ->add()
            ->newAssociation("authors", UserDef::class)
                ->setLinkTable("comment_authors")
                ->setSourceIdField("comment_id")
                ->setTargetIdField("user_id")
                ->add();
    }

    public function createEntity($id = Entity::INDEX_NOT_IN_DB)
    {
        return new Comment($this, $id);
    }
    
    public function createComment(User $author, string $body)
    {
        $comment = new Comment($this);
        $comment->body = $body;
        $comment->createdAt = new \DateTime();
        $comment->updatedAt = $comment->createdAt;
        $comment->setAuthor($author);
        
        return $comment;
    }
}

