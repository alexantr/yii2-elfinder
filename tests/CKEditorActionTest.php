<?php

namespace tests;

use Yii;

class CKEditorActionTest extends TestCase
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
        $out = Yii::$app->runAction('elfinder/ckeditor');

        $expected = '"getFileCallback":function (file) {' . "\n" .
            '    var reParam = new RegExp(\'(?:[\?&]|&amp;)CKEditorFuncNum=([^&]+)\', \'i\');' . "\n" .
            '    var match = window.location.search.match(reParam);' . "\n" .
            '    var funcNum = (match && match.length > 1) ? match[1] : \'\';' . "\n" .
            '    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);' . "\n" .
            '    window.close();' . "\n" .
            '},';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
