<?php

namespace tests;

use alexantr\elfinder\InputFile;
use tests\data\models\Post;
use yii\base\InvalidConfigException;

class InputFileTest extends TestCase
{
    public function testEmptyClientRoute(): void
    {
        $this->expectException(InvalidConfigException::class);
        $view = $this->mockView();
        $model = new Post();
        InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
        ]);
    }

    public function testInputWithModelAndPreview(): void
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

    public function testTextareaWithModelAndPreview(): void
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

    public function testInputWithNameAndValueAndPreview(): void
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

    public function testTextareaWithNameAndValueAndPreview(): void
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

    public function testTextareaRowsParam(): void
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

    public function testFilterParam(): void
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

        $expected = '<input type="text" id="post-image" class="form-control yii2-elfinder-input" name="Post[image]" data-filter="[&quot;image&quot;]">';
        $this->assertContainsWithoutLE($expected, $out);

        $expected = "alexantr.elFinder.registerSelectButton('post-image_button', '/index.php?r=elfinder%2Findex&id=post-image&filter%5B0%5D=image');";
        $this->assertStringContainsString($expected, $out);
    }

    public function testMultipleParam(): void
    {
        $view = $this->mockView();

        $model = new Post();
        $widget = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => '/elfinder/index',
            'multiple' => true,
            'filter' => 'image',
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = '<input type="text" id="post-image" class="form-control yii2-elfinder-input" name="Post[image]" data-filter="image" data-multiple="1">';
        $this->assertContainsWithoutLE($expected, $out);

        $expected = "alexantr.elFinder.registerSelectButton('post-image_button', '/index.php?r=elfinder%2Findex&id=post-image&filter=image&multiple=1');";
        $this->assertStringContainsString($expected, $out);
    }

    public function testClientRouteAsArray(): void
    {
        $view = $this->mockView();

        $model = new Post();
        $widget = InputFile::widget([
            'view' => $view,
            'model' => $model,
            'attribute' => 'image',
            'clientRoute' => ['/elfinder/index', 'section' => 'test'],
        ]);

        $out = $view->renderFile('@tests/data/views/layout.php', [
            'content' => $widget,
        ]);

        $expected = "alexantr.elFinder.registerSelectButton('post-image_button', '/index.php?r=elfinder%2Findex&section=test&id=post-image');";
        $this->assertStringContainsString($expected, $out);
    }
}
