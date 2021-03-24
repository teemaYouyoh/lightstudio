<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 2/19/19
 * Time: 6:21 PM
 */

namespace api\modules\v1\controllers;
use backend\models\Interval;
use backend\models\Order;
use backend\models\OrderInterval;
use backend\models\OrderItem;
use backend\models\ProductDescription;
use yii\data\ActiveDataProvider;
use Yii;

class ApiCalendarController extends ApiController
{
    public $modelClass = 'backend\models\OrderInterval';
    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'data',
    ];



    public function actions()
    {
        $actions =  parent::actions();
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['index'],$actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }


    public function actionIndex(){

        $dataProvider = new ActiveDataProvider([
            'query' => OrderInterval::find()
        ]);

        return $dataProvider;
    }



    public function actionCalendar(){
        $data = [];

        $order = Order::findOne(['order_id' => 1]);
        $date = Yii::$app->formatter->asDate($order->date_due, 'yyyy-MM-dd');

        foreach ($order->intervals as $interval){
           $title =  Interval::findOne(['code' => $interval->interval])->title;
            $dateStart = substr($title,0,5);
            $dateEnd = substr($title,6,5);
        }

    }



}