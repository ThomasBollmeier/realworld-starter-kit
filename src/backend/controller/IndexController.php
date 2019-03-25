<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;

class IndexController
{
    use ControllerUtils;

    public function notFound(Request $req, Response $res)
    {
        $url = $req->getUrl();
        $this->respondJSON($res,
            $this->makeError("url", "Unknown endpoint: $url"), 404);
    }

}