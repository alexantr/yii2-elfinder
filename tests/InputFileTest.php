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
            '<input type="text" id="post-image" class="form-control yii2-elfinder-input" name="Post[image]" value="test-image.jpg">' .
            '<span class="input-group-btn">' .
            '<button type="button" id="post-image_button" class="btn btn-default yii2-elfinder-select-button">Select</button>' .
            '</span></div><div id="post-image_preview" class="help-block yii2-elfinder-input-preview"><p>test-image.jpg</p></div>';

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
        $expected = '<textarea id="post-image" class="form-control yii2-elfinder-input" name="Post[image]" rows="5">test-image.jpg</textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="post-image_button" class="btn btn-default yii2-elfinder-select-button">Select</button>' .
            '</div><div id="post-image_preview" class="help-block yii2-elfinder-input-preview"><p>test-image.jpg</p></div>';

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
            '<input type="text" id="test" class="form-control yii2-elfinder-input" name="test-image-name" value="test-image.jpg">' .
            '<span class="input-group-btn">' .
            '<button type="button" id="test_button" class="btn btn-default yii2-elfinder-select-button">Select</button>' .
            '</span></div><div id="test_preview" class="help-block yii2-elfinder-input-preview"><p>test-image.jpg</p></div>';

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
            'textareaRows' => 3,
            'preview' => function ($value) {
                return '<p>' . $value . '</p>';
            },
        ]);
        $expected = '<textarea id="test" class="form-control yii2-elfinder-input" name="test-image-name" rows="3">test-image.jpg</textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="test_button" class="btn btn-default yii2-elfinder-select-button">Select</button>' .
            '</div><div id="test_preview" class="help-block yii2-elfinder-input-preview"><p>test-image.jpg</p></div>';

        $this->assertEqualsWithoutLE($expected, $out);
    }

    public function testTextareaRowsParam()
    {
        $view = $this->mockView();
        $out = InputFile::widget([
            'view' => $view,
            'id' => 'test',
            'name' => 'test-image-name',
            'clientRoute' => '/elfinder/index',
            'textarea' => true,
            'textareaRows' => 7,
        ]);
        $expected = '<textarea id="test" class="form-control yii2-elfinder-input" name="test-image-name" rows="7"></textarea>' .
            '<div class="help-block">' .
            '<button type="button" id="test_button" class="btn btn-default yii2-elfinder-select-button">Select</button>' .
            '</div><div id="test_preview" class="help-block yii2-elfinder-input-preview"></div>';

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

        $expected = "alexantr.elFinder.registerSelectButton('post-image_button', '/index.php?r=elfinder%2Findex&id=post-image&filter%5B0%5D=image');";
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

        $expected = "alexantr.elFinder.registerSelectButton('post-image_button', '/index.php?r=elfinder%2Findex&id=post-image&multiple=1');";
        $this->assertContains($expected, $out);
    }
}
