<?php

namespace tests;

use alexantr\elfinder\ElFinderAsset;
use yii\web\AssetBundle;

class ElFinderAssetTest extends TestCase
{
    public function testRegister()
    {
        $view = $this->mockView();

        $this->assertEmpty($view->assetBundles);

        $bundle = ElFinderAsset::register($view);

        // JqueryAsset, JuiAsset, ElFinderAsset
        $this->assertEquals(3, count($view->assetBundles));

        $this->assertArrayHasKey('alexantr\\elfinder\\ElFinderAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\elfinder\\ElFinderAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php');

        $this->assertContains('/css/elfinder.min.css', $out);
        $this->assertContains('/css/theme.css', $out);
        $this->assertContains('/js/elfinder.min.js', $out);

        $this->assertFileNotExists($bundle->basePath . DIRECTORY_SEPARATOR . 'files');
        $this->assertFileNotExists($bundle->basePath . DIRECTORY_SEPARATOR . 'php');
        $this->assertFileNotExists($bundle->basePath . DIRECTORY_SEPARATOR . 'elfinder.html');
    }
}
