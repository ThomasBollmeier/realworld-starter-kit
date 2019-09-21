<?php
namespace tbollmeier\realworld\backend\controller;

use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\data\ValidationError;
use tbollmeier\realworld\backend\data\Validator;
use tbollmeier\realworld\backend\model\Model;
use tbollmeier\realworld\backend\data\ArticleRes;

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
            $articleData->body);
        
        $article->save();
        
        $this->respondJSON($res, (new ArticleRes($article))->toJsonString());
    }
    
    public function getArticles(Request $req, Response $res)
    {
        $this->respondJSON($res, "todo", 500);
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

