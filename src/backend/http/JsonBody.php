<?php

namespace tbollmeier\realworld\backend\http;


interface JsonBody
{
    function toJsonString() : string ;
}