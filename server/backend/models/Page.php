<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property int $page_id
 * @property int $sort_order
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 */
class Page extends \yii\db\ActiveRecord
{
    public $title;
    public $description;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort_order','status'], 'integer'],
            [['title','description','meta_title','meta_description','meta_keywords'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'sort_order' => 'Порядок',
            'title' => 'Название',
            'description' => 'Описание',
            'status' => 'Статус',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'meta_keywords' => 'Meta keywords'
        ];
    }

    public static function loadPages(){

        return self::find()->all();

    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $pageDescription = new PageDescription();
            $pageDescription->page_id = $this->page_id;
            $pageDescription->title = $this->title;
            $pageDescription->description = $this->description;
            $pageDescription->meta_title = $this->meta_title;
            $pageDescription->meta_description = $this->meta_description;
            $pageDescription->meta_keywords = $this->meta_keywords;
            $pageDescription->save();


        } else {
            $pageDescription = PageDescription::findOne(['page_id' => $this->page_id]);
            $pageDescription->title = $this->title;
            $pageDescription->description = $this->description;
            $pageDescription->meta_title = $this->meta_title;
            $pageDescription->meta_description = $this->meta_description;
            $pageDescription->meta_keywords = $this->meta_keywords;
            $pageDescription->save();
        }

    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['title'] = function ($model) {
            return $model->pageDescription->title;
        };

        $fields['meta_title'] = function ($model) {
            return $model->pageDescription->meta_title;
        };

        $fields['meta_description'] = function ($model) {
            return $model->pageDescription->meta_description;
        };

        $fields['meta_keywords'] = function ($model) {
            return $model->pageDescription->meta_keywords;
        };


        return $fields;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        PageDescription::deleteAll(['page_id' => $this->page_id]);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->title = $this->pageDescription->title;
        $this->description = $this->pageDescription->description;
        $this->meta_title = $this->pageDescription->meta_title;
        $this->meta_description = $this->pageDescription->meta_description;
        $this->meta_keywords = $this->pageDescription->meta_keywords;

    }

    public function getPageDescription(){
        return $this->hasOne(PageDescription::className(), ['page_id' => 'page_id']);
    }


}
