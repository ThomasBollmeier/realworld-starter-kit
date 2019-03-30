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

            $currentUser = $this->getUserFromAuthToken($req);

            $following = $currentUser != null ?
                $this->follows($currentUser, $user) :
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

        $users = User::query([
            "filter" => "name = :username",
            "params" => [":username" => $userName]
        ]);

        if (count($users) === 1) {

            $user = $users[0];

            if (!$this->follows($currentUser, $user)) {
                $following = $currentUser->following;
                $following[] = $user;
                $currentUser->following = $following;
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

        $users = User::query([
            "filter" => "name = :username",
            "params" => [":username" => $userName]
        ]);

        if (count($users) === 1) {

            $user = $users[0];

            $newFollowing = array_filter($currentUser->following, function ($u) use ($user) {
                return $u->getId() != $user->getId();
            });
            $currentUser->following = $newFollowing;
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

    private function follows(User $follower, User $followed)
    {
        foreach($follower->following as $user) {
            if ($user->getId() === $followed->getId()) {
                return true;
            }
        }

        return false;
    }

}