<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_video".
 *
 * @property int $product_video_id
 * @property int $product_id
 * @property string $video
 * @property int $sort_order
 */
class ProductVideo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_video';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'sort_order'], 'integer'],
            [['video'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_video_id' => 'Product Image ID',
            'product_id' => 'Product ID',
            'video' => 'Видео',
            'sort_order' => 'Sort Order',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(),['product_id' => 'product_id']);
    }
}
