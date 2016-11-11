<?php

namespace tests;

use alexantr\elfinder\InputFile;
use tests\data\models\Post;

class InputFileTest extends TestCase
{
    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testEmptyClientRoute()
    {
        $view = $this->mockView();
        $model = new Post();
        InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
        ]);
    }

    public function testRenderWithModel()
    {
        $view = $this->mockView();
        $model = new Post();
        $out = InputFile::widget([
            'view' => $view,
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
        $view = $this->mockView();
        $out = InputFile::widget([
            'view' => $view,
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
        $view = $this->mockView();

        $model = new Post();
        $widget = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'filter' => ['image'],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
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

    public function testMultipleTextareaWithModel()
    {
        $view = $this->mockView();

        $model = new Post();
        $widget = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'multiple' => true,
            'useTextarea' => true,
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = '<textarea id="post-image" class="form-control elfinder-form-control" name="Post[image]" rows="5"></textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="post-image_button" class="btn btn-default elfinder-btn">Select</button>' .
            '</div>';
        $this->assertContains($expected, $out);
    }

    public function testMultipleTextareaWithNameAndValue()
    {
        $view = $this->mockView();

        $widget = InputFile::widget([
            'view' => $view,
            'id' => 'test',
            'name' => 'test-image-name',
            'clientRoute' => '/elfinder/index',
            'multiple' => true,
            'useTextarea' => true,
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = '<textarea id="test" class="form-control elfinder-form-control" name="test-image-name" rows="5"></textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="test_button" class="btn btn-default elfinder-btn">Select</button>' .
            '</div>';
        $this->assertContains($expected, $out);
    }

    public function testPreviewWithModel()
    {
        $view = $this->mockView();

        $model = new Post();
        $model->image = 'test-image.jpg';

        $widget = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = '<div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';
        $this->assertContains($expected, $out);
    }

    public function testPreviewWithNameAndValue()
    {
        $view = $this->mockView();

        $widget = InputFile::widget([
            'view' => $view,
            'name' => 'test-image-name',
            'value' => 'test-image.jpg',
            'clientRoute' => '/elfinder/index',
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = '<div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';
        $this->assertContains($expected, $out);
    }
}
