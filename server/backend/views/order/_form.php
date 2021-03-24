<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 3/7/19
 * Time: 12:20 PM
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;

?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="order">
                    <?= Html::beginForm() ?>

                    <div class="form-group">
                        <?= Html::label('Имя', 'firstname',['class' => 'required']) ?>
                        <?= Html::input('text', 'firstname','', ['class' => 'form-control']) ?>

                    </div>
                    <div class="form-group">
                        <?= Html::label('Email', 'email',['class' => 'required']) ?>
                        <?= Html::input('text', 'email','', ['class' => 'form-control']) ?>
                    </div>

                    <div class="form-group">
                        <?= Html::label('Телефон', 'phone',['class' => 'required']) ?>
                        <?= Html::input('text', 'phone','', ['class' => 'form-control']) ?>
                    </div>

                    <div class="form-group">
                        <label for="photozone" class="required">Локация</label>
                        <select class="form-control" v-model="photozoneSelect">
                            <option  v-for=" (photozone, key) in photozones" :value="key">{{ photozone.productDescription.title }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <?= Html::label('Дата съёмки', 'date',['class' => 'required']) ?>
                        <?= Html::input('text', 'date','', [
                                'class' => 'form-control datepicker',
                                'v-model' => 'dateInput',
                                 '@focusout' => 'busyIntervals()',

                        ]) ?>
                    </div>
                    <div v-if="dateSet" class="form-group d-flex flex-column">
                        <h4>Время съмки</h4>
                        <div class="form-check form-check-inline"  v-for=" (interval, key) in intervals">
                            <input @change="setTime()" v-model="selectIntervals" type="checkbox" :value="key"  :id="interval.code">
                            <span class="ml-3">{{ interval.title }}</span>
                        </div>
                    </div>
                    <div v-if="timeSet" class="form-group">
                        <h4>Опции</h4>
                        <div class="form-check form-check-inline"  v-for=" (option, key) in options">
                            <input type="checkbox" :value="key"  :id="option.option_id">
                           <span class="ml-3">{{ option.optionDescription.value }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="orderStatus" class="required">Статус заказа</label>
                        <select class="form-control" v-model="statusSelect">
                            <option  v-for=" (status, key) in statuses" :value="key">{{ status.title }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <strong>Сумма: </strong> {{ total | currency }} грн.
                    </div>

                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>



<?php $script = <<<JS


    let app = new Vue({
            el: '#order',
            data: {
                photozones: [] ,
                options: [] ,
                intervals: [] ,
                statuses: [] ,
                basket: [],
                photozoneSelect: 0,
                statusSelect: 0,
                selectIntervals: [],
                quantity: 1,
                timeSet: false,
                dateSet: false,
                dateInput: '',
                
            },
            mounted() {
                axios
                    .get('/order/photozones')
                    .then(response => (this.photozones = response.data));
                 axios
                    .get('/order/options')
                    .then(response => (this.options = response.data));
                 axios
                    .get('/order/intervals')
                    .then(response => (this.intervals = response.data));
                  axios
                    .get('/order/statuses')
                    .then(response => (this.statuses = response.data));
            },
            computed: {
                total() {
                    return this.selectIntervals.length *  this.photozones[this.photozoneSelect].price;
                }
            },
            methods: {
                addToBasket(){
                    this.basket.push({ id: this.selected,name: this.products[this.selected].name, quantity: this.quantity, price: this.products[this.selected].price * this.quantity});
                },
                removeFromBasket(product){
                    this.basket.splice(this.basket.indexOf(product), 1)
                },
                busyIntervals(){
                    let datepickerVal = $('.datepicker').val();
                    if( datepickerVal =! ''){
                         this.dateInput = $('.datepicker').val();
                         this.dateSet = true;
                    }
                },
                setTime() {
                 
                    //  if($(this).prop('checked')){
                    //     newIntervals.push($(this).val());
                    //     addInterval(newIntervals);
                    // } else {
                    //     let index = newIntervals.indexOf($(this).val());
                    //  if (index > -1) {
                    //     newIntervals.splice(index, 1);
                    // }
                    // removeInterval(newIntervals);
               // }
                    // this.basket.push({time: this.intervals[key].code });
                }
                
            },
            filters: {
                currency(value) {
                    return value.toFixed(2);
                }
            }
        });

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY-MM-DD',
            icons: {
                // time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove',
            },
        });



JS;
$this->registerJS($script, View::POS_READY); ?>


    <style>
        #orderinterval-id {
            display: flex;
            flex-direction: column;
        }

        .productPrice {
            display: flex;
            align-items: center;
            height: 100%;
            font-weight: bold;
        }

        .total,
        .total-text,
        .currency{
            font-size: 20px;
            font-weight: bold;
        }

    </style>

