<?php

error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/../compatibility.php';

Yii::setAlias('@tests', __DIR__);
Yii::setAlias('@alexantr/elfinder', dirname(__DIR__) . '/src');
