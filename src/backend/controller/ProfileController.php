<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\data\ProfileRes;
use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\model\User;

class ProfileController
{
    use ControllerUtils;

    public function getProfile(Request $req, Response $res)
    {
        $userName = $req->getUrlParams()["username"];

        $users = User::query([
            "filter" => "name = :username",
            "params" => [":username" => $userName]
        ]);

        if (count($users) === 1) {

            $user = $users[0];

            $profile = new ProfileRes(
                $user->name,
                $user->bio,
                $user->imageUrl,
                false
            );

            $this->respondJSON($res, $profile->toJsonString());

        } else {

            $this->respondJSON($res,
                $this->makeError("username", "not found"), 404);

        }

    }

}