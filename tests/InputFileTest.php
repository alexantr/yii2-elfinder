<?php

namespace tests;

use alexantr\elfinder\InputFile;
use tests\data\models\Post;
use Yii;

class InputFileTest extends TestCase
{
    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyClientRoute()
    {
        $model = new Post();
        InputFile::widget([
            'model' => $model,
            'attribute' => 'image',
        ]);
    }

    public function testRenderWithModel()
    {
        $model = new Post();
        $out = InputFile::widget([
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
        ]);
        $expected = '<div class="input-group elfinder-input-group">' .
            '<input type="text" id="post-image" class="form-control elfinder-form-control" name="Post[image]">' .
            '<span class="input-group-btn">' .
            '<button type="button" id="post-image_button" class="btn btn-default elfinder-btn">Select</button>' .
            '</span></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testRenderWithNameAndValue()
    {
        $out = InputFile::widget([
            'id' => 'test',
            'name' => 'test-image-name',
            'value' => 'test-image.jpg',
            'clientRoute' => '/elfinder/index',
        ]);
        $expected = '<div class="input-group elfinder-input-group">' .
            '<input type="text" id="test" class="form-control elfinder-form-control" name="test-image-name" value="test-image.jpg">' .
            '<span class="input-group-btn">' .
            '<button type="button" id="test_button" class="btn btn-default elfinder-btn">Select</button>' .
            '</span></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testFilterParam()
    {
        $model = new Post();
        $widget = InputFile::widget([
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'filter' => ['image'],
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = "jQuery(document).on('click', '#post-image_button', function (e) {
    e.preventDefault();
    var w = screen.width / 1.5;
    var h = screen.height / 1.5;
    if (w < 900 && screen.width > 960) w = 900;
    if (h < 600 && screen.height > 660) h = 600;
    var params = 'menubar=no,toolbar=no,location=no,directories=no,status=no,fullscreen=no,width=' + w + ',height=' + h;
    var win = window.open('/index.php?r=elfinder%2Findex&amp;id=post-image&amp;filter%5B0%5D=image', 'elfinder_post-image', params);
    win.focus();
});";
        $this->assertContains($expected, $out);
    }

    public function testPreview()
    {
        $expected = '<div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';

        // model

        $model = new Post();
        $model->image = 'test-image.jpg';

        $widget = InputFile::widget([
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $this->assertContains($expected, $out);

        // name & value

        $widget = InputFile::widget([
            'name' => 'test-image-name',
            'value' => 'test-image.jpg',
            'clientRoute' => '/elfinder/index',
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);

        $out = Yii::$app->view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $this->assertContains($expected, $out);
    }
}
