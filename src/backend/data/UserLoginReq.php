<?php

namespace tbollmeier\realworld\backend\data;


use tbollmeier\realworld\backend\http\JsonBody;

class UserLoginReq implements JsonBody
{
    private $email;
    private $password;

    public function __construct(
        $email,
        $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    function toJsonString() : string
    {
        return json_encode([
            "user" => [
                "email" => $this->email,
                "password" => $this->password
            ]
        ]);
    }
}