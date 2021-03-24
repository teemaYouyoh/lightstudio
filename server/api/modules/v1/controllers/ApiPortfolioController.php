<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/17/19
 * Time: 2:32 AM
 */

namespace api\modules\v1\controllers;
use backend\models\Portfolio;
use Yii;
use yii\data\ActiveDataProvider;

class ApiPortfolioController extends ApiController
{

    public $modelClass = 'backend\models\Portfolio';
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
                'query' => Portfolio::find(),
                'pagination' => false,

            ]);
            return $dataProvider;
    }

}