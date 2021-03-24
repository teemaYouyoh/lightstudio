<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 1/17/19
 * Time: 2:32 AM
 */

namespace api\modules\v1\controllers;

use api\components\GoogleCalendar;
use api\components\LiqPay;
use backend\models\Option;
use backend\models\OrderInterval;
use backend\models\OrderItem;
use backend\models\OrderOption;
use backend\models\OrderStatus;
use backend\models\ProductDescription;
use backend\models\Product;
use backend\models\Interval;
use backend\models\StatusOrder;
use Yii;
use backend\models\Order;
use yii\helpers\Json;
use api\components\Crm;


class ApiPaymentController extends ApiController
{

    private $intervals;
    private $hall;
    private $amount;
    private $totalIntervals;
    private $product;

   

    public $modelClass = 'backend\models\Order';
    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'data',
    ];

    const LIQPAY = 1;
    const INVOICE = 2;
    const HALLS = 1;
    const PENDING_STATUS = 1;
    const CANCELLED_STATUS = 3;
    const PAID_STATUS = 5;
    const ERROR_STATUS = 6;
    const INVOICE_STATUS = 7;
    const ACTIVE_STATUS = 1;


    public function actionPay(){

         //       Yii::$app->mailer->compose()
         //    ->setFrom('lightstudio.office@gmail.com')
         //    ->setSubject('Light Studio | CRON')
         //    ->setTextBody('LIQPAY')
         //    ->send();

         //    return 'send';

    
        $transaction = Yii::$app->db->beginTransaction();

        $data = Json::decode(Yii::$app->request->getRawBody());
        $order = new Order();
        $order->customer_firstname = $data['userName'];
        $order->customer_phone = $data['phone'];
        $order->customer_email = $data['email'];
        $order->payment_method_id = $data['paymentMethodId'];
        $order->date_due = $data['dateDue'];

        if($order->validate()){
            $order->save();
        } else {
            return ['status' => 400, 'message' => $order->errors];
        }
        

        if(!$this->saveProducts($data['items'],$order->order_id)){
            $transaction->rollBack();
            return  [
                'status' => 403,
                'message' => 'Забронировано',
                'orderId' => $order->order_id
            ];
        };


        $this->saveOptions($data['items'][0]['options'][0],$order->order_id,$data['timeDue']);
        $this->setStatus($order->order_id,self::PENDING_STATUS);
        // $this->setStatus($order->order_id, self::INVOICE_STATUS);
        $setAmountOrder = Order::findOne(['order_id' => $order->order_id]);
        $setAmountOrder->amount = $this->amount;
        $setAmountOrder->save();

        $transaction->commit();

        $params = [
            'amount' => $this->amount,
            'description' =>  'Бронирование зала ' . $this->product->productDescription->title,
            'customerName' => $order->customer_firstname,
            'orderId' => $order->order_id,
            'productName' => $this->product->productDescription->title,
            'email' => $order->customer_email,
        ];
        
            return  [
                'liqpay' => $this->payLiqpay($params),
                'status' => 200,
                'orderId' => $order->order_id
            ];


            // $this->sendCrm($order);
            // $google = new GoogleCalendar();
            // $google->order_id = $order->order_id;
            // $google->syncCalendar();
            // $packEmail = $this->packEmail($order->order_id,7,'');
            // $this->sendEmail($order->order_id,$packEmail);
            
            // return [
            //     'status' => 200,
            //     'message' => 'Заказ успешно забронировал. Наш менеджер свяжется с вами :)',
            //     'orderId' => $order->order_id
            // ];


            
     
        // else {
        //     $this->changeOrderStatus($order->order_id,self::INVOICE_STATUS);

        //     return  [
        //         'status' => 200,
        //         'orderId' => $order->order_id,
        //         'invoice' => $this->sendLiqPayInvoice($params)
        //     ];
        // }

    }

    private function saveProducts($items,$orderId){
       
        foreach ($items as $item){
            $orderItem = new OrderItem();
            $orderItem->product_id = $item['productId'];
            $orderItem->order_id = $orderId;
            $orderItem->save();

            if(!$orderItem->save()){
                return ['status' => 400, 'message' => $orderItem->errors,'data' => $orderItem];
            }

            $this->product = Product::findOne(['product_id' => $item['productId']]);

            foreach ($item['intervals'] as  $date => $intervals) {
                foreach ($intervals as $interval){
                    
                    $date = Order::preparedDate($date);

                    if($this->checkIntervals($date,$interval,$item['productId'])){
                            return false;
                    }

                    $orderInterval = new OrderInterval();
                    $orderInterval->date = Order::preparedDate($date);
                    $orderInterval->interval = $interval;
                    $this->intervals[] = $interval;
                    $orderInterval->order_id = $orderId;
                    $orderInterval->status = self::ACTIVE_STATUS;
                    $orderInterval->product_id = $item['productId'];
                    if(!$orderInterval->save()){
                       return ['status' => 400, 'message' => $orderInterval->errors, 'data' => $orderInterval];
                    }

                    $this->totalIntervals++;
                }
            }
            $this->amount += $this->totalIntervals * $this->product->price;
            return true;
        }
    }

    private function setStatus($orderId,$status){
        $orderStatus = new OrderStatus();
        $orderStatus->order_id = $orderId;
        $orderStatus->status_code = $status;
        $orderStatus->save();
    }



    private function checkIntervals($date,$interval,$productId){

     $find = OrderInterval::findOne([
           'date' => $date,
           'interval' => $interval,
           'product_id' => $productId,
           'status' => self::ACTIVE_STATUS
       ]);

       return $find ? true : false;
 
    }

    private function saveOptions($options,$orderId,$timeDue){
        foreach ($options as $option) {
            $orderOption = new OrderOption();
            $orderOption->option_id = (int)$option;
            $optionPrice = Option::findOne(['option_id' => (int)$option]);
            $orderOption->order_id = $orderId;
            $orderOption->save();
            if($optionPrice->option_group_id === 1){
                $this->amount += $optionPrice->price * $timeDue;
            } else {
                $this->amount += $optionPrice->price;
            }
        }
    }


    public function payLiqpay($params){

        $send_data = array(
            'version'    => '3',
            'public_key'  => Yii::$app->params['liqpay']['publicKey'],
            'amount'      => $params['amount'],
            'currency'    => 'UAH',
            'description' => $params['description'],
            'order_id'    => $params['orderId'],
            'action'        => 'pay',
            'language'    => 'uk',
            // 'paytypes' => ['card','privat24','liqpay'],
            // 'result_url'  => Yii::$app->params['liqpay']['resultUrl'],
            'server_url' => Yii::$app->params['liqpay']['serverUrl'],
            'sandbox' => Yii::$app->params['liqpay']['sandbox'],
            'sender_first_name' => $params['customerName'],
            'product_name' => $params['productName'],
            'expired_date' => $this->setExpLiqpayTime(600),
        );

        $data = base64_encode(json_encode($send_data));
        $signature = base64_encode( sha1( Yii::$app->params['liqpay']['privateKey'] . $data . Yii::$app->params['liqpay']['privateKey'], 1 ) );

        $result = [
            'data' => $data,
            'signature' => $signature,
        ];
        
        return $result;
    }




    private function sendCrm($order){
        $amo = new Crm();
        $amo->amount = $order->amount;
        $amo->date = $order->date_due;
        $amo->customer = $order->customer_firstname;
        $amo->intervals = $order->intervals;
        $amo->hall = $order->orderProduct->product_id;
        $amo->phone = $order->customer_phone;
        $amo->email = $order->customer_email;
        $amo->order_id = $order->order_id;
       return $amo->createOrder();
    }


    private function changeOrderStatus($order_id,$status){
        $orderStatus = OrderStatus::findOne(['order_id' => $order_id]);
        if($orderStatus){
            $orderStatus->status_code = $status;
            $orderStatus->save();
        }
    }


    private function setExpLiqpayTime($offset){
        $diff = date('Z') - $offset;
        return date("Y-m-d H:i:s",  strtotime('now') - $diff);
    }   


    public function actionCheckLiqpayResult($orderId){

        $liqpay = new LiqPay(Yii::$app->params['liqpay']['publicKey'], Yii::$app->params['liqpay']['privateKey']);

        if(Order::findOne(['order_id' => $orderId])){

            $res = $liqpay->api("request", array(
                'action'        => 'status',
                'version'       => '3',
                'order_id'      => $orderId
            ));

            return $res;
            
        } else {
            return false;
        }
    }


    private function bookingCheckPay($orderId){

    
       $liqpay = new LiqPay(Yii::$app->params['liqpay']['publicKey'], Yii::$app->params['liqpay']['privateKey']);
       
        $res = $liqpay->api("request", array(
            'action'    => 'status',
            'version'   => '3',
            'order_id'  => $orderId
        ));

        if($res->status !== 'success'){
            
            if($res->err_code == 'payment_not_found'){
                $status = $res->err_description;
                 $this->changeOrderStatus($orderId, self::CANCELLED_STATUS);
                $this->sendErrorEmail($this->packEmail($orderId,self::CANCELLED_STATUS,$status),$status);
               
            } else {
                $status = $this->getLiqpayErrorStatus($res);
                 $this->changeOrderStatus($orderId, self::ERROR_STATUS);
                $this->sendErrorEmail($this->packEmail($orderId,self::ERROR_STATUS,$status),$status);
            }

            $this->deactivatedIntervals($orderId);

            return [
                'status' => 403,
                'message' => $status,
                'data' => $res,
            ];
        } else {
            $order = Order::findOne(['order_id' => $orderId]);
            $this->changeOrderStatus($orderId, self::PAID_STATUS);
            $this->sendCrm($order);
            $google = new GoogleCalendar();
            $google->order_id = $order->order_id;
            $google->syncCalendar();
            $packEmail = $this->packEmail($order->order_id,self::PAID_STATUS,'');
            // Отключил email уведомление 
            // TODO: включить потом обратно
            if(!empty($order->customer_email)){
                $this->sendEmailToCustomer($order->customer_email,$packEmail);
            }
            $this->sendEmail($order->order_id,$packEmail);
            
            return [
                'status' => 200,
                'message' => 'Заказ успешно оплачен',
                'data' => $res,
            ];
        
        }  
        
    }


    private function deactivatedIntervals($orderId){
        $orderInterval =  OrderInterval::findAll(['order_id' => $orderId]);
        if($orderInterval){
            foreach ($orderInterval as $interval){
                $interval->status  = 0;
                $interval->save();
            }
            return true;
        } else {
            return false;
        }
    }


    public function actionLiqpayResponse(){

        $data = Yii::$app->request->post('data');
        $signature = $this->calculateSignature($data, Yii::$app->params['liqpay']['privateKey']);
        $parsed_data =  json_decode(base64_decode($data));
          
        if ($signature == Yii::$app->request->post('signature')) {
            return $this->bookingCheckPay($parsed_data->order_id);
         } 
         
          return false;
    }


   private function calculateSignature($data, $private_key) {
        return base64_encode(sha1($private_key . $data . $private_key, true));
    }


    private function getLiqpayErrorStatus($res){

        switch ($res->status){
            case 'error':
                return 'Неуспешный платеж. Некорректно заполнены данные';
                break;
            case 'failure':
                return 'Неуспешный платеж';
                break;
            case 'reversed':
                return 'Платеж возвращен';
                break;
            default:
                return $res->err_description;
                break;
        }
    }


    private function getOrderStatus($order_id){
        $orderStatus = OrderStatus::findOne(['order_id' => $order_id]);
        if($orderStatus){
            return $orderStatus->status_code;
        }
        return false;
    }


    public function actionLead(){


        $data = Json::decode(Yii::$app->request->getRawBody());

        $crm = new Crm();

        $crm->customer = $data['name'];
        $crm->phone = $data['phone'];
        $crm->email = $data['email'];

        
         if(isset($data['dress'])){
             $crm->dress = $data['dress'];
             $crm->amount = $data['dressPrice'];
             $htmlBody =   'Имя: ' . $data['name'] . PHP_EOL
                . ' Телефон: ' . $data['phone'] . PHP_EOL
                .  ' Email: ' . $data['email'] . PHP_EOL
                .  ' Платье: ' . $data['dress']
                 .  'Стоимость: ' . $data['dressPrice']
                ; 
        } else {
             $crm->dress = '';
              $htmlBody =   'Имя: ' . $data['name'] . PHP_EOL
                . ' Телефон: ' . $data['phone'] . PHP_EOL
                .  ' Email: ' . $data['email'] . PHP_EOL; 

        }


        $crm->createLead();


        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('ЗАЯВКА САЙТ')
            ->setTextBody('ЗАЯВКА:')
            ->setHtmlBody(
                  $htmlBody
            )
            ->send();

    }


    private function packEmail($orderId,$status,$message){

        $order = Order::findOne(['order_id' => $orderId]);
        $options = $this->getOptions($orderId);
        $item = OrderItem::findOne(['order_id' => $orderId]);
        $hall = ProductDescription::findOne(['product_id' => $item->product_id]);

        $intervalId = OrderInterval::find()
        ->where([
            'order_id' => $orderId,
            'status' => self::ACTIVE_STATUS
        ])->orderBy(['interval' => SORT_ASC])->one();

        $interval = Interval::findOne(['code' => $intervalId['interval']]);
        $time = substr($interval['title'], 0, 5);


        $paymentResult = null;

        $class = null;

        switch ($status){
            case 1:
                $class = 'black';
                break;
            case 5:
                $class = 'green';
                break;
            case 3:
            case 6:
                $class = 'red';
                break;
            case 7:
                $class = 'gold';
                break;
            default:
                $class = 'green';
                break;
        }

        if(!empty($message)){
            $paymentResult = $message;
        } else {
            $paymentResult = $this->getStatusTitle($status);
        }


        return  [
            'options' => [
                'persons' => $options['persons'],
                'light' => $options['light'],
                'dressing' => $options['dressing'],
            ],
            'hall' => $hall->title,
            'time' => $time,
            'paymentMethod' => $order->paymentMethod->title,
            'paymentResult' =>  $paymentResult,
            'class' => $class,
            'order' => $order,
            'dateDue' => Yii::$app->formatter->format($order->date_due, 'date'),
        ];
    }


    private function sendErrorEmail($params,$subject){
        Yii::$app->mailer->compose('developerOrder',[
            'params' => $params
        ])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Light Studio | LIQPAY')
            ->setTextBody($subject)
            ->send();
    }


    private function sendEmail($orderId,$params){
        Yii::$app->mailer->compose('ownerOrder',[
            'params' => $params
        ])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('НОВЫЙ ЗАКАЗ #' . $orderId)
            ->setTextBody('У вас новый заказ')
            ->send();
    }

    private function getStatusTitle($status){
        $statusTitle = StatusOrder::findOne(['code' => $status]);

        return $statusTitle->title;
    }

    private function getOptions($orderId){

        $order = Order::findOne(['order_id' => $orderId]);

        $result['persons'] = '1';
        $result['light'] = 'Не указан';
        $result['dressing'] = 'По умолчанию';
 
            foreach ($order->options as $item){
                $option = Option::findOne(['option_id' => $item['option_id']]);
                if($option){
                    switch ($option->option_group_id){
                        case 1:
                            $result['persons'] = isset($option->optionDescription->value) ? $option->optionDescription->value : null;
                            break;
                        case 2:
                            $result['light'] = isset($option->optionDescription->value) ? $option->optionDescription->value : 'Не указан';
                            break;
                        case 3:
                            $result['dressing'] = isset($option->optionDescription->value) ? $option->optionDescription->value : 'По умолчанию';
                            break;
                    }
                }
            }

        return $result;

    }

     private function sendEmailToCustomer($email,$params){

        if(!empty($email)){
            Yii::$app->mailer->compose('customerOrder',[
                'params' => $params
            ])
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($email)
                ->setSubject('Light Studio | Бронирование!')
                ->setTextBody('Вы забронировали зал')
                ->send();
        }

    }

}


