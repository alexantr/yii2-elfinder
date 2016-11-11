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

Example of controller:

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
            // for input file widget
            'input' => [
                'class' => InputFileAction::className(),
                'connectorRoute' => 'connector',
            ],
            // for CKEditor widget params
            'ckeditor' => [
                'class' => CKEditorAction::className(),
                'connectorRoute' => 'connector',
            ],
        ];
    }

    /**
     * Standalone file manager
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'connectorRoute' => 'connector',
        ]);
    }
}
```

View file for standalone file manager:

```php
<?php
/* @var $this \yii\web\View */
/* @var $connectorRoute string */

// Conflict between bootstrap-button.js and jQuery UI
// https://github.com/twbs/bootstrap/issues/6094
$this->registerJs('jQuery.fn.btn = jQuery.fn.button.noConflict();');

$this->title = Yii::t('app', 'File Manager');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= alexantr\elfinder\ElFinder::widget([
    'connectorRoute' => $connectorRoute,
    'settings' => [
        'height' => 640,
    ],
]) ?>
```

Set params for [CKEditor widget](https://github.com/alexantr/yii2-ckeditor):

```php
<?= CKEditor::widget([
    'name' => 'attributeName',
    'clientOptions' => [
        'filebrowserBrowseUrl' => yii\helpers\Url::to(['elfinder/ckeditor']),
        'filebrowserImageBrowseUrl' => yii\helpers\Url::to(['elfinder/ckeditor', 'filter' => 'image']),
    ],
]) ?>
```

Input file widget:

```php
<?= $form->field($model, 'image')->widget(alexantr\elfinder\InputFile::className(), [
    'clientRoute' => 'elfinder/input',
    'filter' => ['image'],
    'buttonText' => Yii::t('app', 'Select'),
    'preview' => function ($value) {
        return yii\helpers\Html::img($value, ['width' => 200]);
    },
]) ?>
```
