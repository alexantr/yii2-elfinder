<?php

namespace tests;

use alexantr\elfinder\ElFinderAsset;
use yii\web\AssetBundle;

class ElFinderAssetTest extends TestCase
{
    public function testRegister(): void
    {
        $view = $this->mockView();

        $this->assertEmpty($view->assetBundles);

        $bundle = ElFinderAsset::register($view);

        // JqueryAsset, JuiAsset, ElFinderAsset
        $this->assertCount(3, $view->assetBundles);

        $this->assertArrayHasKey('alexantr\\elfinder\\ElFinderAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\elfinder\\ElFinderAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php');

        $this->assertStringContainsString('/css/elfinder.min.css', $out);
        $this->assertStringContainsString('/css/theme.css', $out);
        $this->assertStringContainsString('/js/elfinder.min.js', $out);

        $this->assertFileExists($bundle->basePath . '/css/elfinder.min.css', $out);
        $this->assertFileExists($bundle->basePath . '/js/elfinder.min.js', $out);

        // check if help files copied
        $this->assertFileExists($bundle->basePath . '/js/i18n/help/en.html.js', $out);

        $this->assertFileNotExists($bundle->basePath . '/files');
        $this->assertFileNotExists($bundle->basePath . '/php');
        $this->assertFileNotExists($bundle->basePath . '/elfinder.html');
        $this->assertFileNotExists($bundle->basePath . '/elfinder.legacy.html');
        $this->assertFileNotExists($bundle->basePath . '/Changelog');
    }
}
