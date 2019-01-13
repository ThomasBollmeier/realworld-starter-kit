<?php

namespace tbollmeier\realworld\backend\auth;


interface JWTokenFactory
{
    function createToken() : string;
}