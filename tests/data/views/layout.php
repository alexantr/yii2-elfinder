<?php
/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>

<?= isset($content) ? $content : '' ?>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>