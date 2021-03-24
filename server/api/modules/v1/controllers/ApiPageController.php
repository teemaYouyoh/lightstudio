<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/12/19
 * Time: 2:50 AM
 */

namespace api\modules\v1\controllers;


use api\helpers\ConstantHelper;
use backend\models\Page;
use yii\data\ActiveDataProvider;

class ApiPageController extends ApiController
{
    public $modelClass = 'backend\models\Page';
    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'data',
    ];


    public function actions()
    {
        $actions =  parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }


    public function prepareDataProvider(){
        return new ActiveDataProvider([
            'query' => Page::find()
        ]);
    }





}





