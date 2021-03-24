<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use yii\web\Controller;
use backend\models\Page;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Class PageController
 * @package backend\controllers
 */
class PageController extends Controller
{


    public function actionIndex(){

        $pages = Page::loadPages();

        return $this->render('index',[
            'pages' => $pages,
        ]);

        return $this->render('index',[

        ]);
    }

    public function actionCreate(){

        $model = new Page();


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
            return $response->data = ['code'=> '200','message' => 'Attribute ' . $id . ' deleted', 'data' => []];
        } else {
            return $response->data = ['code'=> '403','message' => 'Attribute ' . $id . ' not deleted', 'data' => []];
        }

    }

    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}