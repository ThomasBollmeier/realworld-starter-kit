<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\data\UserRes;
use tbollmeier\realworld\backend\http\ValidationError;
use tbollmeier\webappfound\auth\JsonWebToken;
use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;

class UserController
{
    private $secretKey = "geheim";

    public function signUp(Request $req, Response $res)
    {
        $userReg = json_decode($req->getBody());

        if ($userReg === null) {
            $error = new ValidationError();
            $error->addFieldMessage("user", "invalid user data");
            $res->setResponseCode(422)
                ->setBody($error->toJsonString())
                ->send();
            return;
        }

        $user = $userReg->user;

        $userRes = new UserRes(
            $user->email,
            JsonWebToken::encode([
                "username" => $user->username,
                "email" => $user->email
            ], $this->secretKey),
            $user->username,
            "",
            null);

        $res->setHeader("Content-Type", "application/json")
            ->setBody($userRes->toJsonString())
            ->send();

    }

}