<?php
namespace tbollmeier\realworld\backend\model;

class Model
{
    private static $userDef = null;
    private static $articleDef = null;
    private static $tagDef = null;
    
    public static function getUserDef() 
    {
        if (self::$userDef == null) {
            self::$userDef = new UserDef();
        }
        
        return self::$userDef;
    }
    
    public static function getArticleDef()
    {
        if (self::$articleDef == null) {
            self::$articleDef = new ArticleDef();
        }
        
        return self::$articleDef;
    }
    
    public static function getTagDef()
    {
        if (self::$tagDef == null) {
            self::$tagDef = new TagDef();
        }
        
        return self::$tagDef;
    }
    
}

