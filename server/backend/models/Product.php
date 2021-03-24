<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "product".
 *
 * @property int $product_id
 * @property string $sku
 * @property string $image
 * @property int $price
 * @property int $sort_order
 * @property int $status
 * @property string video
 *
 */
class Product extends \yii\db\ActiveRecord
{

    public $title;
    public $description;
    public $category;
    public $category_id;
    public $attribute;
    public $attribute_value;
    public $video_value;
    public $image_value;
    public $attributes;
    public $images;
    public $videos;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'sort_order', 'status'], 'integer'],
            [['sku'], 'string', 'max' => 10],
            [['image','video'], 'string', 'max' => 255],
            [['title', 'category',
                'description', 'attribute',
                'attribute_value','attributes','video_value',
                'images','videos','image_value'
             ], 'safe'
            ],
            [['meta_title','meta_description','meta_keywords'], 'string'],
            [['title','category_id','sku','price'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'sku' => 'Артикул',
            'image' => 'Изображание',
            'video' => 'Видеофон',
            'price' => 'Цена',
            'sort_order' => 'Порядок',
            'status' => 'Статус',
            'title' => 'Название',
            'description' => 'Описание',
            'category' => 'Категория',
            'category_id' => 'Категория',
            'attribute' => 'Атрибут',
            'attributes' => 'Атрибуты',
            'attribute_value' => 'Значение',
            'video_value' => 'Ссылка на видео',
            'image_value' => 'Изображение для галереи',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords'
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['status']);

        $fields['description'] = function ($model) {

                return $model->productDescription->description;
            };

        $fields['title'] = function ($model) {
            return $model->productDescription->title;
        };

        $fields['images'] = function ($model) {

            return $model->productImages;
    };

        $fields['videos'] = function ($model) {

            return $model->productVideos;

        };

        $fields['meta_title'] = function ($model) {
            return $model->productDescription->meta_title;
        };

        $fields['meta_description'] = function ($model) {
            return $model->productDescription->meta_description;
        };

        $fields['meta_keywords'] = function ($model) {
            return $model->productDescription->meta_keywords;
        };

        $fields['attributes'] = function ($model) {
            $attributes = $this->productAttributes;
            return $attributes;
        };

        return $fields;
    }


    public static function loadProducts(){

        return self::find()->all();

    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $category = new ProductCategory();
            $category->category_id = $this->category_id;
            $category->product_id = $this->product_id;
            $category->save( );

            $productDescription = new ProductDescription();
            $productDescription->product_id = $this->product_id;
            $productDescription->title = $this->title;
            $productDescription->description = $this->description;
            $productDescription->meta_title = $this->meta_title;
            $productDescription->meta_description = $this->meta_description;
            $productDescription->meta_keywords = $this->meta_keywords;
            $productDescription->save();

        } else {
            $category = ProductCategory::findOne(['product_id' => $this->product_id]);
            $category->category_id = $this->category_id;
            $category->save();

            $productDescription = ProductDescription::findOne(['product_id' => $this->product_id]);
            $productDescription->title = $this->title;
            $productDescription->description = $this->description;
            $productDescription->meta_title = $this->meta_title;
            $productDescription->meta_description = $this->meta_description;
            $productDescription->meta_keywords = $this->meta_keywords;
            $productDescription->save();
        }

    }

    public function afterDelete()
    {
        parent::afterDelete();

        ProductCategory::deleteAll(['product_id' => $this->product_id]);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->title = $this->productDescription->title;
        $this->description = $this->productDescription->description;
        $this->category = $this->productCategory->categoryDescription->title;
        $this->category_id = $this->productCategory->category_id;
        $this->attributes = $this->productAttributes;
        $this->images = $this->productImages;
        $this->videos = $this->productVideos;
        $this->meta_title = $this->productDescription->meta_title;
        $this->meta_description = $this->productDescription->meta_description;
        $this->meta_keywords = $this->productDescription->meta_keywords;
    }


    public static function getProducts() {
        return self::find()->all();
    }

    public static function getPhotozones() {
        return self::find()
            ->joinWith(['productCategory','productDescription'])
            ->where(['category_id' => 1])
            ->asArray()
            ->all();
    }

    public static function getPrice($productId){
        return self::findOne(['product_id' => $productId]);
    }


    public function getProductDescription(){
        return $this->hasOne(ProductDescription::className(), ['product_id' => 'product_id']);
    }

    public function getProductCategory(){
        return $this->hasOne(ProductCategory::className(), ['product_id' => 'product_id']);
    }

    public function getProductAttributes(){
        return $this->hasMany(ProductAttribute::className(), ['product_id' => 'product_id']);
    }

    public function getProductImages(){
        return $this->hasMany(ProductImage::className(),['product_id' => 'product_id'])->orderBy(['sort_order' => SORT_ASC]);
    }

    public function getProductVideos(){
        return $this->hasMany(ProductVideo::className(),['product_id' => 'product_id']);
    }

}
