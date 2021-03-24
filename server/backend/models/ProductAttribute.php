<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_attribute".
 *
 * @property int $product_id
 * @property int $attribute_id
 * @property string $value
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'attribute_id'], 'integer'],
            [['value'], 'string', 'max' => 100],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['attribute_title'] = function ($model) {
            return $model->attributeDescription->title;
        };


        return $fields;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'attribute_id' => 'Attribute ID',
            'value' => 'Value',
        ];
    }

    public function getAttributeDescription()
    {
        return $this->hasOne(AttributeDescription::className(),['attribute_id' => 'attribute_id']);
    }


}
