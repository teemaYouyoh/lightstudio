<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use backend\models\AttributeGroup;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;

/**
 * Class AttributeController
 * @package backend\controllers
 */
class AttributeGroupController extends Controller
{


    public function actionIndex(){

       $attributeGroups = AttributeGroup::loadAttributeGroups();

        return $this->render('index',[
            'attributeGroups' => $attributeGroups,
        ]);
    }

    public function actionCreate(){

        $model = new AttributeGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }


        return $this->render('create',[
            'model' => $model,
        ]);
    }

    public function actionUpdate($id){

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update',[
            'model' => $model,
        ]);

    }

    public function actionDelete(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $response = Yii::$app->response;

        if($this->findModel($id)->delete()){
            return $response->data = ['code'=> '200','message' => 'Group ' . $id . ' deleted', 'data' => []];
        } else {
            return $response->data = ['code'=> '403','message' => 'Group ' . $id . ' not deleted', 'data' => []];
        }

    }

    protected function findModel($id)
    {
        if (($model = AttributeGroup::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}