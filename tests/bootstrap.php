<?php

// ensure we get report on all possible php errors
error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// backward compatibility for php 5.5 and low
if (!class_exists('PHPUnit_Framework_TestCase') && class_exists('PHPUnit\Framework\TestCase')) {
    abstract class PHPUnit_Framework_TestCase extends PHPUnit\Framework\TestCase
    {
    }
}

Yii::setAlias('@tests', __DIR__);
Yii::setAlias('@alexantr/elfinder', dirname(__DIR__) . '/src');
