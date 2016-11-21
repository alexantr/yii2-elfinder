<?php

namespace alexantr\elfinder;

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class TinyMCE
{
    /**
     * Callback for TinyMCE 4 file_picker_callback
     * @param array|string $url Url to TinyMCEAction
     * @param array $popupSettings TinyMCE popup settings
     * @return JsExpression
     */
    public static function getFilePickerCallback($url, $popupSettings = [])
    {
        $default = [
            'title' => 'elFinder 2.1',
            'width' => 900,
            'height' => 500,
        ];

        $settings = array_merge($default, $popupSettings);
        $settings['file'] = Url::to($url);
        $encodedSettings = Json::encode($settings);

        $callback = <<<JSEXP
function (callback, value, meta) {
    tinymce.activeEditor.windowManager.open($encodedSettings, {
        oninsert: function (file, fm) {
            var url = file.url, reg = /\/[^/]+?\/\.\.\//;
            while(url.match(reg)) {
                url = url.replace(reg, '/');
            }
            if (meta.filetype == 'file') {
                callback(url, {title: file.name});
            }
            if (meta.filetype == 'image') {
                callback(url, {alt: file.name});
            }
            if (meta.filetype == 'media') {
                callback(url);
            }
        }
    });
    return false;
}
JSEXP;
        return new JsExpression($callback);
    }
}
