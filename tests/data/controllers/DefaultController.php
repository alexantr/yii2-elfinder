<?php

namespace tests\data\controllers;

use alexantr\elfinder\CKEditorAction;
use alexantr\elfinder\ClientBaseAction;
use alexantr\elfinder\ConnectorAction;
use alexantr\elfinder\InputFileAction;
use alexantr\elfinder\TinyMCEAction;
use yii\web\Controller;

class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::className(),
                'options' => [],
            ],
            'input' => [
                'class' => InputFileAction::className(),
                'connectorRoute' => 'connector',
            ],
            'inputname' => [
                'class' => InputFileAction::className(),
                'connectorRoute' => 'connector',
                'nameOnly' => true,
            ],
            'ckeditor' => [
                'class' => CKEditorAction::className(),
                'connectorRoute' => 'connector',
            ],
            'tinymce' => [
                'class' => TinyMCEAction::className(),
                'connectorRoute' => 'connector',
            ],
            'invalid' => [
                'class' => ClientBaseAction::className(),
            ],
        ];
    }
}
