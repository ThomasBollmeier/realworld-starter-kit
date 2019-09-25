<?php
namespace tbollmeier\realworld\backend\data;

use tbollmeier\realworld\backend\model\Comment;
use tbollmeier\realworld\backend\model\User;

class CommentRes
{
    private $comment;
    private $following;
    
    public function __construct(Comment $comment, User $currentUser = null)
    {
        $this->comment = $comment;
        
        if ($currentUser == null ) {
            $this->following = false;
        } else {
            $this->following = $currentUser->isFollowing($this->comment->getAuthor());
        }
    }
    
    public function commentData()
    {
        $author = $this->comment->getAuthor();
        
        return [
            "comment" => [
                "id" => $this->comment->commentNo,
                "body" => $this->comment->body,
                "createdAt" => $this->comment->createdAt->format(\DateTime::ATOM),
                "updatedAt" => $this->comment->updatedAt->format(\DateTime::ATOM),
                "author" => [
                    "username" => $author->name,
                    "bio" => $author->bio,
                    "image" => $author->imageUrl,
                    "following" => $this->following
                ]
            ]
        ];
    }
    
    public function toJsonString() : string
    {
        return json_encode($this->commentData());
    }
 
}

