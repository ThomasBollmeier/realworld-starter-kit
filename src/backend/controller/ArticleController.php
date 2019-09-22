<?php
namespace tbollmeier\realworld\backend\controller;

use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\data\ValidationError;
use tbollmeier\realworld\backend\data\Validator;
use tbollmeier\realworld\backend\model\Model;
use tbollmeier\realworld\backend\data\ArticleRes;
use tbollmeier\realworld\backend\data\ArticlesRes;

class ArticleController
{
    use ControllerUtils;
    
    public function create(Request $req, Response $res)
    {
        $user = $this->getUserFromAuthToken($req);
        if ($user === null) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }
        
        list($articleData, $error) = $this->validateCreateReq($req);
        
        if ($error != null) {
            $this->respondJSON($res, $error->toJsonString(), 422);
            return;
        }
        
        $article = Model::getArticleDef()->createArticle(
            $user, 
            $articleData->title, 
            $articleData->description, 
            $articleData->body,
            $articleData->tagList);
        
        $article->save();
        
        $this->respondJSON($res, (new ArticleRes($article, $user))->toJsonString());
    }
    
    public function getArticles(Request $req, Response $res)
    {
        $currentUser = $this->getUserFromAuthToken($req);
        $queryParams = $req->getQueryParams();
        
        $articleDef = Model::getArticleDef();
        
        if (array_key_exists("author", $queryParams)) {
            
            $articles = $articleDef->findByAuthor($queryParams["author"]);
            
            if (array_key_exists("tag", $queryParams)) {
                $articles = $articleDef->filterByTag($articles, $queryParams["tag"]);
            }
            
            if (array_key_exists("favorited", $queryParams)) {
                $articles = $articleDef->filterByFavoritedBy($articles, $queryParams["favorited"]);
            }
            
        } else if (array_key_exists("tag", $queryParams)) {
            
            $articles = $articleDef->findByTag($queryParams["tag"]);
            
            if (array_key_exists("favorited", $queryParams)) {
                $articles = $articleDef->filterByFavoritedBy($articles, $queryParams["favorited"]);
            }
            
        } else if (array_key_exists("favorited", $queryParams)) {
            
            $articles = $articleDef->findByFavoritedBy($queryParams["favorited"]);
            
        } else {
            
            $articles = $articleDef->findAll();
            
        }
        
        $this->respondJSON($res, (new ArticlesRes($articles, $currentUser))->toJsonString());
    }
    
    public function favorite(Request $req, Response $res)
    {
        $user = $this->getUserFromAuthToken($req);
        if ($user === null) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }
        
        $slug = $req->getUrlParams()["slug"];
        $article = Model::getArticleDef()->findBySlug($slug);
        if ($article == null) {
            $this->respondJSON($res, $this->makeError("article", "not found"), 404);
            return;
        }
        
        $article->addToFavoritesOf($user);
        $article->save();
        
        $this->respondJSON($res, (new ArticleRes($article, $user))->toJsonString());
    }
    
    public function unfavorite(Request $req, Response $res)
    {
        $user = $this->getUserFromAuthToken($req);
        if ($user === null) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }
        
        $slug = $req->getUrlParams()["slug"];
        $article = Model::getArticleDef()->findBySlug($slug);
        if ($article == null) {
            $this->respondJSON($res, $this->makeError("article", "not found"), 404);
            return;
        }
        
        $article->removeFromFavoritesOf($user);
        $article->save();
        
        $this->respondJSON($res, (new ArticleRes($article, $user))->toJsonString());
    }
    
    private function validateCreateReq(Request $req) 
    {
        $data = json_decode($req->getBody());
        
        if ($data === null) {
            $error = new ValidationError();
            $error->addFieldMessage("article", "invalid article data");
            return [null, $error];
        }
        
        $validator = new Validator(["article"]);
        $error = $validator->validate($data);
        if ($error != null) {
            return [null, $error];
        }
        
        $articleData = $data->article;
        
        $validator = new Validator([
            "title",
            "description",
            "body"
        ]);
        $error = $validator->validate($articleData);
        
        return $error === null ?
            [$articleData, null] :
            [null, $error]; 
        
    }
}

