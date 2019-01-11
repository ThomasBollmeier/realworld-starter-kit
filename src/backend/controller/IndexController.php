<?php

namespace tbollmeier\realworld\backend\controller;


class IndexController
{
    public function notFound()
    {
        $response = [
          "error" => [
              "body" => "Unknown endpoint"
          ]
        ];
        http_response_code(404);
        echo json_encode($response);
    }

}