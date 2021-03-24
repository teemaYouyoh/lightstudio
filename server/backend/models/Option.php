<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "option".
 *
 * @property int $option_id
 * @property int $option_group_id
 * @property int $sort_order
 */
class Option extends \yii\db\ActiveRecord
{
    public $value;
    public $group;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_group_id','value'], 'required'],
            [['option_group_id', 'sort_order'], 'integer'],
            [['value','group'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function optionLabels()
    {
        return [
            'option_id' => 'Option ID',
            'option_group_id' => 'Option Group ID',
            'sort_order' => 'Порядок',
            'group' => ' Группа',
            'value' => 'Значение',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->value = $this->optionDescription->value;
        $this->group = $this->optionGroup->optionGroupDescription->title;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        OptionDescription::deleteAll(['option_id' => $this->option_id]);
    }

    public function afterSave($insert, $changedoptions)
    {
        parent::afterSave($insert, $changedoptions);

        if($insert){
            $optionDescription = new OptionDescription();
            $optionDescription->option_id = $this->option_id;
            $optionDescription->value = $this->value;
            $optionDescription->save();
        } else {
            $optionDescription = OptionDescription::findOne(['option_id' => $this->option_id]);
            $optionDescription->value = $this->value;
            $optionDescription->save();
        }
    }


    public static function getOptions(){
        return self::find()->all();
    }

    public static function getPrice($optionId){
        return self::findOne(['option_id' => $optionId]);
    }

    public static function loadOptionGroupDescriptions(){
        return OptionGroupDescription::find()->all();
    }

    public static function loadOptionDescriptionsList(){
        return OptionDescription::find()->all();
    }


    public function getOptionDescription(){
        return $this->hasOne(OptionDescription::className(), ['option_id' => 'option_id']);
    }

    public function getOptionGroup(){
        return $this->hasOne(OptionGroup::className(), ['option_group_id' => 'option_group_id']);
    }





}
