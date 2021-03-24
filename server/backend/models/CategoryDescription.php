<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category_description".
 *
 * @property int $category_id
 * @property string $title
 * @property string $description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
class CategoryDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['description','meta_title','meta_description','meta_keywords'], 'string'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'title' => 'Title',
            'description' => 'Description',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords'
        ];
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }
}
