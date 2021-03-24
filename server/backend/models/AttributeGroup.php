<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "attribute_group".
 *
 * @property int $attribute_group_id
 * @property string $title
 */
class AttributeGroup extends \yii\db\ActiveRecord
{
    public $title;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute_group';
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
    public function attributeLabels()
    {
        return [
            'attribute_group_id' => 'Attribute Group ID',
            'sort_order' => 'Порядок',
            'title' => 'Название',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->title = $this->attributeGroupDescription->title;
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $attributeGroupDescription = new AttributeGroupDescription();
            $attributeGroupDescription->attribute_group_id = $this->attribute_group_id;
            $attributeGroupDescription->title = $this->title;
            $attributeGroupDescription->save();
        } else {
            $isGroup = AttributeGroupDescription::findOne(['attribute_group_id' => $this->attribute_group_id]);
            $isGroup->attribute_group_id = $this->attribute_group_id;
            $isGroup->title = $this->title;
            $isGroup->save();
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        AttributeGroupDescription::deleteAll(['attribute_group_id' => $this->attribute_group_id]);
    }


    public static function loadAttributeGroups(){
        return self::find()->all();
    }

    public function getAttributeGroupDescription(){
        return $this->hasOne(AttributeGroupDescription::className(), ['attribute_group_id' => 'attribute_group_id']);
    }
}
