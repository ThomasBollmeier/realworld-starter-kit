<?php

namespace tbollmeier\realworld\backend\data;


class ProfileRes
{
    private $username;
    private $bio;
    private $image;
    private $following;

    public function __construct(
        $username,
        $bio,
        $image = null,
        $following = false)
    {
        $this->username = $username;
        $this->bio = $bio;
        $this->image = $image;
        $this->following = $following;
    }

    function toJsonString() : string
    {
        return json_encode([
            "profile" => [
                "username" => $this->username,
                "bio" => $this->bio,
                "image" => $this->image,
                "following" => $this->following
            ]
        ]);
    }
}