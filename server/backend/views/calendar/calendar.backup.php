<?php


use yii\web\View;
use yii\helpers\Html;

$this->title = 'Календарь';
?>

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
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>



<?php $script = <<<JS
    
$(function() {

  $('#calendar').fullCalendar({
   events: [
    {
      title  : '08:00-09:00',
      start  : '2019-04-02',
      end    : '2019-04-02'
    },
        {
      title  : '09:00-10:00',
      start  : '2019-04-02',
      end    : '2019-04-02'
    },
    {
      title  : '11:00-12:00',
      start  : '2019-04-02',
      end    : '2019-04-02'
    },
    {
      title  : 'event3',
      start  : '2019-04-02T12:30:00',
      allDay : false // will make the time show
    }
  ]
  });


});

JS;
$this->registerJS($script, View::POS_READY); ?>



