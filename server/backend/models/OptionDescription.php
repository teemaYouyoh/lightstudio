<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "option_description".
 *
 * @property int $option_id
 * @property string $value
 */
class OptionDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'option_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_id'], 'required'],
            [['option_id'], 'integer'],
            [['value'], 'string', 'max' => 100],
            [['option_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function optionLabels()
    {
        return [
            'option_id' => 'option ID',
            'value' => 'Title',
        ];
    }
}
