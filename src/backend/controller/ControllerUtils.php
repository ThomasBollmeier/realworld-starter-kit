<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\auth\JsonWebToken;
use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;
use tbollmeier\realworld\backend\model\Model;


trait ControllerUtils
{

    private function makeError($key, $value)
    {
        return json_encode([
            "error" => [
                $key => $value
            ]
        ]);
    }

    private function respondJSON(Response $res, $body, $responseCode=200)
    {
        $res->setHeader("Content-Type", "application/json; charset=utf-8")
            ->setResponseCode($responseCode)
            ->setBody($body)
            ->send();
    }

    private function makeAuthToken($name, $email)
    {
        return JsonWebToken::encode([
            "username" => $name,
            "email" => $email
        ]);
    }

    private function checkAuthToken(Request $req)
    {
        $value = $req->getRequestHeader("Authorization");
        if ($value === null) {
            return [false, null, null];
        }

        $parts = explode(" ", $value);

        if (count($parts) != 2) {
            return [false, null, null];
        }

        $userData = JsonWebToken::decode($parts[1]);

        return [true, $userData->username, $userData->email];

    }

    private function getUserFromAuthToken(Request $req)
    {
        list($ok, $name, $email) = $this->checkAuthToken($req);
        if (!$ok) return null;

        $user = Model::getUserDef()->findByEmail($email);
        return ($user !== null && $user->name == $name) ?
            $user :
            null;
    }

}