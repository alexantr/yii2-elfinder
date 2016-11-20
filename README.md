# elFinder file manager for Yii 2

This extension integrates an [elFinder](http://studio-42.github.io/elFinder/) file manager into
[Yii framework 2.0](http://www.yiiframework.com).

[![Latest Stable Version](https://poser.pugx.org/alexantr/yii2-elfinder/v/stable)](https://packagist.org/packages/alexantr/yii2-elfinder)
[![Total Downloads](https://poser.pugx.org/alexantr/yii2-elfinder/downloads)](https://packagist.org/packages/alexantr/yii2-elfinder)
[![License](https://poser.pugx.org/alexantr/yii2-elfinder/license)](https://packagist.org/packages/alexantr/yii2-elfinder)
[![Build Status](https://travis-ci.org/alexantr/yii2-elfinder.svg?branch=master)](https://travis-ci.org/alexantr/yii2-elfinder)

## Installation

Install extension through [composer](http://getcomposer.org/):

```
composer require alexantr/yii2-elfinder
```

## Usage

### Configure connectors

For using elFinder you must create and configure controller.
See full example with actions for elFinder's connector, InputFile widget and CKEditor file browser:

```php
<?php
namespace app\controllers;

use alexantr\elfinder\CKEditorAction;
use alexantr\elfinder\ConnectorAction;
use alexantr\elfinder\InputFileAction;
use Yii;
use yii\web\Controller;

class ElfinderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::className(),
                'options' => [
                    'roots' => [
                        [
                            'driver' => 'LocalFileSystem',
                            'path' => Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads',
                            'URL' => Yii::getAlias('@web') . '/uploads/',
                            'mimeDetect' => 'internal',
                            'imgLib' => 'gd',
                            'accessControl' => function ($attr, $path) {
                                // hide files/folders which begins with dot
                                return (strpos(basename($path), '.') === 0) ?
                                    !($attr == 'read' || $attr == 'write') :
                                    null;
                            },
                        ],
                    ],
                ],
            ],
            'input' => [
                'class' => InputFileAction::className(),
                'connectorRoute' => 'connector',
            ],
            'ckeditor' => [
                'class' => CKEditorAction::className(),
                'connectorRoute' => 'connector',
            ],
        ];
    }
}
```

### InputFile widget

InputFile widget with preview:

```php
<?= alexantr\elfinder\InputFile::widget([
    'name' => 'attributeName',
    'clientRoute' => 'elfinder/input',
    'filter' => ['image'], // enables elFinder's onlyMimes option
    'preview' => function ($value) {
        return yii\helpers\Html::img($value, ['width' => 200]);
    },
]) ?>
```

*Note:* Preview shows only predefined value and not updating on the fly after new select.

Using textarea instead text input (can be useful with enabled multiple selection):

```php
<?= alexantr\elfinder\InputFile::widget([
    'name' => 'attributeName',
    'clientRoute' => 'elfinder/input',
    'textarea' => true,
]) ?>
```

Enable multiple selection:

```php
<?= alexantr\elfinder\InputFile::widget([
    'name' => 'attributeName',
    'clientRoute' => 'elfinder/input',
    'multiple' => true,
]) ?>
```

Default paths separator for text input is comma and newline character for textarea.
You can change them in `InputFileAction` configuration:

```php
class ElfinderController extends Controller
{
    public function actions()
    {
        return [
            // ...
            'input' => [
                'class' => InputFileAction::className(),
                'connectorRoute' => 'connector',
                'separator' => ',',
                'textareaSeparator' => '\n', // newline character in javascript
            ],
            // ...
        ];
    }
}
```

### Integration with CKEditor

Set params for [CKEditor widget](https://github.com/alexantr/yii2-ckeditor):

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'clientOptions' => [
        'filebrowserBrowseUrl' => yii\helpers\Url::to(['elfinder/ckeditor']),
        'filebrowserImageBrowseUrl' => yii\helpers\Url::to(['elfinder/ckeditor', 'filter' => 'image']),
    ],
]) ?>
```

### Standalone file manager

Create action in any controller:

```php
class DefaultController extends Controller
{
    public function actionManager()
    {
        return $this->render('manager', [
            'connectorRoute' => 'connector',
        ]);
    }
}
```

and view file for it:

```php
<?php
/* @var $this \yii\web\View */
/* @var $connectorRoute string */

// Conflict between bootstrap-button.js and jQuery UI
// https://github.com/twbs/bootstrap/issues/6094
$this->registerJs('jQuery.fn.btn = jQuery.fn.button.noConflict();');

$this->title = 'File Manager';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= alexantr\elfinder\ElFinder::widget([
    'connectorRoute' => $connectorRoute,
    'settings' => [
        'height' => 640,
    ],
]) ?>
```
