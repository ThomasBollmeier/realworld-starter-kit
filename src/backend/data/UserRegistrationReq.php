<?php

namespace tbollmeier\realworld\backend\data;


use tbollmeier\realworld\backend\http\JsonBody;

class UserRegistrationReq implements JsonBody
{
    private $username;
    private $email;
    private $password;

    public function __construct(
        $username,
        $email,
        $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    function toJsonString() : string
    {
        return json_encode([
            "user" => [
                "username" => $this->username,
                "email" => $this->email,
                "password" => $this->password
            ]
        ]);
    }
}