<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\Entity;

class Article extends Entity
{
    public function getFavoritesCount()
    {
        return count($this->favorites);
    }
    
    public function isFavoriteOf(User $user)
    {
        foreach ($this->favorites as $favUser) {
            if ($favUser->getId() == $user->getId()) {
                return true;
            }
        }
        
        return false;
    }
    
    public function addToFavoritesOf(User $user) 
    {
        if ($this->isFavoriteOf($user)) {
            return; // nothing to do
        }
        $favorites = $this->favorites;
        $favorites[] = $user;
        $this->favorites = $favorites;
    }
    
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

