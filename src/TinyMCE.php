<?php

namespace alexantr\elfinder;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * TinyMCE helper
 */
class TinyMCE
{
    /**
     * Callback for TinyMCE 4 file_picker_callback
     * @param array|string $url Url to TinyMCEAction
     * @param array $popupSettings TinyMCE popup settings
     * @param \yii\web\View|null $view
     * @return JsExpression
     */
    public static function getFilePickerCallback($url, $popupSettings = [], $view = null)
    {
        $default = [
            'title' => 'elFinder',
            'width' => 900,
            'height' => 500,
        ];

        $settings = array_merge($default, $popupSettings);
        $settings['file'] = Url::to($url);

        $encodedSettings = Json::htmlEncode($settings);

        if ($view === null) {
            $view = Yii::$app->view;
        }
        HelperAsset::register($view);

        return new JsExpression("alexantr.elFinder.filePickerCallback($encodedSettings)");
    }
}
