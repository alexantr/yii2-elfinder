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

    public function testInputWithModelAndPreview()
    {
        $view = $this->mockView();
        $model = new Post();
        $model->image = 'test-image.jpg';
        $out = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);
        $expected = '<div class="input-group">' .
            '<input type="text" id="post-image" class="form-control" name="Post[image]" value="test-image.jpg">' .
            '<span class="input-group-btn">' .
            '<button type="button" id="post-image_button" class="btn btn-default">Select</button>' .
            '</span></div><div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testTextareaWithModelAndPreview()
    {
        $view = $this->mockView();
        $model = new Post();
        $model->image = 'test-image.jpg';
        $out = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'textarea' => true,
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);
        $expected = '<textarea id="post-image" class="form-control" name="Post[image]" rows="5">test-image.jpg</textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="post-image_button" class="btn btn-default">Select</button>' .
            '</div><div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testInputWithNameAndValueAndPreview()
    {
        $view = $this->mockView();
        $out = InputFile::widget([
            'view' => $view,
            'id' => 'test',
            'name' => 'test-image-name',
            'value' => 'test-image.jpg',
            'clientRoute' => '/elfinder/index',
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);
        $expected = '<div class="input-group">' .
            '<input type="text" id="test" class="form-control" name="test-image-name" value="test-image.jpg">' .
            '<span class="input-group-btn">' .
            '<button type="button" id="test_button" class="btn btn-default">Select</button>' .
            '</span></div><div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testTextareaWithNameAndValueAndPreview()
    {
        $view = $this->mockView();
        $out = InputFile::widget([
            'view' => $view,
            'id' => 'test',
            'name' => 'test-image-name',
            'value' => 'test-image.jpg',
            'clientRoute' => '/elfinder/index',
            'textarea' => true,
            'options' => ['class' => 'form-control', 'rows' => 3],
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);
        $expected = '<textarea id="test" class="form-control" name="test-image-name" rows="3">test-image.jpg</textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="test_button" class="btn btn-default">Select</button>' .
            '</div><div class="help-block elfinder-input-preview"><p>test-image.jpg</p></div>';

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
    var w = screen.width / 1.5, h = screen.height / 1.5;
    if (w < 900 && screen.width > 960) w = 900;
    if (h < 600 && screen.height > 660) h = 600;
    var params = 'menubar=no,toolbar=no,location=no,directories=no,status=no,fullscreen=no,width=' + w + ',height=' + h;
    var win = window.open('/index.php?r=elfinder%2Findex&id=post-image&filter%5B0%5D=image', 'elfinder_post-image', params);
    win.focus();
});";
        $this->assertContains($expected, $out);
    }

    public function testMultipleParam()
    {
        $view = $this->mockView();

        $model = new Post();
        $widget = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'multiple' => true,
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = "jQuery(document).on('click', '#post-image_button', function (e) {
    e.preventDefault();
    var w = screen.width / 1.5, h = screen.height / 1.5;
    if (w < 900 && screen.width > 960) w = 900;
    if (h < 600 && screen.height > 660) h = 600;
    var params = 'menubar=no,toolbar=no,location=no,directories=no,status=no,fullscreen=no,width=' + w + ',height=' + h;
    var win = window.open('/index.php?r=elfinder%2Findex&id=post-image&multiple=1', 'elfinder_post-image', params);
    win.focus();
});";
        $this->assertContains($expected, $out);
    }
}
