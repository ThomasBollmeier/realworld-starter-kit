<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\EntityDefinition;

class TagDef extends EntityDefinition
{
    public function __construct()
    {
        parent::__construct("tags");
        
        $this->newField("name")->add();
    }
}

