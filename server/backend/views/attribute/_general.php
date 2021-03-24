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

<?= $form->field($model, 'attribute_group_id')->dropDownList(ArrayHelper::map($attributeGroups, 'attribute_group_id', 'title'),
    [
        'prompt' => 'Выберите группу...'

    ])->label('') ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
