<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "interval".
 *
 * @property int $interval_id
 * @property string $title
 */
class Interval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'interval_id' => 'Interval ID',
            'title' => 'Title',
        ];
    }


    public static function getIntervals() {

        return self::find()->all();
    }
}
