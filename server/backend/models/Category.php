<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $category_id
 * @property int $parent_id
 * @property int $sort_order
 * @property int $status
 */

class Category extends \yii\db\ActiveRecord
{
    public $title;
    public $categories_array;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort_order', 'status'], 'integer'],
            [['title','meta_title','meta_description','meta_keywords'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'parent_id' => 'Родительская категория',
            'sort_order' => 'Сортировка',
            'status' => 'Статус',
            'title' => 'Название',
            'categories' => 'Категории',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords'
        ];
    }


    public function fields()
    {
        $fields = parent::fields();

        $fields['title'] = function ($model) {
            return $model->categoryDescription->title;
        };


        $fields['meta_title'] = function ($model) {
            return $model->categoryDescription->meta_title;
        };

        $fields['meta_description'] = function ($model) {
            return $model->categoryDescription->meta_description;
        };

        $fields['meta_keywords'] = function ($model) {
            return $model->categoryDescription->meta_keywords;
        };


        return $fields;
    }



    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $categoryDescription = new CategoryDescription();
            $categoryDescription->category_id = $this->category_id;
            $categoryDescription->title = $this->title;
            $categoryDescription->meta_title = $this->meta_title;
            $categoryDescription->meta_description = $this->meta_description;
            $categoryDescription->meta_keywords = $this->meta_keywords;
            $categoryDescription->save();
        } else {
            $isCategory = CategoryDescription::findOne(['category_id' => $this->category_id]);
            $isCategory->title = $this->title;
            $isCategory->meta_title = $this->meta_title;
            $isCategory->meta_description = $this->meta_description;
            $isCategory->meta_keywords = $this->meta_keywords;
            $isCategory->save();
        }

    }

    public function afterFind()
    {
        parent::afterFind();
        $this->title = $this->categoryDescription->title;
        $this->meta_title = $this->categoryDescription->meta_title;
        $this->meta_description = $this->categoryDescription->meta_description;
        $this->meta_keywords = $this->categoryDescription->meta_keywords;
    }


    public function afterDelete()
    {
        parent::afterDelete();
        CategoryDescription::deleteAll(['category_id' => $this->category_id]);
    }


    public static function getCategories(){
        return self::find()->all();
    }

    public static function getCategoriesList(){
       //categories = Category::find()->with('categoryDescription')->select(['title'])->asArray()->orderBy(['category_id' => SORT_ASC,'parent_id' => SORT_ASC])->all();
        $categories = CategoryDescription::find()->all();
       return $categories;
    }

    public static function getCategory($id){
        self::findOne(['id' => $id]);
    }


    public function getCategoryDescription(){
        return $this->hasOne(CategoryDescription::className(), ['category_id' => 'category_id']);
    }

    public function getCategoriesDescription(){
        return $this->hasMany(CategoryDescription::className(), ['category_id' => 'category_id']);
    }

}


