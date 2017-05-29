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
            '    window.opener.jQuery("#test").val(file.url).trigger("change");' . "\n" .
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
            '    var urls = [], separator = ",", el = window.opener.jQuery("#test"), value = el.val();' . "\n" .
            '    for (var i in files) {' . "\n" .
            '        urls.push(files[i].url);' . "\n" .
            '    }' . "\n" .
            '    if (el.prop("tagName").toLowerCase() == "textarea") separator = "\n";' . "\n" .
            '    if (value) {' . "\n" .
            '        el.val(value + separator + urls.join(separator)).trigger("change");' . "\n" .
            '    } else {' . "\n" .
            '        el.val(urls.join(separator)).trigger("change");' . "\n" .
            '    }' . "\n" .
            '    window.close();' . "\n" .
            '},';

        $this->assertContainsWithoutLE($expected, $out);
    }
}
