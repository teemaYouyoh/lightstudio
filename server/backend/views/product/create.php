<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/21/19
 * Time: 1:56 PM
 */

use yii\helpers\Html;
    $this->title = 'Добавить товар';
?>


    <?= $this->render('_form', [
            'model' => $model,
            'categories' => $categories,
            'attributes' => $attributes,
    ]) ?>
