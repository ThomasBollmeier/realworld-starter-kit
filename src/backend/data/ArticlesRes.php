<?php
namespace tbollmeier\realworld\backend\data;

use tbollmeier\realworld\backend\model\User;

class ArticlesRes
{
    private $articles;
    private $currentUser;
    
    public function __construct($articles, User $currentUser = null) {
        $this->articles = $articles;
        $this->currentUser = $currentUser;
    }
    
    public function toJsonString() : string
    {
        $articlesData = array_map(function($article) {
            return (new ArticleRes($article, $this->currentUser))->articleData()["article"];
        }, $this->articles);
        
        return json_encode([
            "articles" => $articlesData
        ]);
    }
    
}

