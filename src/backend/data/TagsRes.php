<?php
namespace tbollmeier\realworld\backend\data;

class TagsRes
{
    private $tagNames;
    
    public function __construct($tags) 
    {
        $this->tagNames = array_map(function($tag) {
            return $tag->name;
        }, $tags);
    }
    
    public function toJsonString() : string
    {
        return json_encode([
            "tags" => $this->tagNames
        ]);
    }
}

