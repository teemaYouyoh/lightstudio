<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liqpay".
 *
 * @property string $public_key
 * @property string $private_key
 */
class Liqpay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liqpay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['public_key', 'private_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'public_key' => 'Public Key',
            'private_key' => 'Private Key',
        ];
    }


}
