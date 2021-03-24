<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;



use backend\models\Interval;
use backend\models\Order;
use backend\models\OrderInterval;
use backend\models\OrderSearch;
use backend\models\Product;
use backend\models\Option;
use backend\models\StatusOrder;
use Yii;
use yii\web\Controller;


/**
 * Class OrderController
 * @package backend\controllers
 */
class OrderController extends Controller
{

    public function actionIndex(){

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());


        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionCreate(){

        $photozones = Product::getPhotozones();
        $options = Option::getOptions();
        $intervals = Interval::getIntervals();
        $statuses = StatusOrder::getStatuses();


        return $this->render('create', [
            'photozones' => $photozones,
            'options' => $options,
            'intervals' => $intervals,
            'statuses' => $statuses,
        ]);
    }



    public function actionUpdate($id){

    }


    public function actionDelete(){

    }


    public function actionPhotozones(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Product::getPhotozones();
    }


    public function actionOptions(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Option::find()
        ->joinWith(['optionDescription'])
         ->joinWith(['optionGroup'])
        ->asArray()
        ->all()
        ;

    }

    public function actionIntervals(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Interval::find()->asArray()->all();

    }

    public function actionStatuses(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return StatusOrder::find()->asArray()->all();
    }

    public function actionBusyIntervals(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $date = Order::preparedDate(Yii::$app->request->get('date'));
        $productId = Yii::$app->request->get('productId');
        return OrderInterval::getBusyIntervals($date,$productId);
    }



    public function actionProductPriceAjax(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $productId = Yii::$app->request->get('productId');
        return Product::getPrice($productId);
    }

    public function actionOptionPriceAjax(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $optionId = Yii::$app->request->get('optionId');
        return Option::getPrice($optionId);
    }

}