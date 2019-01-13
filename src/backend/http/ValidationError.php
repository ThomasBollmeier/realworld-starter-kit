<?php

namespace tbollmeier\realworld\backend\http;


class ValidationError implements JsonBody
{
    private $messagesPerField;

    public function __construct()
    {
        $this->messagesPerField = [];
    }

    public function addFieldMessage(string $field, string $message)
    {
        if (!array_key_exists($field, $this->messagesPerField)) {
            $this->messagesPerField[$field] = [$message];
        } else {
            $this->messagesPerField[$field][] = $message;
        }
    }

    function toJsonString(): string
    {
        return json_encode([
            "error" => $this->messagesPerField
        ]);
    }
}