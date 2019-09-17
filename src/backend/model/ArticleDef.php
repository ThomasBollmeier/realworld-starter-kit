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
                ->add()
            ->newField("updatedAt")
                ->setDbAlias("updated_at")
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
        
            /*
             "article": {
             "slug": "how-to-train-your-dragon",
             "title": "How to train your dragon",
             "description": "Ever wonder how?",
             "body": "It takes a Jacobian",
             "tagList": ["dragons", "training"],
             "createdAt": "2016-02-18T03:22:56.637Z",
             "updatedAt": "2016-02-18T03:48:35.824Z",
             "favorited": false,
             "favoritesCount": 0,
             "author": {
             "username": "jake",
             "bio": "I work at statefarm",
             "image": "https://i.stack.imgur.com/xHWG8.jpg",
             "following": false
             }
             }
             */
                
    }
}

