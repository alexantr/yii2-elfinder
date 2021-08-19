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
    if (parent.tinymce.majorVersion === "4") {
        parent.tinymce.activeEditor.windowManager.getParams().oninsert(file);
        parent.tinymce.activeEditor.windowManager.close();
    } else {
        parent.postMessage({mceAction: "customAction", file: file}, "*");
    }
}
JSEXP;
        $this->settings['getFileCallback'] = new JsExpression($callback);

        return parent::run();
    }
}
