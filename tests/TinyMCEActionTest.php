<?php

namespace tests;

use Yii;

class TinyMCEActionTest extends TestCase
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

    public function testRunning(): void
    {
        $out = Yii::$app->runAction('elfinder/tinymce');

        $expected = '"getFileCallback":function (file) {' . "\n" .
            '    if (parent.tinymce.majorVersion === "4") {' . "\n" .
            '        parent.tinymce.activeEditor.windowManager.getParams().oninsert(file);' . "\n" .
            '        parent.tinymce.activeEditor.windowManager.close();' . "\n" .
            '    } else {' . "\n" .
            '        parent.postMessage({mceAction: "customAction", file: file}, "*");' . "\n" .
            '    }' . "\n" .
            '},';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
