<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/12/19
 * Time: 3:25 AM
 */

namespace api\modules\v1\controllers;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\AccessControl;
class ApiController extends ActiveController
{


    public function behaviors(){

        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        $behaviors['corsFilter' ] = [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Request-Method' => ['GET','POST', 'HEAD', 'OPTIONS'],
                ],
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['get','create', 'update', 'delete'],
            'rules' => [
                [
                    'actions' => ['get','create', 'update', 'delete'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }


    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],

        ];
    }

}