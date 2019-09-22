<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\EntityDefinition;
use tbollmeier\webappfound\db\QueryOptions;

class TagDef extends EntityDefinition
{
    public function findByName(string $tagName)
    {
        $options = (new QueryOptions())
            ->setFilter("name = :tag_name")
            ->addParam("tag_name", $tagName);
        $tags = $this->query($options);
        
        return !empty($tags) ? $tags[0] : null;
    }
    
    public function __construct()
    {
        parent::__construct("tags");
        
        $this
            ->newField("name")->add()
            ->newAssociation("articles", ArticleDef::class)
                ->setLinkTable("articles_tags")
                ->setSourceIdField("tag_id")
                ->setTargetIdField("article_id")
                ->add();
    }
}

