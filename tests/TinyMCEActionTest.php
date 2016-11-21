<?php

namespace tests;

use Yii;

class TinyMCEActionTest extends TestCase
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

    public function testRunning()
    {
        $out = Yii::$app->runAction('elfinder/tinymce');

        $expected = '"getFileCallback":function (file) {' . "\n" .
            '    parent.tinymce.activeEditor.windowManager.getParams().oninsert(file);' . "\n" .
            '    parent.tinymce.activeEditor.windowManager.close();' . "\n" .
            '},';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
