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
   let moment = $('#calendar').fullCalendar('getDate');
   
  $('#calendar').fullCalendar({
  locale: 'ru',
  defaultView: 'agendaDay',
 
  height: 700,
  contentHeight: 700,
  header: {
    left: 'prev,next today',
    center: 'title',
    right: 'month,agendaWeek,agendaDay,listWeek'
  },
   //   events: {
   //    url: "https://studio2.zigzag.team/v1/calendar?month=" + moment.format('MM'),
   //    
   // },
     eventSources: [
    {
      url: "https://studio2.zigzag.team/v1/calendar",
      type: 'get',
      error: function() {
        alert('Что то пошло не так =( ');
      },
    }
  ]
  });
  
  $('.fc-next-button').click(function() {
        let moment = $('#calendar').fullCalendar('getDate');
        console.log(moment);
        // alert("The current date of the calendar is " + moment.format('MM'));
    });


});

JS;
$this->registerJS($script, View::POS_READY); ?>

<style>
    .fc-button {
        cursor: pointer;
    }
</style>

