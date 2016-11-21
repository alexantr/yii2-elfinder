<?php

namespace tests;

use alexantr\elfinder\TinyMCE;

class TinyMCETest extends TestCase
{
    public function testTinyMCEFilePickerCallback()
    {
        $out = TinyMCE::getFilePickerCallback(['/elfinder/tinymce'], 1000, 600);

        $expected = 'function (callback, value, meta) {
    tinymce.activeEditor.windowManager.open({
        file: "/index.php?r=elfinder%2Ftinymce",
        title: "elFinder 2.1",
        width: 1000,
        height: 600
    }, {
        oninsert: function (file, fm) {
            var url = file.url, reg = /\/[^/]+?\/\.\.\//;
            while(url.match(reg)) {
                url = url.replace(reg, \'/\');
            }
            if (meta.filetype == \'file\') {
                callback(url, {title: file.name});
            }
            if (meta.filetype == \'image\') {
                callback(url, {alt: file.name});
            }
            if (meta.filetype == \'media\') {
                callback(url);
            }
        }
    });
    return false;
}';

        $this->assertInstanceOf('yii\web\JsExpression', $out);
        $this->assertEqualsWithoutLE($expected, "$out");
    }
}
