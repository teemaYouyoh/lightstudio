<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "attribute_description".
 *
 * @property int $attribute_id
 * @property string $title
 */
class AttributeDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_id'], 'required'],
            [['attribute_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['attribute_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => 'Attribute ID',
            'title' => 'Title',
        ];
    }
}
