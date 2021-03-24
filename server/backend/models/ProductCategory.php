<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_category".
 *
 * @property int $product_id
 * @property int $category_id
 */
class ProductCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id'], 'required'],
            [['product_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
        ];
    }


    public function getCategoryDescription(){
        return $this->hasOne(CategoryDescription::className(), ['category_id' => 'category_id']);
    }
}
