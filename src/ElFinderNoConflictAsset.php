<?php

namespace alexantr\elfinder;

/**
 * Asset to fix conflict between bootstrap-button.js and jQuery UI
 * https://github.com/twbs/bootstrap/issues/6094
 */
class ElFinderNoConflictAsset extends \yii\web\AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $js = ['no-conflict.js'];

    public $depends = ['yii\bootstrap\BootstrapPluginAsset', 'yii\jui\JuiAsset'];
}
