<?php

namespace tbollmeier\realworld\backend\auth;


use Firebase\JWT\JWT;

class JsonWebToken
{
    private const API_SECRET_KEY = "geheim";

    public static function encode(array $payload) : string
    {
        return JWT::encode($payload, self::API_SECRET_KEY);
    }

    public static function decode(string $token)
    {
        return JWT::decode($token, self::API_SECRET_KEY, ['HS256']);
    }

}