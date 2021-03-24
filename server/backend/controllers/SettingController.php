<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Setting;
use yii\web\UploadedFile;
/**
 * Class SettingController
 * @package backend\controllers
 */
class SettingController extends Controller
{


    public function actionIndex(){

       $model = Setting::getSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             $model->logo = Setting::upload($model, 'logo');
            return $this->redirect(['index']);
        }

        return $this->render('index',[
            'model' => $model
        ]);
    }





    public function actionCreate(){


        return $this->render('create');
    }

    public function actionUpdate(){

        $model = Setting::getSettings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           // $model->logo = Setting::getInstance($model, 'logo');
            return $this->redirect(['index']);
        }

        return $this->render('update');
    }

    public function actionDelete(){



    }
}