<?php

/* @var $this yii\web\View */

$this->title = 'Панель состояния';
?>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">shopping_cart</i>
                </div>
                <p class="card-category">Заказы</p>
                <h3 class="card-title"><?= $data['success_orders'] ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons text-success">done</i>
                    <a href="/order">Оплаченные</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">money</i>
                </div>
                <p class="card-category">Оборот</p>
                <h3 class="card-title"><?= $data['cash_flow'] ?></h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> За весь период
                </div>
            </div>
        </div>
    </div>
<!--    <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--        <div class="card card-stats">-->
<!--            <div class="card-header card-header-rose card-header-icon">-->
<!--                <div class="card-icon">-->
<!--                    <i class="material-icons">equalizer</i>-->
<!--                </div>-->
<!--                <p class="card-category">Популярный зал</p>-->
<!--                <h3 class="card-title">100</h3>-->
<!--            </div>-->
<!--            <div class="card-footer">-->
<!--                <div class="stats">-->
<!--                    <i class="material-icons">local_offer</i> Количество заказов-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="col-lg-3 col-md-6 col-sm-6">-->
<!--        <div class="card card-stats">-->
<!--            <div class="card-header card-header-info card-header-icon">-->
<!--                <div class="card-icon">-->
<!--                    <i class="fa fa-twitter"></i>-->
<!--                </div>-->
<!--                <p class="card-category">Подписчиков</p>-->
<!--                <h3 class="card-title">+245</h3>-->
<!--            </div>-->
<!--            <div class="card-footer">-->
<!--                <div class="stats">-->
<!--                    <i class="material-icons">update</i> За месяц-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</div>