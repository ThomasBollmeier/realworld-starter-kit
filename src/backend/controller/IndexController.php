<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;

class IndexController
{
    public function notFound(Request $req, Response $res)
    {
        $url = $req->getUrl();
        $res->setResponseCode(404)
            ->setHeader("Content-Type", "application/text")
            ->setBody("Unknown endpoint: $url")
            ->send();
    }

}