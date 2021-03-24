<?php


namespace backend\models;

use Yii;
use yii\base\Model;

class OrderSearch extends Order {

    /* Вычисляемое поле */

    public $order_id;
    public $customer_firstname;
    public $amount;
    public $date_create;
    public $payment_method;
    public $status;

    /* Настройка правил */
    public function rules() {
        return [
            /* другие правила */
            [['payment_method','status','order_id','amount','date_create','customer_firstname'], 'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Настроим поиск для использования
     * поля fullName
     */
    public function search($params) {
        $query = Order::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'order_id' => SORT_DESC,
                ],
                'attributes' => ['payment_method', 'order_id', 'amount','status','customer_firstname','date_create'],
            ],
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['order.order_id' => $this->order_id])
            ->andFilterWhere(['like','customer_firstname', $this->customer_firstname])
            ->andFilterWhere(['like','amount', $this->amount])
            ->andFilterWhere(['like','date_create', $this->date_create])
            ->andFilterWhere(['like','payment_method_id', $this->payment_method])
            ->andFilterWhere(['like','status', $this->status])
           ;



        return $dataProvider;
    }
}