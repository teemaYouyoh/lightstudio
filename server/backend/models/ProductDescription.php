<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_description".
 *
 * @property int $product_id
 * @property string $title
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
class ProductDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description','meta_title','meta_description','meta_keywords'], 'string'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'title' => 'Title',
            'description' => 'Description',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords'
        ];
    }
}
