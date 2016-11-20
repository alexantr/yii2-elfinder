<?php

namespace alexantr\elfinder;

use Yii;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Class InputFileAction
 * @package alexantr\elfinder
 */
class InputFileAction extends ClientBaseAction
{
    /**
     * @var string Separator for multiple paths in text input
     */
    public $separator = ', ';

    /**
     * @var string Separator for multiple paths in textarea
     */
    public $textareaSeparator = '\n';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = Yii::$app->request->getQueryParam('id');
        $id = Html::encode($id);

        $multiple = Yii::$app->request->getQueryParam('multiple');

        if (!empty($multiple)) {
            $this->settings['commandsOptions']['getfile']['multiple'] = true;
            $callback = <<<JSEXP
function (files) {
    var urls = [], separator = "{$this->separator}";
    for (var i in files) {
        urls.push(files[i].url);
    }
    var el = window.opener.document.getElementById("$id");
    if (el.tagName.toLowerCase() == "textarea") separator = "{$this->textareaSeparator}";
    if (el.value) {
        el.value = el.value + separator + urls.join(separator);
    } else {
        el.value = urls.join(separator);
    }
    window.close();
}
JSEXP;
        } else {
            $callback = <<<JSEXP
function (file) {
    window.opener.document.getElementById("$id").value = file.url;
    window.close();
}
JSEXP;
        }

        $this->settings['getFileCallback'] = new JsExpression($callback);

        return parent::run();
    }
}
