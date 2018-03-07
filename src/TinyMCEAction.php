<?php

namespace alexantr\elfinder;

use yii\web\JsExpression;

/**
 * TinyMCE action
 */
class TinyMCEAction extends ClientBaseAction
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $callback = <<<JSEXP
function (file) {
    parent.tinymce.activeEditor.windowManager.getParams().oninsert(file);
    parent.tinymce.activeEditor.windowManager.close();
}
JSEXP;
        $this->settings['getFileCallback'] = new JsExpression($callback);

        return parent::run();
    }
}
