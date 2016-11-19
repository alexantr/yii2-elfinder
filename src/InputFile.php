<?php
namespace alexantr\elfinder;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * Class InputFile
 * @package alexantr\elfinder
 */
class InputFile extends InputWidget
{
    /**
     * @var string Route to elFinder client
     */
    public $clientRoute;

    /**
     * @var array Allowed mimes
     * @see https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#onlyMimes
     */
    public $filter;

    /**
     * @var bool Allow select multiple files
     */
    public $multiple = false;

    /**
     * @var string Use textarea for multiple paths
     */
    public $textarea = false;

    /**
     * @inheritdoc
     */
    public $options = ['class' => 'form-control'];

    /**
     * @var string Input template
     */
    public $template = '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>{preview}';

    /**
     * @var string Input template
     */
    public $textareaTemplate = '{input}<div class="help-block">{button}</div>{preview}';

    /**
     * @var string Preview template
     */
    public $previewTemplate = '<div class="help-block elfinder-input-preview">{preview}</div>';

    /**
     * @var string Browse button html tag
     */
    public $buttonTag = 'button';

    /**
     * @var string Browse button text
     */
    public $buttonText = 'Select';

    /**
     * @var array Browse button options
     */
    public $buttonOptions = ['class' => 'btn btn-default'];

    /**
     * @var callable Custom callable function which showing preview
     */
    public $preview;

    private $url;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->clientRoute === null) {
            throw new InvalidConfigException('Client route must be specified.');
        }

        if (empty($this->buttonOptions['id'])) {
            $this->buttonOptions['id'] = $this->options['id'] . '_button';
        }
        if ($this->buttonTag == 'button') {
            $this->buttonOptions['type'] = 'button';
        }

        $route = [$this->clientRoute];
        $route['id'] = $this->options['id'];
        if (!empty($this->filter)) {
            $route['filter'] = $this->filter;
        }
        if ($this->multiple) {
            $route['multiple'] = 1;
        }

        $this->url = Url::toRoute($route);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->multiple && $this->textarea && !isset($this->options['rows'])) {
            $this->options['rows'] = 5;
        }
        if ($this->multiple && $this->textarea) {
            $this->template = $this->textareaTemplate;
        }

        $replace = [];
        if ($this->hasModel()) {
            if ($this->multiple && $this->textarea) {
                $replace['{input}'] = Html::activeTextarea($this->model, $this->attribute, $this->options);
            } else {
                $replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
            }
        } else {
            if ($this->multiple && $this->textarea) {
                $replace['{input}'] = Html::textarea($this->name, $this->value, $this->options);
            } else {
                $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
            }
        }
        $replace['{button}'] = Html::tag($this->buttonTag, $this->buttonText, $this->buttonOptions);

        // callable preview
        $replace['{preview}'] = '';
        if (is_callable($this->preview)) {
            $value = null;
            if ($this->hasModel()) {
                $value = Html::getAttributeValue($this->model, $this->attribute);
            } else {
                $value = $this->value;
            }
            if ($value !== null) {
                $replace['{preview}'] = str_replace('{preview}', call_user_func($this->preview, $value), $this->previewTemplate);
            }
        }

        $js = <<<JSEXP
jQuery(document).on('click', '#{$this->buttonOptions['id']}', function (e) {
    e.preventDefault();
    var w = screen.width / 1.5;
    var h = screen.height / 1.5;
    if (w < 900 && screen.width > 960) w = 900;
    if (h < 600 && screen.height > 660) h = 600;
    var params = 'menubar=no,toolbar=no,location=no,directories=no,status=no,fullscreen=no,width=' + w + ',height=' + h;
    var win = window.open('{$this->url}', 'elfinder_{$this->options['id']}', params);
    win.focus();
});
JSEXP;

        $this->getView()->registerJs($js);

        echo strtr($this->template, $replace);
    }
}
