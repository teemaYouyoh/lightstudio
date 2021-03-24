<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "status_order".
 *
 * @property int $id
 * @property string $title
 * @property string $code
 */
class StatusOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'code' => 'Code',
        ];
    }

    public static function getStatuses(){
        return self::find()->all();
    }
}
