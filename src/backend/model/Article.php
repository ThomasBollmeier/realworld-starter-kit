<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\Entity;
use Cocur\Slugify\Slugify;

class Article extends Entity
{
    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->slug = (new Slugify())->slugify($title);
    }
    
    public function getFavoritesCount()
    {
        return count($this->favorites);
    }
    
    public function isFavoriteOf(User $user)
    {
        return $this->isAssociated("favorites", $user);
    }
    
    public function addToFavoritesOf(User $user) 
    {
        if ($this->isFavoriteOf($user)) {
            return; // nothing to do
        }
        $this->associate("favorites", $user);
    }
    
    public function removeFromFavoritesOf(User $user) 
    {
        $this->dissociate("favorites", $user);
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

