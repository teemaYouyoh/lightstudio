<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:20 PM
 */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Заказы';

?>

<div class="row justify-content-end">
    <div class="col-lg-12">
        <?//= Html::a('<span class="btn-label"> <i class="material-icons">add</i></span> Новый заказ', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title"><?= Html::encode($this->title)?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            // 'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Заказ №',
                    'attribute' => 'order_id',
                ],
                [
                    'label' => 'Клиент',
                    'attribute' => 'customer_firstname',
                ],
                [
                    'label' => 'Телефон',
                    'attribute' => 'customer_phone',
                ],
                [
                    'label' => 'Email',
                    'attribute' => 'customer_email',
                ],
                [
                    'label' => 'Дата заказа',
                    'attribute' => 'date_create',
                ],
                // [
                //     'label' => 'Способ оплаты',
                //     'attribute' => 'payment_method',
                // ],
                [
                    'label' => 'Статус',
                    'attribute' => 'status',
                ],
                [
                    'label' => 'Сумма',
                    'attribute' => 'amount',
                ],
            ],
        ]);
        ?>

                </div>
            </div>
        </div>
    </div>
</div>