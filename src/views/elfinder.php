<?php

use alexantr\elfinder\ElFinder;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $connectorRoute string */
/* @var $settings array */

$css = <<<CSSEXP
html, body { height: 100%; margin: 0; padding: 0; overflow: hidden; }
body { background: #fff; }
.elfinder.ui-corner-all, .elfinder-toolbar.ui-corner-top, .elfinder-statusbar.ui-corner-bottom { border-radius: 0; }
CSSEXP;
$this->registerCss($css);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>elFinder</title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
<?= ElFinder::widget([
    'view' => $this,
    'connectorRoute' => $connectorRoute,
    'settings' => $settings,
]) ?>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
