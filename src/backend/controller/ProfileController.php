<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\data\ProfileRes;
use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\model\Model;

class ProfileController
{
    use ControllerUtils;

    public function getProfile(Request $req, Response $res)
    {
        $userName = $req->getUrlParams()["username"];

        $users = Model::getUserDef()->query([
            "filter" => "name = :username",
            "params" => [":username" => $userName]
        ]);

        if (count($users) === 1) {

            $user = $users[0];

            $currentUser = $this->getUserFromAuthToken($req);

            $following = $currentUser != null ?
                $currentUser->isFollowing($user) :
                false;

            $profile = new ProfileRes(
                $user->name,
                $user->bio,
                $user->imageUrl,
                $following
            );

            $this->respondJSON($res, $profile->toJsonString());

        } else {

            $this->respondJSON($res,
                $this->makeError("username", "not found"), 404);

        }

    }

    public function follow(Request $req, Response $res)
    {
        $currentUser = $this->getUserFromAuthToken($req);
        if ($currentUser === null) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }

        $userName = $req->getUrlParams()["username"];

        $users = Model::getUserDef()->query([
            "filter" => "name = :username",
            "params" => [":username" => $userName]
        ]);

        if (count($users) === 1) {

            $user = $users[0];

            if (!$currentUser->isFollowing($user)) {
                $currentUser->follow($user);
                $currentUser->save();
            }

            $profile = new ProfileRes(
                $user->name,
                $user->bio,
                $user->imageUrl,
                true
            );

            $this->respondJSON($res, $profile->toJsonString());

        } else {

            $this->respondJSON($res,
                $this->makeError("username", "not found"), 404);

        }

    }

    public function unfollow(Request $req, Response $res)
    {
        $currentUser = $this->getUserFromAuthToken($req);
        if ($currentUser === null) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }

        $userName = $req->getUrlParams()["username"];

        $users = Model::getUserDef()->query([
            "filter" => "name = :username",
            "params" => [":username" => $userName]
        ]);

        if (count($users) === 1) {
            
            $user = $users[0];

            $currentUser->unfollow($user);
            $currentUser->save();

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