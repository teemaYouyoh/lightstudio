<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_option".
 *
 * @property int $id
 * @property int $option_id
 * @property int $order_id
 */
class OrderOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_id','order_id'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'option_id' => 'Option ID',
        ];
    }
}
