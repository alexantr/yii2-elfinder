<?php

namespace alexantr\elfinder;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class ElFinder
 * @package alexantr\elfinder
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
     * @inheritdoc
     */
    public function init()
    {
        if ($this->connectorRoute === null) {
            throw new InvalidConfigException('Connector route must be specified.');
        }

        $this->settings['url'] = Url::toRoute($this->connectorRoute);

        if (!isset($this->settings['lang'])) {
            $this->settings['lang'] = $this->getLangFromApp();
        }

        $this->settings['customData'] = [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->getId();

        $view = $this->getView();
        $bundle = ElFinderAsset::register($view);

        if (isset($this->settings['lang']) && $this->settings['lang'] !== false) {
            if (is_file($bundle->basePath . '/js/i18n/elfinder.' . $this->settings['lang'] . '.js')) {
                $view->registerJsFile($bundle->baseUrl . '/js/i18n/elfinder.' . $this->settings['lang'] . '.js', [
                    'depends' => [ElFinderAsset::className()],
                ]);
            }
        }

        $this->settings['soundPath'] = $bundle->baseUrl . '/sounds';

        if (!isset($this->settings['height'])) {
            $this->settings['height'] = new JsExpression('jQuery(window).height() - 2');
        }

        $settings = Json::encode($this->settings);

        $view->registerJs("jQuery('#$id').elfinder($settings);");

        // force 'content-box' for 'box-sizing'
        $css = <<<CSSEXP
#{$id}, #{$id} *,
.elfinder-contextmenu, .elfinder-contextmenu *,
.elfinder-quicklook, .elfinder-quicklook * {
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
}
CSSEXP;
        $view->registerCss($css);

        echo "<div id=\"{$id}\"></div>";
    }

    /**
     * Get client language from current Yii app language
     * Based on https://github.com/Studio-42/elFinder/wiki/Automatically-load-language
     * @return string
     */
    protected function getLangFromApp()
    {
        $full_lang = mb_strtolower(Yii::$app->language);
        $lang = substr($full_lang, 0, 2);
        if ($lang == 'ja') {
            $lang = 'jp';
        } elseif ($lang == 'pt') {
            $lang = 'pt_BR';
        } elseif ($lang == 'ug') {
            $lang = 'ug_CN';
        } elseif ($lang == 'zh') {
            $lang = ($full_lang == 'zh-tw' || $full_lang == 'zh_tw') ? 'zh_TW' : 'zh_CN';
        } elseif ($lang == 'be') {
            // for belarusian use russian instead english
            $lang = 'ru';
        }
        return $lang;
    }
}
