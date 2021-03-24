<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use Yii;
use backend\models\Portfolio;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;
/**
 * Class PortfolioController
 * @package backend\controllers
 */
class PortfolioController extends Controller
{

    public function actionIndex(){

        $dataProvider = new ActiveDataProvider([
            'query' =>  Portfolio::find()
        ]);

        return $this->render('index',[
               'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate(){




        return $this->render('create',[

        ]);
    }

    public function actionUpdate($id){


        return $this->render('update',[

        ]);

    }

  
    public function actionDelete(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $image_id = Yii::$app->request->post('id');

        if(empty($image_id)){
            return 'Нет данных!';
        }

        $delete = Portfolio::deleteAll(['id' => $image_id]);

        if($delete){
            return 'Изображение удалено';
        } else {
            return 'Ошибка';
        }
    }

    public function actionUpload(){

    \Yii::$app->response->format = Response::FORMAT_JSON;

    $uploadFileDir =  Yii::getAlias('@backend') .'/web/uploads/image/portfolio/';


    $files = $_FILES['images'];

    $count = 0;


    for ($i=0;  $i < count($files['name']); $i++) { 
        $fileTmpPath = $files['tmp_name'][$i];
        $fileName = $files['name'][$i];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'png');

        $dest_path = $uploadFileDir . $fileName;

    if (in_array($fileExtension, $allowedfileExtensions) && move_uploaded_file($fileTmpPath, $dest_path)) {
        $portfolio = new Portfolio();
        $portfolio->image = '/uploads/image/portfolio/' . $fileName;
        $portfolio->save();
    }
 }

}


}