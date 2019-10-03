<?php
namespace tbollmeier\realworld\backend\controller;

use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\model\Model;
use tbollmeier\realworld\backend\data\TagsRes;

class TagController
{
    use ControllerUtils;
    
    public function getTags(Request $req, Response $res)
    {
        $tags = Model::getTagDef()->query();
        
        $this->respondJSON($res, (new TagsRes($tags))->toJsonString());
    }
}

