<?php


namespace console\controllers;

use api\components\Crm;
use api\components\LiqPay;
use backend\models\Order;
use backend\models\OrderItem;
use backend\models\Option;
use backend\models\OrderInterval;
use backend\models\ProductDescription;
use backend\models\OrderStatus;
use backend\models\StatusOrder;
use backend\models\Interval;
use yii\console\Controller;
use Google_Client;
use Google_Service_Calendar;
use Yii;

class BookingController extends Controller {

    const SUCCESS_STATUS = 'success';
    const SANDBOX_STATUS = 'sandbox';
    const INVOICE_WAIT = 'invoice_wait';
    const PRIVATE_KEY = 'TWPXRXJTeJck8hUsg0dpCqzHwuA6bscoDDWI2gj0';
    const PUBLIC_KEY =  'i30988876794';
    const STATUS_PAYED = 5;
    const STATUS_ERROR_PAYMENT = 6;
    const STATUS_INVOICE = 7;
    const STATUS_IN_PROGRESS = 1;
    const CANCELLED = 3;

    const HYGGE = '1om1m91tgnt8uu27hhdivl29qk@group.calendar.google.com';
    const WHITE = 'u2m950dorkh41368jstu710i48@group.calendar.google.com';
    const FASHION = 'a2551g3dkvtuoe03jjerm14vl4@group.calendar.google.com';
    const MAKE_UP = 'g6ei2ri6vlg3ncf6b7a0qa67i8@group.calendar.google.com';
    const OPEN = 'lmbi11549beurk9qrf318tvm1g@group.calendar.google.com';
    const LOFT = 'j67cfdjnl342f66fkklprp9meg@group.calendar.google.com';
   
    const GOOGLE_JSON = '{"web":{"client_id":"918988381410-dn44n7fgq9t7ue4q74npvl0ul1seul04.apps.googleusercontent.com","project_id":"light-studio-306117","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://oauth2.googleapis.com/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"UbthhDmff22ovlb1Xhe2sIFm","redirect_uris":["https://lightstudio.ua/"]}}';

   
    public function actionCheckPayment(){
        $orderStatuses = OrderStatus::find()->where(['status_code' => self::STATUS_IN_PROGRESS])->all();
        $liqpay = new LiqPay(self::PUBLIC_KEY, self::PRIVATE_KEY);


        foreach ($orderStatuses as $status){
            $orderId = $status['order_id'];
            $orderStatus = $status['status_code'];

            $order = Order::findOne(['order_id' => $orderId]);

            $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($order->date_create);

             $res = $liqpay->api("request", array(
                    'action'        => 'status',
                    'version'       => '3',
                    'order_id'      => $orderId
                ));

            if($diff > 600 && $res->status !== 'success'){

                $this->deleteEvent($orderId);
                       
                $orderInterval = OrderInterval::findAll(['order_id' => $orderId]);
                    
                foreach ($orderInterval as $interval){
                    $interval->status = 0;
                    $interval->save();
                }

                if(isset($res->err_code) && $res->err_code == 'payment_not_found') {
                        $this->changeOrderStatus($orderId,self::CANCELLED);
                        $this->sendEmail($orderId,self::CANCELLED,$res->err_description);
                        
                } else {

                 switch ($res->status){
                        case 'error':
                            $message = 'Неуспешный платеж. Некорректно заполнены данные';
                            break;
                        case 'failure':
                            $message = 'Неуспешный платеж';
                            break;
                        case 'reversed':
                            $message = 'Платеж возвращен';
                            break;
                        case 'wait_info':
                         $message = 'Ожидается дополнительная информация, попробуйте позже';
                        default:
                            $message  = isset($res->err_description) ? $res->err_description : $res->status ;
                            break;
                    }

                    $this->changeOrderStatus($orderId,self::STATUS_ERROR_PAYMENT);
                    $this->sendEmail($orderId,self::STATUS_ERROR_PAYMENT,$message);
                }
         
            
        }
    }
}


    public function sendEmail($orderId,$status,$message){
        Yii::$app->mailer->compose('developerOrder',[
                'params' =>   $this->packEmail($orderId,$status,$message)
        ])
            ->setFrom('lightstudio.office@gmail.com')
            ->setSubject('Light Studio | CRON')
            ->setTextBody('LIQPAY')
            ->send();
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


    private function packEmail($orderId,$status,$message){

        $order = Order::findOne(['order_id' => $orderId]);
        $options = $this->getOptions($orderId);
        $item = OrderItem::findOne(['order_id' => $orderId]);
        $hall = ProductDescription::findOne(['product_id' => $item->product_id]);
        $paymentResult = null;

        $intervalId =  OrderInterval::findOne([
            'order_id' => $orderId,
            'status' => 1
        ]);

        $interval = Interval::findOne(['code' => $intervalId['interval']]);
        $time = substr($interval['title'], 0, 5);

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


    private function getStatusTitle($status){
        $statusTitle = StatusOrder::findOne(['code' => $status]);

        return $statusTitle->title;
    }

    public function deleteEvent($orderId){
        $client = new Google_Client();
        $client->setApplicationName('GOOGLE API CALENDAR');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(self::GOOGLE_JSON);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $tokenPath = Yii::getAlias('@api') . '/components/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->refreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                echo 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));
                $accessToken = $client->authenticate($authCode);
                $client->setAccessToken($accessToken);
            }
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        $service = new Google_Service_Calendar($client);
        $order = Order::findOne(['order_id' => $orderId]);
        $calendarId = $this->getHall($order->orderProduct->product_id);
        $date = \Yii::$app->formatter->asDate($order->date_due, 'yyyy-MM-dd');
        $optionParams = ['timeMax' => date('Y-m-d', strtotime($date. ' + 1 days')) .'T23:00:00Z','timeMin' => $date .'T00:00:00Z'];
        $events = $service->events->listEvents($calendarId,$optionParams);
        while (true) {
            foreach ($events->getItems() as $event) {
                if($service->events->get($calendarId,$event->getId())->getDescription() === 'Заказ №' . $order->order_id){
                    $service->events->delete($calendarId,$event->getId());
                }
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $service->events->listEvents($calendarId, $optionParams);
            } else {
                break;
            }
        }
    }

    //    public function actionTest()
    // {
    //      $client = new Google_Client();
    //     $client->setApplicationName('GOOGLE API CALENDAR');
    //     $client->setScopes(Google_Service_Calendar::CALENDAR);
    //     $client->setAuthConfig(self::GOOGLE_JSON);
    //     $client->setAccessType('offline');
    //     $client->setPrompt('consent');
    //     $tokenPath = Yii::getAlias('@api') . '/components/token.json';
    //     if (file_exists($tokenPath)) {
    //         $accessToken = json_decode(file_get_contents($tokenPath), true);
    //         $client->setAccessToken($accessToken);
    //     }

    //     if ($client->isAccessTokenExpired()) {
    //         if ($client->getRefreshToken()) {
    //             $client->refreshToken($client->getRefreshToken());
    //         } else {
    //             $authUrl = $client->createAuthUrl();
    //             printf("Open the following link in your browser:\n%s\n", $authUrl);
    //             echo 'Enter verification code: ';
    //             $authCode = trim(fgets(STDIN));
    //             $accessToken = $client->authenticate($authCode);
    //             $client->setAccessToken($accessToken);
    //         }
    //         if (!file_exists(dirname($tokenPath))) {
    //             mkdir(dirname($tokenPath), 0700, true);
    //         }
    //         file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    //     }

    //     $service = new Google_Service_Calendar($client);

    //     $order = Order::findOne(['order_id' => 5837]);
    //     $date = \Yii::$app->formatter->asDate($order->date_due, 'yyyy-MM-dd');

    //     $calendarId = $this->getHall($order->orderProduct->product_id);

    //     foreach ($order->intervals as $interval) {
    //         $title = Interval::findOne(['code' => $interval->interval])->title;

    //         $dateStart = substr($title, 0, 5);
    //         $dateEnd = substr($title, 6, 5);
    //         $nextDay = null;

    //         // echo $dateStart . ' ' . $dateEnd . PHP_EOL;
    //         // exit;

    //         if ($dateStart == "23:00") {
    //             $nextDay = date('Y-m-d', strtotime($date . ' + 1 days'));
    //         }

    //         $event = new \Google_Service_Calendar_Event(array(
    //             'summary' => $order->customer_firstname,
    //             'description' => 'Заказ №' . $order->order_id,
    //             'start' => array(
    //                 'dateTime' => $date . 'T' . $dateStart . ':00',
    //                 "timeZone" => 'Europe/Kiev',
    //             ),
    //             'end' => array(
    //                 'dateTime' => $nextDay ? $nextDay . 'T' . $dateEnd . ':00' : $date . 'T' . $dateEnd . ':00',
    //                 "timeZone" => 'Europe/Kiev',
    //             ),
    //             'reminders' => array(
    //                 'useDefault' => FALSE,
    //             ),
    //         ));
    //         $nextDay = null;

    //         $event = $service->events->insert($calendarId, $event);
    //        printf('Event created: %s' . PHP_EOL, $event->htmlLink);
    //        exit;

    //     }
    // }

    private function changeOrderStatus($order_id,$status){
        $orderStatus = OrderStatus::findOne(['order_id' => $order_id]);
        $orderStatus->status_code = $status;
        $orderStatus->save();
    }


    private function getHall($hall){

        switch ($hall){
            case 1:
                return self::OPEN;
                break;
            case 15:
                return self::FASHION;
                break;
            case 16:
                return self::LOFT;
                break;
            case 17:
                return self::WHITE;
                break;
            case 18:
                return self::HYGGE;
                break;
            case 19:
                return self::MAKE_UP;
            default:
                return false;
        }

    }
}