<?php
namespace tbollmeier\realworld\backend\model;

use tbollmeier\webappfound\db\Entity;
use tbollmeier\webappfound\db\EntityDefinition;

class UserDef extends EntityDefinition
{
    
    public function createUser($email, $name, $password)
    {
        $user = $this->createEntity();
        $user->email = $email;
        $user->name = $name;
        $user->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user->bio = "";
        $user->imageUrl = null;
        
        return $user;
    }

    public function createEntity($id = Entity::INDEX_NOT_IN_DB)
    {
        return new User($this, $id);
    }

    public function __construct()
    {
        parent::__construct("users");
        
        $this
            ->newField("email")->add()
            ->newField("name")->add()
            ->newField("passwordHash")
                ->setDbAlias("password_hash")
                ->add()
            ->newField("bio")->add()
            ->newField("imageUrl")
                ->setDbAlias("image_url")
                ->add()
            ->newAssociation("followers", UserDef::class)
                ->setIsComposition(false)
                ->setLinkTable("followers")
                ->setSourceIdField("followed_id")
                ->setTargetIdField("follower_id")
                ->setReadonly(true)
                ->add()
            ->newAssociation("following", UserDef::class)
                ->setIsComposition(false)
                ->setLinkTable("followers")
                ->setSourceIdField("follower_id")
                ->setTargetIdField("followed_id")
                ->add();
    }

    public function findByEmail($email)
    {
        $users = $this->query([
            "filter" => "email = :email",
            "params" => [":email" => $email]
        ]);
        
        return count($users) !== 0 ? $users[0] : null;
    }
    
}

