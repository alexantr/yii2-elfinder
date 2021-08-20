<?php

namespace tests;

use Yii;
use yii\base\InvalidConfigException;

class ClientBaseActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockWebApplication([
            'controllerMap' => [
                'elfinder' => 'tests\data\controllers\DefaultController',
            ],
            'components' => [
                'view' => [
                    'class' => 'yii\web\View',
                ],
                'assetManager' => [
                    'class' => 'yii\web\AssetManager',
                    'basePath' => '@tests/data/assets',
                    'baseUrl' => '/assets',
                ],
            ],
        ]);
    }

    public function testEmptyConnectorRoute(): void
    {
        $this->expectException(InvalidConfigException::class);
        Yii::$app->runAction('elfinder/invalid');
    }

    public function testRunningWithFilterAndResizableParam(): void
    {
        $_GET['filter'] = ['image', 'text'];

        $out = Yii::$app->runAction('elfinder/input');

        $expected = '"onlyMimes":["image","text"],"resizable":false,';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
