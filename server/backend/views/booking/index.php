<?php

use yii\web\View;
use yii\helpers\Html;
$this->title = 'Управление бронированием';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card" id="order">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title"><?= Html::encode($this->title)?></h4>
            </div>
            <div class="form-group">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="photozone" class="required">Локация</label>
                        <select @change="busyIntervals()" class="form-control" v-model="photozoneSelect">
                            <option   v-for=" (photozone, key) in photozones" :value="key">{{ photozone.productDescription.title }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6">
                    <?= Html::label('Дата', 'date',['class' => 'required']) ?>
                    <?= Html::input('text', 'date','', [
                        'class' => 'form-control datepicker',
                        'v-model' => 'dateInput',
                        '@focusout' => 'busyIntervals()',
                        '@change' => 'busyIntervals()',
                    ]) ?>
                </div>
            </div>
            <div v-if="dateSet" class="form-group">
                <div class="col-lg-6 d-flex flex-column">
                    <h4>Время съмки</h4>
                    <div class="form-check form-check-inline"  v-for=" (interval, key) in intervals">
                        <input @change="changeTime(key)" v-model="selectIntervals" type="checkbox" :value="key"  :id="interval.code">
                        <span class="ml-3">{{ interval.title }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $script = <<<JS
    
$(function() {
    
     let app = new Vue({
            el: '#order',
            data: {
                photozones: [] ,
                intervals: [] ,
                photozoneSelect: 0,
                selectIntervals: [],
                dateSet: false,
                dateInput: '',
            },
            mounted() {
                axios
                    .get('/calendar/photozones')
                    .then(response => (this.photozones = response.data));
                 axios
                    .get('/calendar/intervals')
                    .then(response => (this.intervals = response.data));
            },
            methods: {
                    showMessage(from, align, message,icon,color){
            
        // swal({
        //     title: title,
        //     text: message,
        //     type: type,
        //     showCancelButton: false,
        //     confirmButtonText: 'Ok',
        //     confirmButtonClass: "btn btn-success",
        //     buttonsStyling: false
        // });
        
         $.notify({
      icon:  icon,
      message: message

    }, {
      type: color,
      timer: 3000,
      placement: {
        from: from,
        align: align
      }
    });
  
                    },
                      busyIntervals(){
                    let self = this;
                     this.selectIntervals = [];
                     this.dateSet = false;
                    let datepickerVal = $('.datepicker').val();
                    if( datepickerVal !== ''){
                         this.dateInput = $('.datepicker').val();
                         this.dateSet = true;
                         
                         axios
                    .get('/calendar/busy-intervals?date=' + this.dateInput + '&productId=' + this.photozones[this.photozoneSelect]['product_id'])
                    .then(function(response){
                          $.each(response.data, function(index,elem) {   
                              self.selectIntervals.push(elem['interval']);
                            // console.log(elem);
                         });
                    });
                    } 
                },
                
                changeTime(key){
                    let datepickerVal = $('.datepicker').val();
                    let remove = 0;
                    if(Object.values(this.selectIntervals).indexOf(key) >= 0){
                        remove = 0;
                    } else {
                        remove = 1;
                    }
                    
                    let vm = this;
                    if(remove){
                              swal({
            title: 'Вы уверены?',
            text: 'Вы откроете слот ' +  vm.intervals[key]['title'] + ' который уже занят!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Да',
            cancelButtonText: 'Нет',
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            buttonsStyling: false
        }).then(function() {
                    axios
                    .get('/calendar/change-time', {
                        params: {
                            date: datepickerVal,
                            productId: vm.photozones[vm.photozoneSelect]['product_id'],
                            interval: vm.intervals[key]['code'],
                            remove: remove,
                        }
                    })
                     .then(response => ( vm.showMessage('top','center','Сохранено','done','success')))
                   .catch(function(error){
                             vm.showMessage('top','center',error.response.data,'warning','danger');
                        })
                    ;
        }, function(dismiss) {
            // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            if (dismiss === 'cancel') {
                
                swal({
                    title: 'Отмена',
                    text: 'Вы отменили действие:)',
                    type: 'error',
                    confirmButtonClass: "btn btn-info",
                    buttonsStyling: false
                }).catch(swal.noop)
                
                vm.busyIntervals();
            }
        })
           } else {
                        let vm = this;
             axios
                .get('/calendar/change-time', {
                        params: {
                            date: datepickerVal,
                            productId: this.photozones[this.photozoneSelect]['product_id'],
                            interval: this.intervals[key]['code'],
                            remove: remove,
                        }
                    })
                    .then(response => ( vm.showMessage('top','center','Сохранено','done','success')))
                      // .catch(error => (vm.showMessage('Произошла ошибка!',error.response.data,'error')))
                        .catch(function(error){
                         vm.showMessage('top','center',error.response.data,'warning','danger');
                        })
                    ;
                    }
                }
            },
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


});

JS;
$this->registerJS($script, View::POS_READY); ?>



