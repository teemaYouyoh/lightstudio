<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/23/19
 * Time: 6:31 PM
 */


use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\View;

$this->title = 'Группы атрибутов';

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
                            <th>Название</th>
                            <th class="text-right">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($attributeGroups): ?>
                            <?php foreach ($attributeGroups as $k => $attributeGroup): ?>
                                <tr class="item-<?= $attributeGroup['attribute_group_id'] ?>">
                                    <td><?= Html::encode($attributeGroup['title'])?></td>
                                    <td class="td-actions text-right">
                                        <?= Html::a('<span class="btn-label"> <i class="material-icons">edit</i></span>', ['update','id' => $attributeGroup['attribute_group_id']], ['class' => 'btn btn-success']) ?>
                                        <?= Html::button('<span class="btn-label"> <i class="material-icons">close</i></span>',
                                            [
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                                    'id' => $attributeGroup['attribute_group_id']
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
        // controlItem.deleteItem('test');
         $.ajax({
            dataType: "json",
            url: "/attribute-group/delete",
            data: 'id=' + id,
            type: "post",
            success: function(res) {
                $('.item-' + id).fadeOut();
            }
        });
    });
JS;
$this->registerJS($script, View::POS_READY); ?>
