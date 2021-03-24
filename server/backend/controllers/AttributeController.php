<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use backend\models\ProductAttribute;
use yii\web\Controller;
use backend\models\Attribute;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Class AttributeController
 * @package backend\controllers
 */
class AttributeController extends Controller
{

    public function actionIndex(){

        $attributes = Attribute::loadAttributes();

        return $this->render('index',[
            'attributes' => $attributes,
        ]);
    }

    public function actionCreate(){

        $model = new Attribute();
        $attributeGroups = Attribute::loadAttributeGroupDescriptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create',[
            'model' => $model,
            'attributeGroups' => $attributeGroups,
        ]);
    }

    public function actionUpdate($id){

        $model = $this->findModel($id);
        $attributeGroups = Attribute::loadAttributeGroupDescriptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update',[
            'model' => $model,
            'attributeGroups' => $attributeGroups,
        ]);

    }

    public function actionDelete(){

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $response = Yii::$app->response;

        if($this->findModel($id)->delete()){
            return $response->data = ['code'=> '200','message' => 'Attribute ' . $id . ' deleted', 'data' => []];
        } else {
            return $response->data = ['code'=> '403','message' => 'Attribute ' . $id . ' not deleted', 'data' => []];
        }

    }

    protected function findModel($id)
    {
        if (($model = Attribute::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionAddProductAttribute(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $attributeId = Yii::$app->request->post('attribute');
        $productId = Yii::$app->request->post('product_id');
        $value = Yii::$app->request->post('attribute_value');

        if(empty($attributeId) || empty($productId) || empty($value)){
            return 'Нет данных!';
        }

        $productAttribute = ProductAttribute::find()
            ->where(['product_id' => Yii::$app->request->post('product_id')])
            ->andWhere(['attribute_id' => Yii::$app->request->post('attribute')])
            ->asArray()
            ->one();

        if(!$productAttribute){
            $productAttribute = new ProductAttribute();
            $productAttribute->attribute_id = $attributeId;
            $productAttribute->value = $value;
            $productAttribute->product_id = $productId;
            $productAttribute->save();
        } else {
            return 'Существует!';
        }

        return $productAttribute;
    }

    public function actionDeleteProductAttribute(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = Yii::$app->request->post('product_id');
        $attributeId = Yii::$app->request->post('attribute_id');

        if(empty($attributeId) || empty($productId)){
            return 'Нет данных!';
        }

        $delete = ProductAttribute::deleteAll(['product_id' => $productId,'attribute_id' => $attributeId]);

        if($delete){
             return 'Атрибут удален';
        } else {
            return 'Ошибка';
        }
    }


}