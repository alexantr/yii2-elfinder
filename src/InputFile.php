<?php
namespace alexantr\elfinder;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * InputFile Widget
 */
class InputFile extends InputWidget
{
    /**
     * @var string Route to elFinder client
     */
    public $clientRoute;
    /**
     * @var array|string Allowed mimes
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
     * {@inheritdoc}
     */
    public $options = ['class' => 'form-control yii2-elfinder-input'];
    /**
     * @var string Input template
     */
    public $template = '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>{preview}';
    /**
     * @var string Input template
     */
    public $textareaTemplate = '{input}<div class="help-block">{button}</div>{preview}';
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
    public $buttonOptions = ['class' => 'btn btn-default yii2-elfinder-select-button'];
    /**
     * @var int Default value in "rows" attribute for textarea
     */
    public $textareaRows = 5;
    /**
     * @var string Preview container tag name
     */
    public $previewTag = 'div';
    /**
     * @var array Preview container options
     */
    public $previewOptions = ['class' => 'help-block yii2-elfinder-input-preview'];
    /**
     * @var callable Custom callable function which showing preview
     */
    public $preview;

    private $url;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
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

        if (empty($this->previewOptions['id'])) {
            $this->previewOptions['id'] = $this->options['id'] . '_preview';
        }

        $route = [$this->clientRoute];
        $route['id'] = $this->options['id'];
        if (!empty($this->filter)) {
            $route['filter'] = $this->filter;
            $this->options['data']['filter'] = is_string($this->filter) ? $this->filter : Json::encode($this->filter);
        }
        if ($this->multiple) {
            $route['multiple'] = 1;
            $this->options['data']['multiple'] = '1';
        }

        $this->url = Url::toRoute($route);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->textarea) {
            $this->template = $this->textareaTemplate;
            if (!isset($this->options['rows'])) {
                $this->options['rows'] = $this->textareaRows;
            }
        }

        $replace = [];
        if ($this->hasModel()) {
            if ($this->textarea) {
                $replace['{input}'] = Html::activeTextarea($this->model, $this->attribute, $this->options);
            } else {
                $replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
            }
        } else {
            if ($this->textarea) {
                $replace['{input}'] = Html::textarea($this->name, $this->value, $this->options);
            } else {
                $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
            }
        }
        $replace['{button}'] = Html::tag($this->buttonTag, $this->buttonText, $this->buttonOptions);

        // preview
        $replace['{preview}'] = Html::tag($this->previewTag, '', $this->previewOptions);
        if (is_callable($this->preview)) {
            $value = null;
            if ($this->hasModel()) {
                $value = Html::getAttributeValue($this->model, $this->attribute);
            } else {
                $value = $this->value;
            }
            if ($value !== null) {
                $replace['{preview}'] = Html::tag($this->previewTag, call_user_func($this->preview, $value), $this->previewOptions);
            }
        }

        $view = $this->getView();
        HelperAsset::register($view);

        $view->registerJs("alexantr.elFinder.registerSelectButton('{$this->buttonOptions['id']}', '{$this->url}');", View::POS_END);

        return strtr($this->template, $replace);
    }
}
