<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\Entity;

class User extends Entity
{
    public function isFollowing(User $other)
    {
        foreach ($this->following as $followed) {
            if ($followed->getId() === $other->getId()) {
                return true;
            }
        }
        
        return false;
    }
    
    public function follow(User $other) 
    {
        $following = $this->following;
        $following[] = $other;
        $this->following = $following;
    }
    
    public function unfollow(User $other)
    {
        $newFollowing = array_filter($this->following, function ($user) use ($other) {
            return $user->getId() != $other->getId();
        });
        $this->following = $newFollowing;    
    }
}

