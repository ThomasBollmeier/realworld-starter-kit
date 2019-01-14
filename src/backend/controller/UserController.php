<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\data\UserRes;
use tbollmeier\realworld\backend\http\Response;
use tbollmeier\realworld\backend\http\ValidationError;
use tbollmeier\webappfound\auth\JsonWebToken;
use tbollmeier\webappfound\http\Request;

class UserController
{
    private $secretKey = "geheim";

    public function signUp(Request $req)
    {
        $userReg = json_decode($req->getBody());

        if ($userReg === null) {
            $error = new ValidationError();
            $error->addFieldMessage("user", "invalid user data");
            (new Response(422))
                ->setBody($error)
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

        (new Response(200))
            ->setBody($userRes)
            ->send();

    }

}