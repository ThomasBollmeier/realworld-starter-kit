<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\EntityDefinition;
use tbollmeier\webappfound\db\Entity;


class ArticleDef extends EntityDefinition
{
    public function createEntity($id = Entity::INDEX_NOT_IN_DB) 
    {
        return new Article($this, $id);
    }
    
    public function createArticle(
        User $author, 
        string $title, 
        string $description,
        string $body,
        $tagList = [])
    {
        $article = new Article($this);
   
        $article->setTitle($title);
        $article->description = $description;
        $article->body = $body;
        $article->createdAt = new \DateTime();
        $article->updatedAt = $article->createdAt;
        
        $article->setAuthor($author);
        
        return $article;
    }
    
    public function __construct()
    {
        parent::__construct("articles");
        
        $this
            ->newField("slug")->add()
            ->newField("title")->add()
            ->newField("description")->add()
            ->newField("body")->add()
            ->newField("createdAt")
                ->setDbAlias("created_at")
                ->setConvToDb([ArticleDef::class, "dateTimeToDb"])
                ->setConvFromDb([ArticleDef::class, "dateTimeFromDb"])
                ->add()
            ->newField("updatedAt")
                ->setDbAlias("updated_at")
                ->setConvToDb([ArticleDef::class, "dateTimeToDb"])
                ->setConvFromDb([ArticleDef::class, "dateTimeFromDb"])
                ->add()
            ->newAssociation("tags", TagDef::class)
                ->setLinkTable("articles_tags")
                ->setSourceIdField("article_id")
                ->setTargetIdField("tag_id")
                ->add()
            ->newAssociation("authors", UserDef::class)
                ->setLinkTable("authors")
                ->setSourceIdField("article_id")
                ->setTargetIdField("user_id")
                ->add()
            ->newAssociation("favorites", UserDef::class)
                ->setLinkTable("favorites")
                ->setSourceIdField("article_id")
                ->setTargetIdField("user_id")
                ->add();
    }
    
    public static function dateTimeToDb(\DateTime $dateTime)
    {
        return $dateTime->format(\DateTime::ATOM);
    }
    
    public static function dateTimeFromDb(string $dateTimeStr)
    {
        return \DateTime::createFromFormat(\DateTime::ATOM, $dateTimeStr);
    }
}

