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


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput()?>

<?= $form->field($model, 'sort_order')->textInput()?>



<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
