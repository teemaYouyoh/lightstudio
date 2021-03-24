<?php

namespace backend\models;

use backend\models\PaymentMethod;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order".
 *
 * @property int $order_id
 * @property string $customer_firstname
 * @property string $customer_lastname
 * @property string $customer_email
 * @property string $customer_phone
 * @property int $payment_method_id
 * @property string $date_create
 * @property string $date_due
 * @property int $amount
 */
class Order extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_create'],
                ],
                'value' => date("Y-m-d H:i:s"),

            ],


        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    public $status;
    public $items;
    public $product;
    public $payment_method;

    public $_intervals;
    public $product_id;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_method_id','customer_firstname','customer_phone'], 'required'],
            [['payment_method_id'], 'integer'],
            [['date_create', 'date_due','amount'], 'safe'],
            [['customer_firstname', 'customer_lastname'], 'string', 'max' => 30],
            [['customer_email'], 'string', 'max' => 100],
            [['customer_email'], 'email'],
            [['customer_phone'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'ID',
            'customer_firstname' => 'Имя',
            'customer_lastname' => 'Фамилия',
            'customer_email' => 'Email',
            'customer_phone' => 'Номер телефона',
            'payment_method_id' => 'Мето оплаты',
            'amount' => 'Сумма заказа',
            'date_create' => 'Дата заказа',
            'date_due' => 'Дата съемки',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        if($insert){
//
//
//        } else {
//
//        }
    }

    public function afterFind()
    {
        parent::afterFind();


        $this->status = $this->orderStatus->status->title;
        $this->product = isset($this->orderProduct->product->productDescription->title)  ? $this->orderProduct->product->productDescription->title : null;
        $this->product_id = isset($this->orderProduct->product->product_id) ? $this->orderProduct->product->product_id : null;
        $this->payment_method = isset($this->paymentMethod->title)?$this->paymentMethod->title:null;
        $this->_intervals = $this->intervals;
    }

    public function getOrderStatus(){
        return $this->hasOne(OrderStatus::className(), ['order_id' => 'order_id']);
    }

    public function getOrderProduct(){
        return $this->hasOne(OrderItem::className(), ['order_id' => 'order_id']);
    }

    public function getPaymentMethod(){
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }

    public function getIntervals(){
        return $this->hasMany(OrderInterval::className(), ['order_id' => 'order_id']);
    }


    public function getOptions(){
        return $this->hasMany(OrderOption::className(), ['order_id' => 'order_id']);
    }
    public static function setOrder($data){

    }


    public static function preparedDate($date) {
      return  str_replace(['-','.','/'], '', $date);
    }
}
