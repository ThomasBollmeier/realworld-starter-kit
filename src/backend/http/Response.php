<?php

namespace tbollmeier\realworld\backend\http;


class Response
{
    private $responseCode;
    private $body;

    public function __construct(int $responseCode)
    {
        $this->responseCode = $responseCode;
        $this->body = "";
    }

    public function setBody(JsonBody $body)
    {
        $this->body = $body;
        return $this;
    }

    public function send()
    {
        http_response_code($this->responseCode);
        echo $this->body->toJsonString();
    }

}