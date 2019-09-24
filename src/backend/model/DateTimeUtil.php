<?php
namespace tbollmeier\realworld\backend\model;

class DateTimeUtil
{
    public static function dateTimeToDb(\DateTime $dateTime)
    {
        return $dateTime->format("Y-m-d H:i:s");
    }
    
    public static function dateTimeFromDb(string $dateTimeStr)
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $dateTimeStr);
    }
}

