<?php

namespace alexantr\elfinder;

use yii\web\JsExpression;

/**
 * CKEditor action
 */
class CKEditorAction extends ClientBaseAction
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $callback = <<<JSEXP
function (file) {
    var reParam = new RegExp('(?:[\?&]|&amp;)CKEditorFuncNum=([^&]+)', 'i');
    var match = window.location.search.match(reParam);
    var funcNum = (match && match.length > 1) ? match[1] : '';
    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
    window.close();
}
JSEXP;
        $this->settings['getFileCallback'] = new JsExpression($callback);

        return parent::run();
    }
}
