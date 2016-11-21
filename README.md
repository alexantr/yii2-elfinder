# elFinder file manager for Yii 2

This extension integrates an [elFinder 2.1](http://studio-42.github.io/elFinder/) file manager into
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

### Configure actions

For using elFinder you must create and configure controller. Full example with actions for elFinder's connector,
`InputFile` widget, CKEditor `filebrowser*` and TinyMCE `file_picker_callback`:

```php
<?php
namespace app\controllers;

use alexantr\elfinder\CKEditorAction;
use alexantr\elfinder\ConnectorAction;
use alexantr\elfinder\InputFileAction;
use alexantr\elfinder\TinyMCEAction;
use Yii;
use yii\web\Controller;

class ElfinderController extends Controller
{
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
            'tinymce' => [
                'class' => TinyMCEAction::className(),
                'connectorRoute' => 'connector',
            ],
        ];
    }
}
```

Reed [elFinder docs](https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1) to configure
connector options.

### InputFile widget

Example of `InputFile` widget with enabled mime filter and preview:

```php
<?= alexantr\elfinder\InputFile::widget([
    'name' => 'attributeName',
    'clientRoute' => 'elfinder/input',
    'filter' => ['image'],
    'preview' => function ($value) {
        return yii\helpers\Html::img($value, ['width' => 200]);
    },
]) ?>
```

*Note 1:* Filter param is using to display only certain files based on their mime type. Check `onlyMimes` option
in [elFinder docs](https://github.com/Studio-42/elFinder/wiki/Client-configuration-options-2.1#onlyMimes).

*Note 2:* Preview displays only predefined (saved earlier) input value and not updating on the fly after new selection.

Using textarea instead text input (can be useful with enabled multiple selection):

```php
<?= alexantr\elfinder\InputFile::widget([
    'name' => 'attributeName',
    'clientRoute' => 'elfinder/input',
    'textarea' => true,
]) ?>
```

Enable multiple selection to select more then one file in one input:

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

For using elFinder with CKEditor widget (like [this one](https://github.com/alexantr/yii2-ckeditor)) you need to
specify params `filebrowserBrowseUrl` and (or) `filebrowserImageBrowseUrl`:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'clientOptions' => [
        // ...
        'filebrowserBrowseUrl' => yii\helpers\Url::to(['elfinder/ckeditor']),
        'filebrowserImageBrowseUrl' => yii\helpers\Url::to(['elfinder/ckeditor', 'filter' => 'image']),
    ],
]) ?>
```

*Note:* For `filebrowserImageBrowseUrl` we use filter query param to display only images.

### Integration with TinyMCE 4

For using elFinder with TinyMCE 4 widget (like [this one](https://github.com/2amigos/yii2-tinymce-widget)) you need to
specify param `file_picker_callback`:

```php
<?= alexantr\ckeditor\CKEditor::widget([
    'name' => 'attributeName',
    'clientOptions' => [
        // ...
        'file_picker_callback' => alexantr\elfinder\TinyMCE::getFilePickerCallback(['elfinder/tinymce']),
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
// Add this script if you are using Bootstrap 3
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

## Links

* [elFinder Wiki](https://github.com/Studio-42/elFinder/wiki)
* [CKEditor widget](https://github.com/alexantr/yii2-ckeditor) by [alexantr](https://github.com/alexantr)
* [CKEditor widget](https://github.com/2amigos/yii2-ckeditor-widget) by [2amigos](https://github.com/2amigos)
* [TinyMCE widget](https://github.com/2amigos/yii2-tinymce-widget) by [2amigos](https://github.com/2amigos)
* [Yii framework site](http://www.yiiframework.com)
