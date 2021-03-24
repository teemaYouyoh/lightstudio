<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "attribute_group_description".
 *
 * @property int $attribute_group_id
 * @property string $title
 */
class AttributeGroupDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute_group_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_group_id'], 'required'],
            [['attribute_group_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['attribute_group_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute_group_id' => 'Attribute Group ID',
            'title' => 'Title',
        ];
    }
}
