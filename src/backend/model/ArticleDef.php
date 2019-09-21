<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\EntityDefinition;
use tbollmeier\webappfound\db\Entity;
use tbollmeier\webappfound\db\Environment;


class ArticleDef extends EntityDefinition
{
    public function findAll() {
        
        return $this->query();
        
    }
    
    public function findByAuthor(string $authorName) 
    {
        $articles = [];
        
        $sql =<<<SQL
SELECT
     a.id
    ,a.slug
    ,a.title
    ,a.description
    ,a.body
    ,a.created_at
    ,a.updated_at
FROM 
    articles as a 
    JOIN 
    authors as au
        ON a.id = au.article_id
    JOIN 
    users as u
        ON u.id = au.user_id
WHERE
    u.name = :author_name
SQL;
     
        $db = Environment::getInstance()->dbConn;
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ":author_name" => $authorName
        ]);
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        while ($row) {
            $article = $this->createEntity($row['id']);
            $article->setRowData($row);
            $articles[] = $article;
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        $stmt->closeCursor();
        
        return $articles;
    }
    
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
        return $dateTime->format("Y-m-d H:i:s");
    }
    
    public static function dateTimeFromDb(string $dateTimeStr)
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $dateTimeStr);
    }
}

