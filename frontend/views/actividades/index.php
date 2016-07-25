<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actividades-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    //for popup create window
    modal::begin([
        'id' => 'modal',
        'size' => Modal::SIZE_LARGE,
    ]);
    echo "<div id='modalContent'></div>";
    modal::end();
    ?>


    <?=
    yii2fullcalendar\yii2fullcalendar::widget(array(
        'events' => $events,
        'options' => [
            'lang' => 'es',
        ],
        'header' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'month,basicWeek,agendaDay'
        ],
        'clientOptions' => [
            'eventLimit' => true,
            'minTime' => '08:00:00',
            'maxTime' => '17:00:00',
            'weekends' => false, // sab and dom
        ],
    ));
    ?>
    <?php
    $this->registerJs('
        $(\'.modal-lg\').css(\'width\', \'80%\');
        
$(function(){
    
        //var moment = $(\'#calendar\').fullCalendar(\'getDate\');
    //alert("The current date of the calendar is " + moment);
    
    $(document).on(\'click\',\'.fc-day\',function(){
        var moment = $(\'#calendar\').fullCalendar(\'getDate\');
        
            
            var date= $(this).attr(\'data-date\');            
            $.get(\'index.php?r=actividades/create\',{\'date\':date,\'pid\':'.isset($_GET['pid'])?'':$_GET['pid'].'}, function(data){ 
                
             $(\'#modal\').modal(\'show\')
                .find(\'#modalContent\')
                .html(data);               
            });
    });

 
});');
    ?>

</div>
