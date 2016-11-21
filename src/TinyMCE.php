<?php

namespace alexantr\elfinder;

use yii\helpers\Url;
use yii\web\JsExpression;

class TinyMCE
{
    /**
     * Callback for TinyMCE 4 file_picker_callback
     * @param array|string $url Url to TinyMCEAction
     * @param int $width Pupup width
     * @param int $height Popup height
     * @return JsExpression
     */
    public static function getFilePickerCallback($url, $width = 900, $height = 500)
    {
        $url = Url::to($url);
        $callback = <<<JSEXP
function (callback, value, meta) {
    tinymce.activeEditor.windowManager.open({
        file: "$url",
        title: "elFinder 2.1",
        width: $width,
        height: $height
    }, {
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
