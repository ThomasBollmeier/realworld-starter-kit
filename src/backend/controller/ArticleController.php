<?php
namespace tbollmeier\realworld\backend\controller;

use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\data\ValidationError;
use tbollmeier\realworld\backend\data\Validator;

class ArticleController
{
    use ControllerUtils;
    
    public function create(Request $req, Response $res)
    {
        
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

