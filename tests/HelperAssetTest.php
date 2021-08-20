<?php

namespace tests;

use alexantr\elfinder\HelperAsset;
use yii\web\AssetBundle;

class HelperAssetTest extends TestCase
{
    public function testRegister(): void
    {
        $view = $this->mockView();

        $this->assertEmpty($view->assetBundles);

        $bundle = HelperAsset::register($view);

        // JqueryAsset, HelperAsset
        $this->assertCount(2, $view->assetBundles);

        $this->assertArrayHasKey('alexantr\\elfinder\\HelperAsset', $view->assetBundles);
        $this->assertTrue($view->assetBundles['alexantr\\elfinder\\HelperAsset'] instanceof AssetBundle);

        $out = $view->renderFile('@tests/data/views/layout.php');

        $this->assertStringContainsString('/helper.js', $out);

        $this->assertFileExists($bundle->basePath . '/helper.js', $out);
    }
}
