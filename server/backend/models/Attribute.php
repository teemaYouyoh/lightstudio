<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attribute".
 *
 * @property int $attribute_id
 * @property int $attribute_group_id
 * @property int $sort_order
 */
class Attribute extends \yii\db\ActiveRecord
{
    public $title;
    public $group;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_group_id','title'], 'required'],
            [['attribute_group_id', 'sort_order'], 'integer'],
            [['title','group'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => 'Attribute ID',
            'attribute_group_id' => 'Attribute Group ID',
            'sort_order' => 'Порядок',
            'group' => ' Группа',
            'title' => 'Название',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->title = $this->attributeDescription->title;
        $this->group = $this->attributeGroup->attributeGroupDescription->title;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        AttributeDescription::deleteAll(['attribute_id' => $this->attribute_id]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $attributeDescription = new AttributeDescription();
            $attributeDescription->attribute_id = $this->attribute_id;
            $attributeDescription->title = $this->title;
            $attributeDescription->save();
        } else {
            $attributeDescription = AttributeDescription::findOne(['attribute_id' => $this->attribute_id]);
            $attributeDescription->title = $this->title;
            $attributeDescription->save();
        }
    }


    public static function loadAttributes(){
        return self::find()->all();
    }

    public static function loadAttributeGroupDescriptions(){
        return AttributeGroupDescription::find()->all();
    }

    public static function loadAttributeDescriptionsList(){
        return AttributeDescription::find()->all();
    }


    public function getAttributeDescription(){
        return $this->hasOne(AttributeDescription::className(), ['attribute_id' => 'attribute_id']);
    }

    public function getAttributeGroup(){
        return $this->hasOne(AttributeGroup::className(), ['attribute_group_id' => 'attribute_group_id']);
    }





}
