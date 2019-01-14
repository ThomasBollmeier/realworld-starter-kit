<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\http\Response;

class IndexController
{
    public function notFound()
    {
        (new Response(404))
            ->setBody("Unknown endpoint")
            ->send();
    }

}