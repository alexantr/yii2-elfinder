<?php

namespace tests;

use alexantr\elfinder\TinyMCE;

class TinyMCETest extends TestCase
{
    public function testTinyMCEFilePickerCallback()
    {
        $view = $this->mockView();

        $out = TinyMCE::getFilePickerCallback(['/elfinder/tinymce'], ['width' => 1000, 'height' => 600], $view);

        $expected = 'alexantr.elFinder.filePickerCallback({"title":"elFinder","width":1000,"height":600,"file":"\/index.php?r=elfinder%2Ftinymce"})';

        $this->assertInstanceOf('yii\web\JsExpression', $out);
        $this->assertEqualsWithoutLE($expected, "$out");
    }
}
