<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:20 PM
 */

use yii\helpers\Html;
use yii\web\View;
$this->title = 'Товары';

?>

<div class="row justify-content-end">
    <div class="col-lg-12">
        <?= Html::a('<span class="btn-label"> <i class="material-icons">add</i></span> Добавить', ['create'], ['class' => 'btn btn-success']) ?>
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Категори</th>
                            <th>Артикул</th>
                            <th>Цена</th>
                            <th>Порядок</th>
                            <th>Статус</th>
                            <th class="text-right">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($products): ?>
                            <?php foreach ($products as $k => $product): ?>
                                <tr class="item-<?= $product['product_id'] ?>">
                                   <?php if(!empty($product['image'])):?>
                                    <td>
                                      <div class="image-preview" style="height: 100px; width: 100px; overflow: hidden">
                                          <img src="<?= Html::encode($product['image'])?>" width="120">
                                      </div>
                                    </td>
                                    <? else: ?>
                                        <td>Нет изображения</td>
                                    <? endif; ?>
                                    <td><?= Html::encode($product['title'])?></td>
                                    <td><?= Html::encode($product['category'])?></td>
                                    <td><?= Html::encode($product['sku'])?></td>
                                    <td><?= Html::encode($product['price'])?></td>
                                    <td><?= Html::encode($product['sort_order'])?></td>
                                    <td><?= Html::encode($product['status']) == 1 ?   'Активно' :  'Выключено'?></td>
                                    <td class="td-actions text-right">
                                        <?= Html::a('<span class="btn-label"> <i class="material-icons">edit</i></span>', ['update','id' => $product['product_id']], ['class' => 'btn btn-success']) ?>
                                        <?= Html::button('<span class="btn-label"> <i class="material-icons">close</i></span>',
                                            [
                                                'data' => [
                                                    'id' => $product['product_id']
                                                ],
                                                'class' => 'btn btn-danger js-delete-item',
                                            ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $script = <<<JS
    $('.js-delete-item').on('click', function(e) {
        var id = $(this).data('id');
        var itemName = $(this).data('name');
       
       result = confirm('Вы уверены, что хотите удалить?');
        
        if(result){
             $.ajax({
                dataType: "json",
                url: "/product/delete",
                data: 'id=' + id,
                type: "post",
                success: function(res) {
                    $('.item-' + id).fadeOut();
                }
            });
        } 
    

    });
JS;
$this->registerJS($script, View::POS_READY); ?>