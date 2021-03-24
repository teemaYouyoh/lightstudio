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
use backend\models\Category;
use yii\web\NotFoundHttpException;
use yii\web\Response;
/**
 * Class CategoryController
 * @package backend\controllers
 */
class CategoryController extends Controller
{
    public function actionIndex(){

        $categories = Category::getCategories();

       return $this->render('index',[
           'categories' => $categories,
       ]);
    }

    public function actionCreate(){

        $model = new Category();
        $categories = Category::getCategoriesList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create',[
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    public function actionUpdate($id){

        $model = $this->findModel($id);
        $categories = Category::getCategoriesList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update',[
            'model' => $model,
            'categories' => $categories,
        ]);
    }


    public function actionDelete(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $response = Yii::$app->response;

        if($this->findModel($id)->delete()){
            return $response->data = ['code'=> '200','message' => 'Category ' . $id . ' deleted', 'data' => []];
        } else {
            return $response->data = ['code'=> '403','message' => 'Category ' . $id . ' not deleted', 'data' => []];
        }
    }


    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}