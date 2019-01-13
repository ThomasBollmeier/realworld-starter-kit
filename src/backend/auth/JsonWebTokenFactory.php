<?php

namespace tbollmeier\realworld\backend\auth;


class JsonWebTokenFactory implements JWTokenFactory
{
    function createToken(): string
    {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $token = "";
        for ($i=0; $i < 20; $i++) {
            $token .= substr($alphabet, rand(0, 25), 1);
        }

        return $token;
    }
}