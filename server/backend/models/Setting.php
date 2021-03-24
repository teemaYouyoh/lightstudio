<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "setting".
 *
 * @property string $phone
 * @property string $instagram
 * @property string $facebook
 * @property string $email
 * @property string $logo
 * @property string $address
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone','email'], 'required'],
            [['email'], 'email'],
            [['phone'], 'string', 'max' => 15],
            [['instagram', 'facebook', 'email', 'address'], 'string', 'max' => 255],
           // [['logo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 1],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook',
            'email' => 'Email',
            'logo' => 'Логотип',
            'address' => 'Адрес',
        ];
    }

    public static function getSettings(){
        return self::find()->one();
    }


//    public function upload()
//    {
//        if ($this->validate()) {
//            $this->logo->saveAs('uploads/' . $this->logo->baseName . '.' . $this->logo->extension);
//
////            foreach ($this->logo as $file) {
////                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
////            }
//            return true;
//        } else {
//            return false;
//        }
//    }
}
