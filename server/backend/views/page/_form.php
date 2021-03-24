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
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;


?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-tabs card-header-info">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title"></span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                            <li class="nav-item">
                                <a class="nav-link active show" href="#general" data-toggle="tab">
                                    <i class="material-icons"></i> Основное
                                    <div class="ripple-container"></div>
                                    <div class="ripple-container"></div></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="tab-content">
                    <div class="tab-pane active show" id="general">
                        <?= $form->field($model, 'title')->textInput() ?>

<!--                        --><?//= $form->field($model, 'description')->widget(CKEditor::className(),[
//                            'editorOptions' => [
//                                'preset' => 'basci',
//                                'inline' => true, //по умолчанию false
//                            ],
//                        ]); ?>

                        <?//= $form->field($model, 'sort_order')->textInput() ?>

                        <?= $form->field($model, 'meta_title')->textInput() ?>

                        <?= $form->field($model, 'meta_description')->textInput() ?>

                        <?= $form->field($model, 'meta_keywords')->textInput() ?>

                        <?= $form->field($model, 'status')->dropDownList([
                            '1' => 'Активно',
                            '0' => 'Отключено',
                        ])->label('') ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>