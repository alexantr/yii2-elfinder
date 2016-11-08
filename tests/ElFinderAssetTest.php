<?php

namespace tests;

use alexantr\elfinder\ElFinderAsset;
use Yii;
use yii\web\AssetBundle;

class ElFinderAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = Yii::$app->view;

        $this->assertEmpty($view->assetBundles);
        ElFinderAsset::register($view);
        $this->assertEquals(3, count($view->assetBundles));

        $this->assertArrayHasKey('yii\\jui\\JuiAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\elfinder\\ElFinderAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php', ['content' => '']);

        $this->assertContains('/css/elfinder.min.css', $out);
        $this->assertContains('/css/theme.css', $out);
        $this->assertContains('/js/elfinder.min.js', $out);
    }
}
