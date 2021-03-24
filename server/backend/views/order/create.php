<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = 'Новый заказ';

?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body" id="order">
                <?= Html::beginForm() ?>

                <div class="form-group">
                    <label for="photozone" class="required">Локация</label>
                    <select class="form-control" v-model="photozoneSelect">
                        <option  v-for=" (photozone, key) in photozones" :value="key">{{ photozone.productDescription.title }}</option>
                    </select>
                </div>

              <div class="form-group">
                    <?= Html::label('Дата', 'date',['class' => 'required']) ?>
                    <?= Html::input('text', 'date','', [
                        'class' => 'form-control datepicker',
                        'v-model' => 'dateInput',
                        '@focusout' => 'getBusyIntervals()',
                        '@change' => 'getBusyIntervals()',
                    ]) ?>
            </div>
                <div v-if="dateSet" class="form-group d-flex flex-column">
                    <h4>Время съмки</h4>
                    <div class="form-check form-check-inline"  v-for=" (interval, key) in intervals" v-if="checkTimeStatus(interval)">
                        <input @change="chooseInterval(interval)" v-model="selectIntervals" type="checkbox" :value="key"  :id="interval.code">
                        <span class="ml-3">{{ interval.title }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <h4>Опции</h4>

                 <div class="form-group" >
                    <label for="qvtCustomers">Количество человек</label>
                    <select class="form-control" id="qvtCustomers">
                      <option :key="key" v-for="(option, key) in options" v-if="option.optionGroup.option_group_id == 1">
                            {{ option.optionDescription.value }}
                      </option>
                   
                    </select>
                </div>


                   <div class="form-group" >
                    <label for="qvtCustomers">Свет</label>
                    <select class="form-control" id="qvtCustomers">
                      <option :key="key" v-for="(option, key) in options" v-if="option.optionGroup.option_group_id == 2">
                            {{ option.optionDescription.value }}
                      </option>
                    </select>
                </div>

                            <div class="form-group" >
                    <label for="qvtCustomers">Гримерка (часов)</label>
                    <select class="form-control" id="qvtCustomers" v-model="dressingRoom" >
                      <option :key="key" v-for="(option, key) in options" v-if="option.optionGroup.option_group_id == 3 && option.option_id != 6">
                            {{ option.optionDescription.value }}
                      </option>
                    </select>
                </div>
                 
                 
                </div>
               <div class="form-group">
                    <label for="firstname">Имя</label>
                    <input type="text" name="firstname" d="firstname" class="form-control">
                </div>
                <div class="form-group">
                    <label for="firstname">Телефон</label>
                    <input type="text" name="phone" d="phone" class="form-control">
                </div>
           <!--      <div class="form-group">
                    <label for="orderStatus" class="required">Статус заказа</label>
                    <select class="form-control" v-model="statusSelect">
                        <option  v-for=" (status, key) in statuses" :value="key">{{ status.title }}</option>
                    </select>
                </div> -->

                <div class="form-group">
                        <label for="amount">Сумма (грн.)</label>
                     <input type="text" name="amount" :value="amount" id="amount" class="form-control">
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
                statuses: [
                    { id: 7, title: 'Выставлен счёт'},
                    { id: 5, title: 'Оплачен'},
                ] ,
                basket: [],
                photozoneSelect: 0,
                statusSelect: 0,
                selectIntervals: [],
                busyIntervals: [],
                dressingRoom: 'Бесплатно 2 часа',
                dateSet: false,
                dateInput: '',
                amount: 0,
                
            },

            created() {
                axios
                    .get('/order/photozones')
                    .then(response => (this.photozones = response.data));
                 axios
                    .get('/order/options')
                    .then(response => (this.options = response.data))
                 axios
                    .get('/order/intervals')
                    .then(response => (this.intervals = response.data));
            },
            methods: {
                getBusyIntervals(){
                    let self = this;
                     this.busyIntervals = [];
                     this.dateSet = false;
                    let datepickerVal = $('.datepicker').val();
                    if( datepickerVal !== ''){
                         this.dateInput = $('.datepicker').val();
                         this.dateSet = true;
                         
                         axios
                    .get('/calendar/busy-intervals?date=' + this.dateInput + '&productId=' + this.photozones[this.photozoneSelect]['product_id'])
                    .then(function(response){
                          $.each(response.data, function(index,elem) {   
                              self.busyIntervals.push(elem['interval']);

                         });
                    });
                    } 
                },
                checkTimeStatus(item) {
                        for (let time of this.busyIntervals) {
                          if (time == item['code']) {
                            return false;
                          }
                        }
                        return true;
                    },
                    chooseInterval(interval){
                        console.log(this.options);
                                this.amount = this.selectIntervals.length  * this.photozones[this.photozoneSelect]['price'];
                        },
                setOption(key){
                  
                }
            },
            filters: {
                currency(value) {
                    return value.toFixed(0);
                }
            }
        });

     
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MM-YYYY',
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
