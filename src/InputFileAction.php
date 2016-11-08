<?php

namespace alexantr\elfinder;

use Yii;
use yii\web\JsExpression;

/**
 * Class InputFileAction
 * @package alexantr\elfinder
 */
class InputFileAction extends ClientAction
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = Yii::$app->request->getQueryParam('id');
        $multiple = Yii::$app->request->getQueryParam('multiple');

        if (!empty($multiple)) {
            $multiple = base64_decode($multiple);
            if (in_array($multiple, ["\r", "\n", "\r\n", 'EOL'])) {
                $multiple = '\r\n';
            }
            $this->settings['commandsOptions']['getfile']['multiple'] = true;
            $callback = <<<JSEXP
function (files) {
    var urls = [];
    for (var i in files) {
        urls.push(files[i].url);
    }
    var value = urls.join("{$multiple}");
    var el = window.opener.document.getElementById("{$id}");
    if (el.value) {
        el.value = el.value + "{$multiple}" + value;
    } else {
        el.value = value;
    }
    window.close();
}
JSEXP;
        } else {
            $callback = <<<JSEXP
function (file) {
    var value = file.url;
    window.opener.document.getElementById("{$id}").value = value;
    window.close();
}
JSEXP;
        }

        $this->settings['getFileCallback'] = new JsExpression($callback);

        return parent::run();
    }
}
