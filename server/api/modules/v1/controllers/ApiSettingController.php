<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/17/19
 * Time: 2:32 AM
 */

namespace api\modules\v1\controllers;
use Yii;
use backend\models\Setting;
use yii\data\ActiveDataProvider;

class ApiSettingController extends ApiController
{

    public $modelClass = 'backend\models\Setting';
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
                'query' => Setting::find()
            ]);

            return $dataProvider;
    }

}