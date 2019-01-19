<?php

namespace tbollmeier\realworld\backend\data;


class Validator
{
    private $requiredFields = [];

    public function addRequiredField($name)
    {
        $this->requiredFields[] = $name;
    }

    public function validate($data)
    {
        $ret = null;
        $data_ = is_array($data) ? $data : (array) $data;
        $errors = [];

        foreach ($this->requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $data_)) {
                $errors[$requiredField] = ["Required field '$requiredField' is not given"];
            }
        }

        if (!empty($errors)) {
            $ret = new ValidationError();
            foreach ($errors as $field => $messages) {
                foreach ($messages as $message)
                $ret->addFieldMessage($field, $message);
            }
        }

        return $ret;
    }

}