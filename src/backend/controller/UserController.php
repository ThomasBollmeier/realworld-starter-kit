<?php

namespace tbollmeier\realworld\backend\controller;


class UserController
{
    public function signUp()
    {
        $response = [
          "error" => [
              "body" => "Not implemented"
          ]
        ];
        http_response_code(500);
        echo json_encode($response);
    }

}