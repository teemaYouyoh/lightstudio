<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 2/19/19
 * Time: 6:21 PM
 */

namespace api\modules\v1\controllers;
use backend\models\OrderInterval;
use yii\data\ActiveDataProvider;

class ApiIntervalController extends ApiController
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
            'query' => OrderInterval::find()->where(['status' => 1])
        ]);

        return $dataProvider;
    }


    public function actionIntervalsOfDate($date,$productId){

        $intervals = OrderInterval::find()
            ->where(['date' => $date,'product_id' => $productId,'status' => 1])
            ->select(['interval'])
            ->all();

        return $intervals;


    }


}