<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "option_group_description".
 *
 * @property int $option_group_id
 * @property string $title
 */
class OptionGroupDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'option_group_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_group_id'], 'required'],
            [['option_group_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['option_group_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function optionLabels()
    {
        return [
            'option_group_id' => 'option Group ID',
            'title' => 'Title',
        ];
    }
}
