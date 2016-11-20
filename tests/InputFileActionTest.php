<?php

namespace tests;

use Yii;

class InputFileActionTest extends TestCase
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
        $_GET['id'] = 'test';

        $out = Yii::$app->runAction('elfinder/input');

        $expected = '"getFileCallback":function (file) {' . "\n" .
            '    window.opener.document.getElementById("test").value = file.url;' . "\n" .
            '    window.close();' . "\n" .
            '},';

        $this->assertContainsWithoutLE($expected, $out);
    }

    public function testRunningWithMultipleParam()
    {
        $_GET['id'] = 'test';
        $_GET['multiple'] = '1';

        $out = Yii::$app->runAction('elfinder/input');

        $expected = '"getFileCallback":function (files) {' . "\n" .
            '    var urls = [], separator = ",";' . "\n" .
            '    for (var i in files) {' . "\n" .
            '        urls.push(files[i].url);' . "\n" .
            '    }' . "\n" .
            '    var el = window.opener.document.getElementById("test");' . "\n" .
            '    if (el.tagName.toLowerCase() == "textarea") separator = "\n";' . "\n" .
            '    if (el.value) {' . "\n" .
            '        el.value = el.value + separator + urls.join(separator);' . "\n" .
            '    } else {' . "\n" .
            '        el.value = urls.join(separator);' . "\n" .
            '    }' . "\n" .
            '    window.close();' . "\n" .
            '},';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
