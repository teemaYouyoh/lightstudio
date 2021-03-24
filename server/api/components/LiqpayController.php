<?php
/**
 * Created by PhpStorm.
 * User: rostyslav
 * Date: 2/25/19
 * Time: 7:17 PM
 */

namespace api\components;

class LiqpayController extends \yii\web\Controller
{

    // const PRIVATE_KEY = '2IU7PnWd3KwR1UzljmV6duSmKHvwlQQEyAqihS5n';
    // const PUBLIC_KEY =  'i30428434616';

 
   

    // private $amount;
    // private $description;
    // private $orderId;
    // private $customerName;
    // private $productName;


    // public function pay(){

    //     $send_data = array(
    //         'version'    => '3',
    //         'public_key'  => self::PUBLIC_KEY,
    //         'amount'      => $this->amount,
    //         'currency'    => 'UAH',
    //         'description' => $this->description,
    //         'order_id'    => $this->orderId,
    //         'action'        => 'pay',
    //         'language'    => 'ru',
    //         'result_url'  => 'https://studio.zigzag.team',
    //         'sandbox' => 1,
    //         'sender_first_name' =>  $this->customerName,
    //         'product_name' => $this->productName
    //     );

    //     return $this->setForm($send_data);
    // }


    // private function setForm($send_data){
    //     $data = base64_encode(json_encode($send_data));
    //     $signature = base64_encode( sha1( self::PRIVATE_KEY . $data . self::PRIVATE_KEY, 1 ) );

    //     $result = [
    //         'data' => $data,
    //         'signature' => $signature
    //     ];
    //     return $result;
    // }



}