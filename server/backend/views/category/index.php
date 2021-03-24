<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:20 PM
 */


use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

$this->title = 'Категории';

?>
<?php Pjax::begin(); ?>
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
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Порядок</th>
                            <th class="text-right">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($categories): ?>
                        <?php foreach ($categories as $k => $category): ?>
                            <tr class="item-<?= $category['category_id'] ?>">
                                <td><?= Html::encode($category['title'])?></td>
                                <td><?= Html::encode($category['status']) == 1 ?   'Активно' :  'Выключено'?></td>
                                <td><?= Html::encode($category['sort_order'])?></td>
                                <td class="td-actions text-right">
                                    <?= Html::a('<span class="btn-label"> <i class="material-icons">edit</i></span>', ['update','id' => $category['category_id']], ['class' => 'btn btn-success']) ?>
                                    <?= Html::button('<span class="btn-label"> <i class="material-icons">close</i></span>',
                                        [
                                            'data' => [
                                                'confirm' => 'Вы уверены, что хотите удалить?',
                                                'id' => $category['category_id']
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
<?php Pjax::end(); ?>
<?php $script = <<<JS
    $('.js-delete-item').on('click', function(e) {
        var id = $(this).data('id');
        var itemName = $(this).data('name');
        // controlItem.deleteItem('test');
         $.ajax({
            dataType: "json",
            url: "/category/delete",
            data: 'id=' + id,
            type: "post",
            success: function(res) {
                $('.item-' + id).fadeOut();
            }
        });
    });
JS;
$this->registerJS($script, View::POS_READY); ?>
