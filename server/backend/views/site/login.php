<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model common\models\LoginForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Вход';
?>

<div class="page-header login-page header-filter" filter-color="black" style="background-image: url('/img/login.jpg'); background-size: cover; background-position: top center;">
    <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
    <div class="container">
        <div class="col-lg-4 col-md-6 col-sm-6 ml-auto mr-auto">
            <?php $form = ActiveForm::begin(); ?>
            <div class="card card-login">
                <div class="card-header card-header-info text-center">
                    <h4 class="card-title"><?= Html::encode($this->title); ?></h4>
                </div>
                <div class="card-body ">
            <span class="bmd-form-group">
                    <?= $form->field($model, 'username')->textInput(['autofocus' => false,'class' => 'form-control']); ?>
            </span>
                    <span class="bmd-form-group">
               <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control']); ?>
            </span>
                </div>
                <div class="card-footer justify-content-center">
                    <?= Html::submitButton('Поехали!', ['class' => 'btn btn-info btn-link btn-lg', 'name' => 'login-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="copyright float-right">
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script> Light Studio
            </div>
        </div>
    </footer>
</div>