<?php

namespace tests;

use Yii;

class ClientBaseActionTest extends TestCase
{
    protected function setUp()
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

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyConnectorRoute()
    {
        Yii::$app->runAction('elfinder/invalid');
    }

    public function testRunningWithFilterAndResizableParam()
    {
        $_GET['filter'] = ['image', 'text'];

        $out = Yii::$app->runAction('elfinder/input');

        $expected = '"onlyMimes":["image","text"],"resizable":false,';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
