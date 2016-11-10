<?php

namespace tests\data\models;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public $image;

    public static $db;

    public static function getDb()
    {
        return self::$db;
    }
}
