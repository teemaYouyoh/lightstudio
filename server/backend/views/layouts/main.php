<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $activeController = Yii::$app->controller->getUniqueId(); ?>
<?php $this->beginBody() ?>
<div class="wrapper">
    <div class="sidebar" data-color="azure" data-background-color="white" data-image="">
        <div class="logo">
            <a href="/" class="simple-text logo-normal text-center">
                <?= Yii::$app->params['companyName'] ?>
            </a></div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li class="nav-item <?php if( $activeController == 'site') { echo 'active';} ?>">
                    <a class="nav-link" href="/">
                        <i class="material-icons">dashboard</i>
                        <p>Панель состояния</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" data-toggle="collapse" href="#componentsCatalog" aria-expanded="false">
                        <i class="material-icons">local_offer</i>
                        <p> Каталог
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse
                <?php
                    switch($activeController){
                        case 'category':
                        case  'product':
                        case  'attribute':
                        case  'page':
                        case  'attribute-group':
                            echo 'show';
                            break;
                    }
                    ?>
              " id="componentsCatalog">
                        <ul class="nav">
                            <li class="nav-item <?php if( $activeController == 'category') { echo 'active';} ?>">
                                <a class="nav-link" href="<?= Url::to('/category') ?>">
                                    <i class="material-icons"></i>
                                    <p>Категории</p>
                                </a>
                            </li>
                            <li class="nav-item <?php if( $activeController == 'product') { echo 'active';} ?>">
                                <a class="nav-link" href="<?= Url::to('/product') ?>">
                                    <i class="material-icons"></i>
                                    <p>Товары</p>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link" data-toggle="collapse" href="#componentsAttribute" aria-expanded="false">
                                    <p> Атрибуты
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <div class="collapse
                <?php
                                switch($activeController){
                                    case 'attribute':
                                    case  'attribute-group':
                                        echo 'show';
                                        break;
                                }
                                ?>
              " id="componentsAttribute">
                                    <ul class="nav">
                                        <li class="nav-item <?php if( $activeController == 'attribute') { echo 'active';} ?>">
                                            <a class="nav-link" href="<?= Url::to('/attribute') ?>">
                                                <i class="material-icons"></i>
                                                <p>Атрибуты</p>
                                            </a>
                                        </li>
                                        <li class="nav-item <?php if( $activeController == 'attribute-group') { echo 'active';} ?>">
                                            <a class="nav-link" href="<?= Url::to('/attribute-group') ?>">
                                                <i class="material-icons"></i>
                                                <p>Группа атрибутов</p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item <?php if( $activeController == 'page') { echo 'active';} ?>">
                                <a class="nav-link" href="<?= Url::to('/page') ?>">
                                    <i class="material-icons"></i>
                                    <p>Страницы</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" data-toggle="collapse" href="#componentsSales" aria-expanded="false">
                        <i class="material-icons">store</i>
                        <p> Продажи
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse
                <?php
                    switch($activeController){
                        case 'order':
                        case 'calendar':
                            echo 'show';
                            break;
                    }
                    ?>
              " id="componentsSales">
                        <ul class="nav">
<!--                            <li class="nav-item --><?php //if( $activeController == 'order') { echo 'active';} ?><!--">-->
<!--                                <a class="nav-link" href="--><?//= Url::to('/order') ?><!--">-->
<!--                                    <i class="material-icons"></i>-->
<!--                                    <p>Заказы</p>-->
<!--                                </a>-->
<!--                            </li>-->
                            <li class="nav-item <?php if( $activeController == 'order') { echo 'active';} ?>">
                                <a class="nav-link" href="<?= Url::to('/order') ?>">
                                    <i class="material-icons"></i>
                                    <p>Заказы</p>
                                </a>
                            </li>
                            <li class="nav-item <?php if( $activeController == 'booking') { echo 'active';} ?>">
                                <a class="nav-link" href="<?= Url::to('/booking') ?>">
                                    <i class="material-icons"></i>
                                    <p>Брони</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php if( $activeController == 'portfolio') { echo 'active';} ?>">
                    <a class="nav-link" href="<?= Url::to('/portfolio') ?>">
                        <i class="material-icons">photo_library</i>
                        <p>Портфолио</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" data-toggle="collapse" href="#componentsSettings" aria-expanded="false">
                        <i class="material-icons">settings</i>
                        <p> Система
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse
                <?php
                    switch($activeController ){
                        case 'setting':
                            echo 'show';
                            break;
                    }
                    ?>
              " id="componentsSettings">
                        <ul class="nav">
                            <li class="nav-item <?php if($activeController == 'setting') { echo 'active';} ?>">
                                <a class="nav-link"  href="<?= Url::to('/setting') ?>">
                                    <span class="sidebar-normal">Настройки</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <p class="navbar-brand" ><?= Html::encode($this->title); ?></p>
                </div>
                <form class="navbar-form">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                </form>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li>
                            <?= Yii::$app->user->identity->username ?>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="/" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">person</i>
                                <p class="d-lg-none d-md-block">
                                    Аккаунт
                                </p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
<!--                                <a class="dropdown-item" href="--><?//= Url::to('/user') ?><!--"> <i class="material-icons">people</i> <span style="margin: 0 0 0 10px;">Users</span></a>-->
<!--                                <a class="dropdown-item" href="--><?//= Url::to('/role') ?><!--#"> <i class="material-icons">lock</i> <span style="margin: 0 0 0 10px;">Roles</span></a>-->
<!--                                <a class="dropdown-item" href="--><?//= Url::to('/route') ?><!--"> <i class="material-icons">list</i> <span style="margin: 0 0 0 10px;">Routes</span></a>-->
                                <?= Html::a( '<i class="material-icons">power_settings_new</i> <span style="margin: 0 0 0 10px;">Выйти</span>', ['/site/logout'], [
                                    'data' => ['method' => 'post'],
                                    'class' => 'dropdown-item',
                                ]);?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="content">
            <div id="app" class="container-fluid">
                <?= $content ?>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <nav class="float-left">
                    <ul>
                        <li>
                            <a href="/">
                                © <script>document.write(new Date().getFullYear()) </script>, <?= Yii::$app->params['companyName'] ?>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright float-right">
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
