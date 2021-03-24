<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $payment_method_id
 * @property string $title
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_method_id' => 'ID',
            'title' => 'Title',
        ];
    }



    public static function getMethods() {
        return self::find()->all();
    }
}
