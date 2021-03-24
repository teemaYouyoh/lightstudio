<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/20/19
 * Time: 11:17 PM
 */

namespace backend\controllers;

use backend\models\Attribute;
use backend\models\Category;
use backend\models\Product;
use backend\models\ProductVideo;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use backend\models\ProductImage;
use yii\web\UploadedFile;
use backend\models\UploadForm;

/**
 * Class ProductController
 * @package backend\controllers
 */
class ProductController extends Controller
{


    public function actionIndex(){

        $products = Product::loadProducts();


        return $this->render('index',[
            'products' => $products
        ]);
    }

    public function actionCreate(){

        $model = new Product();
        $categories = Category::getCategoriesList();
        $attributes = Attribute::loadAttributeDescriptionsList();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create',[
            'model' => $model,
            'categories' => $categories,
            'attributes' => $attributes,
        ]);
    }

    public function actionUpdate($id){

        $model = $this->findModel($id);
        $categories = Category::getCategoriesList();
        $attributes = Attribute::loadAttributeDescriptionsList();


    if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);
        }


 

        return $this->render('create',[
            'model' => $model,
            'categories' => $categories,
            'attributes' => $attributes,
        ]);

    }

    public function actionDelete(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $response = Yii::$app->response;

        if($this->findModel($id)->delete()){
            return $response->data = ['code'=> '200','message' => 'Product ' . $id . ' deleted', 'data' => []];
        } else {
            return $response->data = ['code'=> '403','message' => 'Product ' . $id . ' not deleted', 'data' => []];
        }


    }

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionAddProductVideo(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = Yii::$app->request->post('product_id');
        $value = Yii::$app->request->post('video_value');

        if(empty($productId) || empty($value)){
            return 'Нет данных!';
        }

            $productAttribute = new ProductVideo();
            $productAttribute->product_id = $productId;
            $productAttribute->video = $value;
            $productAttribute->save();

        return $productAttribute;
    }

    public function actionDeleteProductVideo(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = Yii::$app->request->post('product_id');
        $video_id = Yii::$app->request->post('product_video_id');

        if(empty($video_id) || empty($productId)){
            return 'Нет данных!';
        }

        $delete = ProductVideo::deleteAll(['product_id' => $productId,'product_video_id' => $video_id]);

        if($delete){
            return 'Видео удалено';
        } else {
            return 'Ошибка';
        }
    }


    public function actionDeleteProductImage(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $productId = Yii::$app->request->post('product_id');
        $image_id = Yii::$app->request->post('product_image_id');

        if(empty($image_id) || empty($productId)){
            return 'Нет данных!';
        }

        $delete = ProductImage::deleteAll(['product_id' => $productId,'product_image_id' => $image_id]);

        if($delete){
            return 'Изображение удалено';
        } else {
            return 'Ошибка';
        }
    }


    public function actionUpdateSortProductImage(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $sort = Yii::$app->request->post('sortOrder');
        $imageId = Yii::$app->request->post('imageId');

        $image = ProductImage::findOne(['product_image_id' => $imageId]);
        $image->sort_order = $sort;

        if($image->save()){
            return 'Сортировка обновлена';
        } else {
            return 'Ошибка';
        }
    }


public function actionUpload(){

    \Yii::$app->response->format = Response::FORMAT_JSON;

    $productId = Yii::$app->request->post('productId');

    $uploadFileDir =  Yii::getAlias('@backend') .'/web/uploads/image/photozones/';


    $files = $_FILES['images'];

    $count = 0;


    for ($i=0;  $i < count($files['name']); $i++) { 
           
    $fileTmpPath = $files['tmp_name'][$i];
    $fileName = $files['name'][$i];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));


    $allowedfileExtensions = array('jpg', 'png');

    $dest_path = $uploadFileDir . $fileName;

    if (in_array($fileExtension, $allowedfileExtensions)) {
      if( move_uploaded_file($fileTmpPath, $dest_path)){
        $productImage = new ProductImage();
        $productImage->image = '/uploads/image/photozones/' . $fileName;
        $productImage->product_id = $productId;  
        $productImage->save();
      }
    }
 }

}


}