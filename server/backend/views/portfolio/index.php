<?php

    $this->title = 'Портфолио';
    use yii\helpers\Url;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Html;
    use yii\web\View;
?>

<div class="container">
    <div class="row">
        <div class="form-row mb-5">
               <input class="form-control" name="images[]" id="imagesCatalog" type="file" multiple="true" />
        </div>
     <div class="col-lg-4">
        <?= Html::button('<i class="material-icons">cloud_upload</i> <span>Загрузить</span>', [
                                    'class' => 'btn btn-success js-add-image',
                                    'disabled' => true,
        ]) ?>
      </div>
    </div>
</div>
  <?php Pjax::begin(['enablePushState' => false, 'id' => 'portfolio-box']); ?>  
    <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'id' => 'portfolio-grid',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Изображение',
                    'attribute' => 'image',
                    'format' => 'raw',
                    'value' => function($model,$key) {
                        return '<img src="'. $model['image']  .'" width="80">';
                    }

                ],
                [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'contentOptions' =>  ['class'=>'td-actions text-right'],
            'headerOptions' =>  ['class'=>'text-center'],
            'buttons' => [
                'delete' => function ($model,$key) {
                    return Html::button('<i class="material-icons">delete</i><div class="ripple-container"></div>',[
                        'class' => 'btn btn-danger js-delete',
                        'data' => [
                            'id' => $key['id']
                        ]
                    ]);
                },
            ],
        ],
            ],
        ]);
    ?>
<?php Pjax::end() ?>

<?php $script = <<<JS

    //  $('body').on('click','.js-add-image',function(e) {
    //     let sendData = new FormData(),
    //      ins = document.getElementById("imagesCatalog").files.length;
      
    //     for (var x = 0; x < ins; x++) {
    //         sendData.append("images[]", document.getElementById("imagesCatalog").files[x]);
    //     }

    //      $.ajax({
    //         type: "POST",
    //         url: "/portfolio/upload",
    //         data: sendData,
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         success: function(response) {
    //             $.pjax.reload({container: '#portfolio-box', async: true});
    //         },
    //         error: function(errResponse) {
    //             console.log(errResponse);
    //         }
    //     });
    // });

$('input[type=file]').change(function () {
    let sendData = new FormData(),
    ins = document.getElementById("imagesCatalog").files.length;
    if(ins > 0) {
        $('.js-add-image').prop("disabled", false);
    } else {
         $('.js-add-image').prop("disabled", true);
    }
});


     $('body').on('click','.js-add-image',function(e) {
        
        let sendData = new FormData(),
        ins = document.getElementById("imagesCatalog").files.length;
      
        for (var x = 0; x < ins; x++) {
            sendData.append("images[]", document.getElementById("imagesCatalog").files[x]);
        }

            $.ajax({
            type: "POST",
            url: "/portfolio/upload",
            data: sendData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                    $('.js-add-image span').text('Загрузка...');
                    $('.js-add-image').prop("disabled", true);
            },
            success: function(response) {
                $.pjax.reload({container: '#portfolio-box', async: true});
                 $('.js-add-image span').text('Загрузить');
            },
            error: function(errResponse) {
                console.log(errResponse);
            }
        });
    });

    $('body').on('click','.js-delete',function(e) {
        let id = $(this).data('id');

        result = confirm('Вы уверены, что хотите удалить?');
        
        if(result){
               $.ajax({
                dataType: "json",
                type: "POST",
                url: "/portfolio/delete",
                data: 'id=' + id,
                success: function(response) {
                    $.pjax.reload({container: '#portfolio-grid', async: true});
                },
                error: function(errResponse) {
                    console.log(errResponse);
                }
            });
        } 
    });

  

JS;
$this->registerJS($script, View::POS_READY);
?>
