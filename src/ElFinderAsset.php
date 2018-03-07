<?php

namespace alexantr\elfinder;

use yii\web\AssetBundle;

class ElFinderAsset extends AssetBundle
{
    public $sourcePath = '@vendor/studio-42/elfinder';
    public $css = [
        'css/elfinder.min.css',
        'css/theme.css',
    ];
    public $js = [
        'js/elfinder.min.js',
    ];
    public $publishOptions = [
        'except' => [
            'files/',
            'php/',
            '*.html',
            '*.php',
            '*.md',
            '*.json',
            'Changelog',
        ],
        'caseSensitive' => false,
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
