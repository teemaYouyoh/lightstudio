<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "option_group".
 *
 * @property int $option_group_id
 * @property string $title
 */
class OptionGroup extends \yii\db\ActiveRecord
{
    public $title;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'option_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort_order'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function optionLabels()
    {
        return [
            'option_group_id' => 'option Group ID',
            'sort_order' => 'Порядок',
            'title' => 'Название',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->title = $this->optionGroupDescription->title;
    }


    public function afterSave($insert, $changedoptions)
    {
        parent::afterSave($insert, $changedoptions);

        if($insert){
            $optionGroupDescription = new OptionGroupDescription();
            $optionGroupDescription->option_group_id = $this->option_group_id;
            $optionGroupDescription->title = $this->title;
            $optionGroupDescription->save();
        } else {
            $isGroup = OptionGroupDescription::findOne(['option_group_id' => $this->option_group_id]);
            $isGroup->option_group_id = $this->option_group_id;
            $isGroup->title = $this->title;
            $isGroup->save();
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        OptionGroupDescription::deleteAll(['option_group_id' => $this->option_group_id]);
    }


    public static function loadOptionGroups(){
        return self::find()->all();
    }

    public function getOptionGroupDescription(){
        return $this->hasOne(OptionGroupDescription::className(), ['option_group_id' => 'option_group_id']);
    }
}
