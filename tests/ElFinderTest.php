<?php

namespace tests;

use alexantr\elfinder\ElFinder;
use Yii;

class ElFinderTest extends TestCase
{
    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyConnectorRoute()
    {
        ElFinder::widget();
    }

    public function testRender()
    {
        $out = ElFinder::widget([
            'id' => 'test',
            'connectorRoute' => '/elfinder/connector',
        ]);
        $expected = '<div id="test"></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testAssetsAndLangParamFromApp()
    {
        Yii::$app->language = 'be-BY';
        $view = Yii::$app->view;

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [],
        ]);

        $this->assertContains('"customData":{"_csrf":"', $out);

        $this->assertContains('/js/i18n/elfinder.ru.js', $out);
        $this->assertContains('"lang":"ru"', $out); // has lang param
    }

    public function testSpecificLangParams()
    {
        $view = Yii::$app->view;

        // ja

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'ja',
            ],
        ]);

        $this->assertContains('/js/i18n/elfinder.jp.js', $out);
        $this->assertContains('"lang":"jp"', $out);

        // pt

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'pt',
            ],
        ]);

        $this->assertContains('/js/i18n/elfinder.pt_BR.js', $out);
        $this->assertContains('"lang":"pt_BR"', $out);

        // ug

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'ug',
            ],
        ]);

        $this->assertContains('/js/i18n/elfinder.ug_CN.js', $out);
        $this->assertContains('"lang":"ug_CN"', $out);

        // zh

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'zh',
            ],
        ]);

        $this->assertContains('/js/i18n/elfinder.zh_CN.js', $out);
        $this->assertContains('"lang":"zh_CN"', $out);

        // zh-TW

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => 'zh-TW',
            ],
        ]);

        $this->assertContains('/js/i18n/elfinder.zh_TW.js', $out);
        $this->assertContains('"lang":"zh_TW"', $out);
    }

    public function testDisabledLangParam()
    {
        $view = Yii::$app->view;

        $out = $view->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => '/elfinder/connector',
            'settings' => [
                'lang' => false,
            ],
        ]);

        $this->assertNotContains('/js/i18n/elfinder', $out); // no lang file
        $this->assertNotContains('"lang":', $out); // no lang param
    }
}
