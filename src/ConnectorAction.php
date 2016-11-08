<?php

namespace alexantr\elfinder;

use yii\base\Action;

/**
 * Class ConnectorAction
 * @package alexantr\elfinder
 */
class ConnectorAction extends Action
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        (new \elFinderConnector(new \elFinder($this->options)))->run();
    }
}
