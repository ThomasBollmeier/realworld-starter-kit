<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\Entity;

class Comment extends Entity
{
    public function getAuthor()
    {
        $authors = $this->authors;
        if (count($authors) != 1) {
            return null;
        }
        
        return $authors[0];
    }
    
    public function setAuthor(User $author)
    {
        $this->authors = [$author];
    }
}

