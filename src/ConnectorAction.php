<?php

namespace alexantr\elfinder;

use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * Connector action
 */
class ConnectorAction extends Action
{
    /**
     * @var array elFinder connector options
     */
    public $options = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        (new \elFinderConnector(new \elFinder($this->options)))->run();
    }
}
