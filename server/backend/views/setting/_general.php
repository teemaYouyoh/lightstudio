<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/21/19
 * Time: 3:19 PM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>


<?php $form = ActiveForm::begin([

]); ?>

<?= $form->field($model, 'phone')->textInput()?>

<?= $form->field($model, 'email')->textInput()?>

<?= $form->field($model, 'address')->textInput()?>

<?= $form->field($model, 'instagram')->textInput()?>

<?= $form->field($model, 'facebook')->textInput()?>

<?//= $form->field($model, 'logo')->fileInput() ?>


<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
