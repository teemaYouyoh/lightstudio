<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/12/19
 * Time: 2:50 AM
 */

namespace api\modules\v1\controllers;

use api\helpers\ConstantHelper;
use Yii;

use backend\models\Product;
use yii\data\ActiveDataProvider;


class ApiProductController extends ApiController
{
    const PHOTO_ZONES = 1;
    const DRESSES = 2;
    const EQUIPMENT = 11;
    const SERVICES = 12;


    public $modelClass = 'backend\models\Product';
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


//    public function prepareDataProvider()
//    {
//        $searchModel = new ProductSearch();
//        return $searchModel->search(Yii::$app->request->queryParams);
//    }

    public function actionIndex(){
        $product = Product::find()->where(['status'=> ConstantHelper::STATUS_ACTIVE])->all();
        return $product;
    }


    public function actionPhotozones(){
        $photozones = self::PHOTO_ZONES;

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()
                ->where(['status'=> ConstantHelper::STATUS_ACTIVE])
                ->joinWith([
                    'productCategory' => function (\yii\db\ActiveQuery $query) use ($photozones){
                        $query->andWhere(['category_id' =>$photozones]);
                    }])
        ]);

        return $dataProvider;
    }

    public function actionProduct($id){

        return Product::find()
            ->where(['status'=> ConstantHelper::STATUS_ACTIVE,'product_id' => $id])
            ->one();
    }


    public function actionDresses(){
        $dresses = self::DRESSES;

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()
                ->where(['status'=> ConstantHelper::STATUS_ACTIVE])
                ->joinWith([
                    'productCategory' => function (\yii\db\ActiveQuery $query) use ($dresses){
                        $query->andWhere(['category_id' =>$dresses]);
                    }])
        ]);

        return $dataProvider;
    }



    public function actionEquipments(){

        $category = self::EQUIPMENT;

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()
                ->where(['status'=> ConstantHelper::STATUS_ACTIVE])
                ->joinWith([
                    'productCategory' => function (\yii\db\ActiveQuery $query) use ($category){
                        $query->andWhere(['category_id' => $category]);
                    }])
        ]);

        return $dataProvider;
    }



    public function actionServices(){
        $category = self::SERVICES;
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()
                ->where(['status'=> ConstantHelper::STATUS_ACTIVE])
                ->joinWith([
                    'productCategory' => function (\yii\db\ActiveQuery $query) use ($category){
                        $query->andWhere(['category_id' => $category]);
                    }])
        ]);

        return $dataProvider;
    }

}





