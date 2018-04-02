<?php

namespace alexantr\elfinder;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * ElFinder Widget
 */
class ElFinder extends Widget
{
    /**
     * @var string A route to connector action
     */
    public $connectorRoute;
    /**
     * @var array Client settings
     * @see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
     */
    public $settings = [];
    /**
     * @var bool Resolves conflict between Bootstrab 3 btn and jQuery UI btn. Enable if using widget on page with BS3
     * @see https://github.com/twbs/bootstrap/issues/6094
     */
    public $buttonNoConflict = false;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->connectorRoute === null) {
            throw new InvalidConfigException('Connector route must be specified.');
        }

        $this->settings['url'] = Url::toRoute($this->connectorRoute);

        if (!isset($this->settings['lang'])) {
            $this->settings['lang'] = Yii::$app->language;
        } elseif ($this->settings['lang'] === false) {
            unset($this->settings['lang']);
        }

        $this->settings['customData'] = [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $id = $this->getId();
        $view = $this->getView();

        if ($this->buttonNoConflict) {
            $view->registerJs('if (jQuery.fn.button.noConflict) { jQuery.fn.btn = jQuery.fn.button.noConflict(); }');
        }

        $bundle = ElFinderAsset::register($view);

        if (isset($this->settings['lang'])) {
            $this->settings['lang'] = $this->checkLanguage($this->settings['lang']);
            if (is_file($bundle->basePath . '/js/i18n/elfinder.' . $this->settings['lang'] . '.js')) {
                $view->registerJsFile($bundle->baseUrl . '/js/i18n/elfinder.' . $this->settings['lang'] . '.js', [
                    'depends' => [ElFinderAsset::className()],
                ]);
            } else {
                unset($this->settings['lang']);
            }
        }

        $this->settings['baseUrl'] = $bundle->baseUrl . '/';
        $this->settings['soundPath'] = $bundle->baseUrl . '/sounds';

        if (!isset($this->settings['height'])) {
            $this->settings['height'] = '100%';
        } elseif ($this->settings['height'] === false) {
            unset($this->settings['height']);
        }

        $settings = Json::encode($this->settings);

        $view->registerJs("jQuery('#$id').elfinder($settings);");

        return "<div id=\"$id\"></div>";
    }

    /**
     * Set elFinder's correct "lang" param
     * @param string $language
     * @return string
     */
    protected function checkLanguage($language)
    {
        $full_language = mb_strtolower($language);
        $lang = substr($full_language, 0, 2);
        if ($lang == 'jp') {
            $lang = 'ja';
        } elseif ($lang == 'pt') {
            $lang = 'pt_BR';
        } elseif ($lang == 'ug') {
            $lang = 'ug_CN';
        } elseif ($lang == 'zh') {
            $lang = ($full_language == 'zh-tw' || $full_language == 'zh_tw') ? 'zh_TW' : 'zh_CN';
        } elseif ($lang == 'be') {
            // for belarusian use russian instead english
            $lang = 'ru';
        }
        return $lang;
    }
}
