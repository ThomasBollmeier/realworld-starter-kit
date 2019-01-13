<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\auth\JsonWebTokenFactory;
use tbollmeier\realworld\backend\data\UserRes;
use tbollmeier\realworld\backend\http\Response;
use tbollmeier\realworld\backend\http\ValidationError;
use tbollmeier\webappfound\controller\BaseController;

class UserController extends BaseController
{

    private $factory;

    public function __construct()
    {
        $this->factory = new JsonWebTokenFactory();
    }


    public function signUp()
    {
        $userReg = json_decode($this->getRequestBody());

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
            $this->factory->createToken(),
            $user->username,
            "",
            null);

        (new Response(200))
            ->setBody($userRes)
            ->send();

    }

}