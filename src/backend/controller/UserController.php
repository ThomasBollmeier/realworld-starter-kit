<?php

namespace tbollmeier\realworld\backend\controller;

use tbollmeier\realworld\backend\data\UserRes;
use tbollmeier\realworld\backend\data\ValidationError;
use tbollmeier\realworld\backend\data\Validator;
use tbollmeier\realworld\backend\model\User;
use tbollmeier\webappfound\http\Request;
use tbollmeier\webappfound\http\Response;

class UserController
{
    use ControllerUtils;

    public function signUp(Request $req, Response $res)
    {
        list($userSignUp, $error) = $this->validateSignUpRequest($req);

        if ($error != null) {
            $this->respondJSON($res, $error->toJsonString(), 422);
            return;
        }

        if (User::findByEmail($userSignUp->email) !== null) {
            $emailExistsError = $this->makeError("email",
                "A user with email address $userSignUp->email exists already");
            $this->respondJSON($res, $emailExistsError, 422);
            return;
        }

        $this->saveNewUser($userSignUp);

        $userRes = new UserRes(
            $userSignUp->email,
            $this->makeAuthToken($userSignUp->username, $userSignUp->email),
            $userSignUp->username,
            "",
            null);

        $this->respondJSON($res, $userRes->toJsonString());
    }

    public function signIn(Request $req, Response $res)
    {
        list($userSignIn, $error) = $this->validateSignInRequest($req);

        if ($error != null) {
            $this->respondJSON($res, $error->toJsonString(), 422);
            return;
        }

        $user = User::findByEmail($userSignIn->email);
        if ($user === null || !password_verify($userSignIn->password, $user->passwordHash)) {
            $emailOrPasswordError = $this->makeError("email",
                "Invalid user email or password");
            $this->respondJSON($res, $emailOrPasswordError, 422);
            return;
        }

        $userRes = new UserRes(
            $user->email,
            $this->makeAuthToken($user->name, $user->email),
            $user->name,
            $user->bio,
            $user->imageUrl);

        $this->respondJSON($res, $userRes->toJsonString());
    }

    public function getCurrent(Request $req, Response $res)
    {
        list($ok, $name, $email) = $this->checkAuthToken($req);

        if (!$ok) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }

        $user = User::findByEmail($email);
        if ($user === null || $user->name != $name) {
            $this->respondJSON($res, $this->makeError("authorization", "invalid"), 401);
            return;
        }

        $userRes = new UserRes(
            $user->email,
            $this->makeAuthToken($user->name, $user->email),
            $user->name,
            $user->bio,
            $user->imageUrl);

        $this->respondJSON($res, $userRes->toJsonString());

    }

    private function validateSignUpRequest(Request $req)
    {
        $data = json_decode($req->getBody());

        if ($data === null) {
            $error = new ValidationError();
            $error->addFieldMessage("user", "invalid user data");
            return [null, $error];
        }

        $validator = new Validator(["user"]);
        $error = $validator->validate($data);
        if ($error != null) {
            return [null, $error];
        }

        $validator = new Validator([
            "username",
            "email",
            "password"
        ]);
        $error = $validator->validate($data->user);

        return $error === null ?
            [$data->user, null] :
            [null, $error];
    }

    private function validateSignInRequest(Request $req)
    {
        $data = json_decode($req->getBody());

        if ($data === null) {
            $error = new ValidationError();
            $error->addFieldMessage("user", "invalid user data");
            return [null, $error];
        }

        $validator = new Validator(["user"]);
        $error = $validator->validate($data)[1];
        if ($error != null) {
            return [null, $error];
        }

        $validator = new Validator([
            "email",
            "password"
        ]);
        $error = $validator->validate($data->user);

        return $error === null ?
            [$data->user, null] :
            [null, $error];
    }

    private function saveNewUser($userData)
    {
        $user = new User();
        $user->email = $userData->email;
        $user->name = $userData->username;
        $user->passwordHash = password_hash($userData->password, PASSWORD_DEFAULT);
        $user->bio = "";
        $user->imageUrl = null;

        $user->save();
    }

}
