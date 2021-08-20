<?php

namespace tests;

use alexantr\elfinder\ElFinder;
use alexantr\elfinder\ElFinderAsset;
use Yii;
use yii\base\InvalidConfigException;

class ElFinderTest extends TestCase
{
    public function testEmptyConnectorRoute(): void
    {
        $this->expectException(InvalidConfigException::class);
        $view = $this->mockView();

        ElFinder::widget([
            'view' => $view,
            'id' => 'test',
        ]);
    }

    public function testRender(): void
    {
        $view = $this->mockView();

        $out = ElFinder::widget([
            'view' => $view,
            'id' => 'test',
            'connectorRoute' => '/elfinder/connector',
        ]);
        $expected = '<div id="test"></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testAssetsAndLangParamFromApp(): void
    {
        Yii::$app->language = 'be-BY';
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [],
        ]);

        $this->assertStringContainsString('"customData":{"_csrf":"', $out);

        $this->assertStringContainsString('/js/i18n/elfinder.ru.js', $out);
        $this->assertStringContainsString('"lang":"ru"', $out); // has lang param
    }

    public function testLangParamJp(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'jp',
            ],
        ]);

        $this->assertStringContainsString('/js/i18n/elfinder.ja.js', $out);
        $this->assertStringContainsString('"lang":"ja"', $out);
    }

    public function testLangParamPt(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'pt',
            ],
        ]);

        $this->assertStringContainsString('/js/i18n/elfinder.pt_BR.js', $out);
        $this->assertStringContainsString('"lang":"pt_BR"', $out);
    }

    public function testLangParamUg(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'ug',
            ],
        ]);

        $this->assertStringContainsString('/js/i18n/elfinder.ug_CN.js', $out);
        $this->assertStringContainsString('"lang":"ug_CN"', $out);
    }

    public function testLangParamZh(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'zh',
            ],
        ]);

        $this->assertStringContainsString('/js/i18n/elfinder.zh_CN.js', $out);
        $this->assertStringContainsString('"lang":"zh_CN"', $out);
    }

    public function testLangParamZhTw(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'zh-TW',
            ],
        ]);

        $this->assertStringContainsString('/js/i18n/elfinder.zh_TW.js', $out);
        $this->assertStringContainsString('"lang":"zh_TW"', $out);
    }

    public function testDisabledLangParam(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => false,
            ],
        ]);

        $this->assertStringNotContainsString('/js/i18n/elfinder', $out); // no lang file
        $this->assertStringNotContainsString('"lang":', $out); // no lang param
    }

    public function testUnknownLangParam(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'xx',
            ],
        ]);

        $this->assertStringNotContainsString('/js/i18n/elfinder', $out); // no lang file
        $this->assertStringNotContainsString('"lang":', $out); // no lang param
    }

    public function testDefaultHeightParam(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [],
        ]);

        $this->assertRegExp('/(' . preg_quote('"height":"100%"') . ')|(' . preg_quote('jQuery(window).height() - 2') . ')/', $out);
    }

    public function testDisabledHeightParam(): void
    {
        $view = $this->mockView();

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'height' => false,
            ],
        ]);

        $this->assertStringNotContainsString('"height":', $out);
    }

    public function testButtonNoConflictEnabled(): void
    {
        $view = $this->mockView();

        $content = ElFinder::widget([
            'view' => $view,
            'id' => 'test',
            'connectorRoute' => '/elfinder/connector',
            'buttonNoConflict' => true,
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $content,
        ]);

        $this->assertStringContainsString('if (jQuery.fn.button.noConflict) { jQuery.fn.btn = jQuery.fn.button.noConflict(); }', $out);
    }

    public function testPaths(): void
    {
        $view = $this->mockView();

        $bundle = ElFinderAsset::register($view);

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [],
        ]);

        $this->assertStringContainsString('"baseUrl":"' . $bundle->baseUrl, $out);
        $this->assertStringContainsString('"soundPath":"' . $bundle->baseUrl . '/sounds"', $out);
    }
}
