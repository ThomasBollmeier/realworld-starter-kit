<?php

namespace tbollmeier\realworld\backend\data;


use tbollmeier\realworld\backend\http\JsonBody;

class UserRes implements JsonBody
{
    private $email;
    private $token;
    private $username;
    private $bio;
    private $image;

    public function __construct(
        $email,
        $token,
        $username,
        $bio,
        $image = null)
    {
        $this->email = $email;
        $this->token = $token;
        $this->username = $username;
        $this->bio = $bio;
        $this->image = $image;
    }

    function toJsonString() : string
    {
        return json_encode([
            "user" => [
                "email" => $this->email,
                "token" => $this->token,
                "username" => $this->username,
                "bio" => $this->bio,
                "image" => $this->image
            ]
        ]);
    }
}