<?php

namespace alexantr\elfinder;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\StringHelper;

/**
 * Client base action
 */
class ClientBaseAction extends Action
{
    /**
     * @var string A route to connector action
     */
    public $connectorRoute;
    /**
     * @var array Client settings
     */
    public $settings = [];

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->connectorRoute === null) {
            throw new InvalidConfigException('Connector route must be specified.');
        }
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        // Get 'onlyMimes' value from GET parameter
        $filter = Yii::$app->request->getQueryParam('filter');
        if (!empty($filter)) {
            $this->settings['onlyMimes'] = is_string($filter) ? StringHelper::explode($filter) : $filter;
        }

        // disable resize in popup window
        $this->settings['resizable'] = false;

        return $this->controller->renderFile('@alexantr/elfinder/views/elfinder.php', [
            'connectorRoute' => $this->connectorRoute,
            'settings' => $this->settings,
        ]);
    }
}
