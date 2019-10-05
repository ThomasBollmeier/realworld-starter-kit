<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\EntityDefinition;
use tbollmeier\webappfound\db\Entity;

class ArticleDef extends EntityDefinition
{
    public function findAll() {
        
        return $this->query(["orderBy" => "updated_at DESC"]);
        
    }
    
    public function findByAuthor(string $authorName) 
    {
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
        ON au.article_id = a.id
    JOIN 
    users as u
        ON u.id = au.user_id
WHERE
    u.name = :author_name
ORDER BY
    a.updated_at DESC
SQL;
        
        return $this->queryCustom($sql, [":author_name" => $authorName]);
    }
    
    public function findByTag(string $tagName)
    {
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
    articles_tags as at
        ON at.article_id = a.id
    JOIN
    tags as t
        ON t.id = at.tag_id
WHERE
    t.name = :tag_name
ORDER BY
    a.updated_at DESC
SQL;
        
        return $this->queryCustom($sql, [":tag_name" => $tagName]);
    }

    public function findByFavoritedBy(string $favoritedBy)
    {
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
    favorites as f
        ON f.article_id = a.id
    JOIN
    users as u
        ON u.id = f.user_id
WHERE
    u.name = :favorited_by
ORDER BY
    a.updated_at DESC
SQL;
        
        return $this->queryCustom($sql, [":favorited_by" => $favoritedBy]);
    }
    
    public function findFeed($followerId)
    {
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
        ON au.article_id = a.id
    JOIN
    followers as f
        ON f.followed_id = au.user_id
WHERE
    f.follower_id = :follower_id
ORDER BY
    a.updated_at DESC
SQL;
        
        return $this->queryCustom($sql, [":follower_id" => $followerId]);
    }
    
    public function findBySlug($slugName)
    {
        $articles = $this->query([
            "filter" => "slug = :slug_name",
            "params" => [
                ":slug_name" => $slugName
            ]
        ]);
        
        return !empty($articles) ? $articles[0] : null;
    }
    
    public function findBySlugPattern(string $slugName)
    {
        $pattern = $slugName . "%";
        $sql =<<<SQL
SELECT
     id
    ,slug
    ,title
    ,description
    ,body
    ,created_at
    ,updated_at
FROM
    articles
WHERE
    slug LIKE :pattern
SQL;
        return $this->queryCustom($sql, [":pattern" => $pattern]);
    }
    
    public function filterByTag($articles, $tagName)
    {
        return array_values(array_filter($articles, function ($article) use ($tagName) {
            foreach ($article->tags as $tag) {
                if (strtolower($tag->name) === strtolower($tagName)) {
                    return true;
                }
            }
            return false;
        }));
    }
    
    public function filterByFavoritedBy($articles, $favoritedBy)
    {
        return array_values(array_filter($articles, function ($article) use ($favoritedBy) {
            foreach ($article->favorites as $favorite) {
                if ($favorite->name === $favoritedBy) {
                    return true;
                }
            }
            return false;
        }));
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
        $article->setTags($tagList);
        
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
                ->setConvToDb([DateTimeUtil::class, "dateTimeToDb"])
                ->setConvFromDb([DateTimeUtil::class, "dateTimeFromDb"])
                ->add()
            ->newField("updatedAt")
                ->setDbAlias("updated_at")
                ->setConvToDb([DateTimeUtil::class, "dateTimeToDb"])
                ->setConvFromDb([DateTimeUtil::class, "dateTimeFromDb"])
                ->add()
            ->newAssociation("tags", TagDef::class)
                ->setLinkTable("articles_tags")
                ->setSourceIdField("article_id")
                ->setTargetIdField("tag_id")
                ->setOnDeleteCallback([$this, "onTagDeleted"])
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
                ->add()
            ->newAssociation("comments", CommentDef::class)
                ->setLinkTable("article_comments")
                ->setIsComposition()
                ->setSourceIdField("article_id")
                ->setTargetIdField("comment_id")
                ->add();
                
    }
    
    public function onTagDeleted(Entity $tag)
    {
        if (empty($tag->articles)) {
            $tag->delete();
        }
    }
}

