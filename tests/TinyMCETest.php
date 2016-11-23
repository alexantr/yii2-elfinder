<?php

namespace tests;

use alexantr\elfinder\TinyMCE;

class TinyMCETest extends TestCase
{
    public function testTinyMCEFilePickerCallback()
    {
        $out = TinyMCE::getFilePickerCallback(['/elfinder/tinymce'], ['width' => 1000, 'height' => 600]);

        $expected = 'function (callback, value, meta) {
    tinymce.activeEditor.windowManager.open({"title":"elFinder","width":1000,"height":600,"file":"/index.php?r=elfinder%2Ftinymce"}, {
        oninsert: function (file) {
            var url = file.url, reg = /\/[^/]+?\/\.\.\//;
            while (url.match(reg)) { url = url.replace(reg, \'/\'); }
            callback(url);
        }
    });
    return false;
}';

        $this->assertInstanceOf('yii\web\JsExpression', $out);
        $this->assertEqualsWithoutLE($expected, "$out");
    }
}
