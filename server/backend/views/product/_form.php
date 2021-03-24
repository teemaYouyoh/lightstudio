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
use yii\web\View;
use yii\widgets\Pjax;

?>

<div class="row justify-content-center">
    <div class="col-md-8">
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
                            <li class="nav-item">
                                <a class="nav-link" href="#data" data-toggle="tab">
                                    <i class="material-icons"></i> Данные
                                    <div class="ripple-container"></div>
                                    <div class="ripple-container"></div></a>
                            </li>
                             <?php if($model->product_id): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#attributes" data-toggle="tab">
                                    <i class="material-icons"></i>  Атрибуты
                                    <div class="ripple-container"></div>
                                    <div class="ripple-container"></div></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#images" data-toggle="tab">
                                    <i class="material-icons"></i>  Изображения
                                    <div class="ripple-container"></div>
                                    <div class="ripple-container"></div></a>
                            </li>
                           <!--  <li class="nav-item">
                                <a class="nav-link" href="#video" data-toggle="tab">
                                    <i class="material-icons"></i>  Видео
                                    <div class="ripple-container"></div>
                                    <div class="ripple-container"></div></a>
                            </li> -->
                             <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php $form = ActiveForm::begin(['options' => [
                    'enctype' => 'multipart/form-data',
                    'id' => 'productInfo'
                ]
                ]); ?>
                <div class="tab-content">
                    <div class="tab-pane active show" id="general">

                        <?php   if($model->product_id): ?>
                            <?= $form->field($model, 'product_id')->hiddenInput(['class' => 'product_id'])->label(false)?>
                        <?php   endif; ?>

                        <?= $form->field($model, 'title')->textInput() ?>

                        <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>

                        <?= $form->field($model, 'meta_title')->textInput() ?>

                        <?= $form->field($model, 'meta_description')->textInput() ?>

                        <?= $form->field($model, 'meta_keywords')->textInput() ?>

                    </div>
                    <div class="tab-pane" id="data">

                           <?= $form->field($model, 'image')->widget(InputFile::className(), [
                               'language'      => 'ru',
                               'controller'    => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
                               'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                               'template'      => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                               'options'       => ['class' => 'form-control'],
                               'buttonOptions' => ['class' => 'btn btn-sm btn-default'],
                               'multiple'      => false       // возможность выбора нескольких файлов
                           ]); ?>

                            <?= $form->field($model, 'video')->widget(InputFile::className(), [
                                'language'      => 'ru',
                                'controller'    => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
                                'path' => 'video', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                                //'filter'        => 'image',    // фильтр файлов, можно задать массив фильтров
                                'template'      => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                                'options'       => ['class' => 'form-control'],
                                'buttonOptions' => ['class' => 'btn btn-sm btn-default'],
                                'multiple'      => false       // возможность выбора нескольких файлов
                            ]); ?>


                        <?= $form->field($model, 'sku')->textInput() ?>

                        <?= $form->field($model, 'price')->textInput() ?>

                        <?= $form->field($model, 'sort_order')->textInput() ?>

                        <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'category_id', 'title'),
                            [

                            ]) ?>

                        <?= $form->field($model, 'status')->dropDownList([
                            '1' => 'Активно',
                            '0' => 'Отключено',
                        ])->label('') ?>

                    </div>
                    <div class="tab-pane" id="attributes">
                        <div class="form-row">
                            <div class="col-lg-4">
                                <?= $form->field($model, 'attribute_value')->textInput() ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'attribute')->dropDownList(ArrayHelper::map($attributes, 'attribute_id', 'title'),
                                    [
                                        'prompt' => 'Выберите атрибут...',

                                    ])->label(false) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= Html::button('<i class="material-icons">add</i>', ['class' => 'btn btn-sm btn-success js-add-attribute']) ?>
                            </div>
                        </div>
                        <?php Pjax::begin(['enablePushState' => false, 'id' => 'product-attributes']); ?>
                        <div class="form-row">
                            <?php  if($model->attributes): ?>

                                <?php foreach ($model->attributes as $key => $attribute): ?>
                                <div class="col-lg-4">
                                    <b><?= Html::encode($attribute->attributeDescription->title)?></b>
                                </div>
                                   <div class="col-lg-4">
                                       <?= $form->field($attribute, "[{$attribute['attribute_id']}]value")->textInput()->label(false) ?>
                                   </div>
                                    <div class="col-lg-4">
                                        <?= Html::button('<i class="material-icons">close</i>', [
                                                'class' => 'btn btn-sm btn-danger js-delete-attribute',
                                                'data' => [
                                                        'attribute_id' => $attribute['attribute_id'],
                                                ]
                                        ]) ?>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php Pjax::end(); ?>
                    </div>
                    <div class="tab-pane" id="images">       
                        <div class="form-row mb-5">
                                <div class="col-lg-4">
                            <?= $form->field($model, 'images[]')->fileInput([
                                        'multiple' => true, 
                                        'accept' => 'image/*',
                                        'id' => 'imagesCatalog'
                                ])->label('Выбрать изображения') ?>
                                <p class="text-warning countFiles">Число файлов: 0</p>
                                </div>
                            <div class="col-lg-4">
                                <?= Html::button('<i class="material-icons">cloud_upload</i> <span>Загрузить</span>', [
                                    'class' => 'btn btn-success js-add-image',
                                    'disabled' => true
                                 ]) ?>
                            </div>
                        </div>

                        <style>
                            .field-imagesCatalog label {
                                cursor: pointer;
                                border-bottom: 1px solid #000;
                            }
                        </style>
                         <?php Pjax::begin(['enablePushState' => false, 'id' => 'product-images-ajax']); ?>  
                        <?php  if($model->images): ?>
                            <div class="row mb-5">
                                <?php foreach ($model->images as $key => $image): ?>
                                    <div class="col-lg-3 mb-5">
                                        <div class="img-block" style="position: relative width: 150px; height: 150px; ">
                                            <img style="object-fit: cover;" src="<?= Html::encode($image['image'])?>" width="100%" height="100%">
                                            <div class="row">
                                                        <div class="col-lg-8">
                                                    <?= Html::button('<i class="material-icons">clear</i>', [
                                                'class' => 'btn btn-block btn-danger btn-sm js-delete-image',
                                                'data' => [
                                                    'product_image_id' => $image['product_image_id'],
                                                ]
                                            ]) ?>
                                            </div>
                                            <div class="col-lg-4">
                                                <input class="imageSort form-control" data-id="<?php echo $image['product_image_id'] ?>" type="number" value="<?= Html::encode($image['sort_order']) ?>">
                                            </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                           <?php Pjax::end(); ?>
                    </div>

                    <div class="tab-pane" id="video">
                        <div class="form-row">
                            <div class="col-lg-9">
                                <?= $form->field($model, 'video_value')->textInput() ?>
                            </div>
                            <div class="col-lg-3">
                                <?= Html::button('<i class="material-icons">add</i>', ['class' => 'btn btn-sm btn-success js-add-video']) ?>
                            </div>
                        </div>
                        <?php Pjax::begin(['enablePushState' => false, 'id' => 'product-videos']); ?>
                        <div class="form-row">
                            <?php  if($model->videos): ?>
                                <?php foreach ($model->videos as $key => $video): ?>
                                    <div class="col-lg-9">
                                        <?= $form->field($video,   "[{$video['product_video_id']}]video")->textInput()->label(false) ?>
                                    </div>
                                    <div class="col-lg-3">
                                        <?= Html::button('<i class="material-icons">close</i>', [
                                            'class' => 'btn btn-sm btn-danger js-delete-video',
                                            'data' => [
                                                'product_video_id' => $video['product_video_id'],
                                            ]
                                        ]) ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php Pjax::end(); ?>
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

<?php $script = <<<JS

$('input[type=file]').change(function () {
    let sendData = new FormData(),
    ins = document.getElementById("imagesCatalog").files.length;
    $('.countFiles').text('Число файлов: ' + ins);

    if(ins > 0) {
        $('.js-add-image').prop("disabled", false);
    } else {
         $('.js-add-image').prop("disabled", true);
    }
});

    $('body').on('click','.js-add-attribute', function(e) {
        var attribute_value = $('#product-attribute_value').val();
        var attribute = $('#product-attribute option:selected').val();
        var product_id = $('.product_id').val();
        e.preventDefault();

        if(attribute_value.length > 0 || attribute.length > 0) {
        $.ajax({
            dataType: "json",
            url: "/attribute/add-product-attribute",
            data: 'attribute_value=' + attribute_value + '&attribute=' + attribute + '&product_id=' + product_id,
            type: "post",
            success: function(res) {
                
              $.pjax.reload({container: '#product-attributes', async: true});
            }
        });
        } else {
            alert('Введите значения!');
        }
    });

    $('body').on('click','.js-delete-attribute',function(e) {
        var attribute_id = $(this).data('attribute_id');
        var product_id = $('.product_id').val();
          e.preventDefault();
        $.ajax({
            dataType: "json",
            url: "/attribute/delete-product-attribute",
            data: 'attribute_id=' + attribute_id + '&product_id=' + product_id,
            type: "post",
            success: function(res) {
               $.pjax.reload({container: '#product-attributes', async: true});
            }
        });
     
    });
    
      $('body').on('click','.js-add-video', function(e) {
        let video_value = $('#product-video_value').val();
        let product_id = $('.product_id').val();
        e.preventDefault();

        if(video_value.length > 0) {
        $.ajax({
            dataType: "json",
            url: "/product/add-product-video",
            data: 'video_value=' + video_value + '&product_id=' + product_id,
            type: "post",
            success: function(res) {
                 console.log(res);
              $.pjax.reload({container: '#product-videos', async: true});
            }
        });
        } else {
            alert('Введите значения!');
        }
    });

    $('body').on('click','.js-delete-video',function(e) {
        let video_id = $(this).data('product_video_id');
        let product_id = $('.product_id').val();
          e.preventDefault();
        $.ajax({
            dataType: "json",
            url: "/product/delete-product-video",
            data: 'product_video_id=' + video_id + '&product_id=' + product_id,
            type: "post",
            success: function(res) {
                console.log(res);
               $.pjax.reload({container: '#product-videos', async: true});
            }
        });
     
    });

//     $('.imagesCatalog').onchange = function(event) {
//    var fileList = event.target.files;
//     console.log(fileList);
// }



     $('body').on('click','.js-add-image',function(e) {
        let sendData = new FormData(),
         ins = document.getElementById("imagesCatalog").files.length;
        let productId = $('.product_id').val();
        sendData.append('productId', productId);
       
        for (var x = 0; x < ins; x++) {
            sendData.append("images[]", document.getElementById("imagesCatalog").files[x]);
        }

            $.ajax({
            type: "POST",
            url: "/product/upload",
            data: sendData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                    $('.js-add-image span').text('Загрузка...');
                    $('.js-add-image').prop("disabled", true);
            },
            success: function(response) {
                $.pjax.reload({container: '#product-images-ajax', async: true});
                $('.countFiles').text('Число файлов: 0');
                // $('.js-add-image').prop("disabled", true);
                 $('.js-add-image span').text('Загрузить');
            },
            error: function(errResponse) {
                console.log(errResponse);
            }
        });
    });



    $('.imageSort').on('keyup', function() {
        let imageId = $(this).data('id');
        let sort = $(this).val();
        console.log('Сортировка:' ,sort);

        if(sort){
                  $.ajax({
            dataType: "json",
            url: "/product/update-sort-product-image",
            data: 'imageId=' + imageId + '&sortOrder=' + sort,
            type: "post",
            success: function(res) {
                console.log(res);
               // $.pjax.reload({container: '#product-images-ajax', async: true});
            },
            error: function(errResponse) {
                console.log(errResponse);
            }
        });
        } else {
            console.log('Пустое значение');
        }

      
        });


     $('body').on('click','.js-delete-image',function(e) {
        let imageId = $(this).data('product_image_id');
        let productId = $('.product_id').val();
        e.preventDefault();

         result = confirm('Вы уверены, что хотите удалить?');
        
    if(result){
        $.ajax({
            dataType: "json",
            url: "/product/delete-product-image",
            data: 'product_image_id=' + imageId + '&product_id=' + productId,
            type: "post",
            success: function(res) {
                console.log(res);
               $.pjax.reload({container: '#product-images-ajax', async: true});
            },
             error: function(errResponse) {
                console.log(errResponse);
            }
        });
    } 
    

    
    });



JS;
$this->registerJS($script, View::POS_READY); ?>


