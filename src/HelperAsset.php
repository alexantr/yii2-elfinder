<?php

namespace alexantr\elfinder;

use yii\web\AssetBundle;

class HelperAsset extends AssetBundle
{
    public $sourcePath = '@alexantr/elfinder/assets';
    public $js = [
        'helper.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
