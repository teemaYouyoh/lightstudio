<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 11/28/18
 * Time: 4:04 AM
 */

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="overflow: hidden; height: 100vh;">

<?php $this->beginBody() ?>
<div class="wrapper wrapper-full-page">
        <?= $content ?>
</div>
<footer class="footer">
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

