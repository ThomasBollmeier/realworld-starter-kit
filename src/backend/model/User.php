<?php

namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\ActiveRecord;

class User extends ActiveRecord
{
    public static function findByEmail($email)
    {
        $users = User::query([
            "filter" => "email = :email",
            "params" => [":email" => $email]
        ]);

        return count($users) !== 0 ? $users[0] : null;
    }

    public function __construct(int $id = self::INDEX_NOT_IN_DB, $sqlBuilder = null)
    {
        parent::__construct($id, $sqlBuilder);

        $this->defineTable("users");
        $this->defineField("email");
        $this->defineField("name");
        $this->defineField("passwordHash", ["dbAlias" => "password_hash"]);
        $this->defineField("bio");
        $this->defineField("imageUrl", ["dbAlias" => "image_url"]);

        $this->defineAssoc("followers",
            User::class,
            false,
            [
                "linkTable" => "followers",
                "sourceIdField" => "followed_id",
                "targetIdField" => "follower_id",
                "readOnly" => true
            ]);

        $this->defineAssoc("following",
            User::class,
            false,
            [
                "linkTable" => "followers",
                "sourceIdField" => "follower_id",
                "targetIdField" => "followed_id"
            ]);
    }

}