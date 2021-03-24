<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use yii\web\HttpException;
use backend\models\Interval;
use backend\models\Order;
use backend\models\OrderInterval;
use backend\models\Product;
use yii\web\Controller;
use Yii;

/**
 * Class CalendarController
 * @package backend\controllers
 */
class CalendarController extends Controller
{
    public function actionIndex(){
        
        return $this->render('index',[

        ]);
    }

    public function actionCreate(){

        return $this->render('create',[

        ]);
    }

    public function actionUpdate($id){

        return $this->render('update',[

        ]);

    }

    public function actionDelete(){

    }

    public function actionPhotozones(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Product::getPhotozones();
    }

    public function actionIntervals(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
           return Interval::find()->orderBy(['code' => SORT_ASC])->asArray()->all();

    }

    public function actionBusyIntervals(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $date = Order::preparedDate(\Yii::$app->request->get('date'));
        $productId = \Yii::$app->request->get('productId');
        return OrderInterval::getBusyIntervals($date,$productId);
    }


    public function actionChangeTime(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $date = Order::preparedDate(Yii::$app->request->get('date'));
        $productId = Yii::$app->request->get('productId');
        $interval = Yii::$app->request->get('interval');
        $remove = Yii::$app->request->get('remove');


        
      

        if(!$remove) {
            $orderInterval = new OrderInterval();
            $orderInterval->interval = $interval;
            $orderInterval->product_id = $productId;
            $orderInterval->date = $date;
            $orderInterval->status = 1;
            $orderInterval->order_id = 0;
            if(!$orderInterval->save()){
                Yii::$app->response->setStatusCode(400);
                Yii::error($orderInterval->errors, 'booking_error');
                return  'Ошибка добавления';
            }
             Yii::info('Интервал создан ID: ' . $orderInterval->id , 'booking_error');
            return $orderInterval;
        } else {

        $orderInterval =  OrderInterval::findOne([
            'date' => $date,
            'product_id' => $productId,
            'interval' => $interval,
            'status' => 1
        ]);
            $orderInterval->status = 0;
            if(!$orderInterval->save()) {
                Yii::$app->response->setStatusCode(400);
                Yii::error($orderInterval->errors, 'booking_error');
                return  'Ошибка добавления';
            }  else {
                  Yii::info('Интервал деактивирован ID: ' . $orderInterval->id , 'booking_error');
                return 'Деактивирован';
            }
        }
    }


}