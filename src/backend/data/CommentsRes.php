<?php
namespace tbollmeier\realworld\backend\data;

class CommentsRes
{
    private $comments;
    private $currentUser;
    
    public function __construct($comments, $currentUser) 
    {
        $this->comments = $comments;
        $this->currentUser = $currentUser;
    }
    
    public function toJsonString() : string
    {
        $commentDataList = [];
        
        foreach ($this->comments as $comment) {
            $commentData = (new CommentRes($comment, $this->currentUser))->commentData();
            $commentDataList[] = $commentData["comment"];
        }
        
        return json_encode(["comments" => $commentDataList]);
    }
}

