<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\http\Response;
use tbollmeier\webappfound\controller\BaseController;

class IndexController extends BaseController
{
    public function notFound()
    {
        (new Response(404))
            ->setBody("Unknown endpoint")
            ->send();
    }

}