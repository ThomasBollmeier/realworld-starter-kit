<?php
namespace tbollmeier\realworld\backend\data;

use tbollmeier\realworld\backend\model\Article;
use tbollmeier\realworld\backend\model\User;

class ArticleRes
{
    private $article;
    private $favorited;
    private $following;
    
    public function __construct(Article $article, User $currentUser = null)
    {
        $this->article = $article;
        if ($currentUser == null ) {
            $this->favorited = false;
            $this->following = false;
        } else {
            $this->favorited = $this->article->isFavoriteOf($currentUser);
            $this->following = $currentUser->isFollowing($this->article->getAuthor());
        }
    }
    
    function toJsonString() : string
    {
        $author = $this->article->getAuthor();
        
        return json_encode([
            "article" => [
                "slug" => $this->article->slug,
                "title" => $this->article->title,
                "description" => $this->article->description,
                "body" => $this->article->body,
                "createdAt" => $this->article->createdAt->format(\DateTime::ATOM),
                "updatedAt" => $this->article->updatedAt->format(\DateTime::ATOM),
                "favorited" => $this->favorited,
                "favoritesCount" => $this->article->getFavoritesCount(),
                "author" => [
                    "username" => $author->name,
                    "bio" => $author->bio,
                    "image" => $author->imageUrl,
                    "following" => $this->following
                ]               
            ]
        ]);
    }
 
}

