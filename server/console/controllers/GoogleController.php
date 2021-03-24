<?php


namespace console\controllers;

use backend\models\Interval;
use backend\models\Order;
use Google_Client;
use Google_Service_Calendar;

use yii\console\Controller;

class GoogleController extends Controller {

    const HYGGE = '1om1m91tgnt8uu27hhdivl29qk@group.calendar.google.com';
    const WHITE = 'u2m950dorkh41368jstu710i48@group.calendar.google.com';
    const FASHION = 'a2551g3dkvtuoe03jjerm14vl4@group.calendar.google.com';
    const MAKE_UP = 'g6ei2ri6vlg3ncf6b7a0qa67i8@group.calendar.google.com';
    const OPEN = 'lmbi11549beurk9qrf318tvm1g@group.calendar.google.com';
    const LOFT = 'j67cfdjnl342f66fkklprp9meg@group.calendar.google.com';

    const GOOGLE_JSON = '{"web":{"client_id":"918988381410-dn44n7fgq9t7ue4q74npvl0ul1seul04.apps.googleusercontent.com","project_id":"light-studio-306117","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://oauth2.googleapis.com/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"UbthhDmff22ovlb1Xhe2sIFm","redirect_uris":["https://lightstudio.ua/"]}}';

    public function getClient(){
        $client = new Google_Client();
        $client->setApplicationName('GOOGLE API CALENDAR');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig(self::GOOGLE_JSON);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $tokenPath = './console/controllers/token.json';
       

        if (file_exists($tokenPath)) {
            $filecontent = file_get_contents($tokenPath);
            if(!empty($filecontent)){
                 $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }
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
        return $client;
    }

    public function actionGoogle()
    {
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);

        $order = Order::findOne(['order_id' => 1]);

        $date = \Yii::$app->formatter->asDate($order->date_due, 'yyyy-MM-dd');

        $calendarId = $this->getHall($order->orderProduct->product_id);

        foreach ($order->intervals as $interval) {
            $title = Interval::findOne(['code' => $interval->interval])->title;

            $dateStart = substr($title, 0, 5);
            $dateEnd = substr($title, 6, 5);
            $nextDay = null;

            if ($dateStart == "23:00") {
                $nextDay = date('Y-m-d', strtotime($date . ' + 1 days'));
            }

            $event = new \Google_Service_Calendar_Event(array(
                'summary' => $order->customer_firstname,
                'description' => 'Заказ №' . $order->order_id,
                'start' => array(
                    'dateTime' => $date . 'T' . $dateStart . ':00+03:00',

                ),
                'end' => array(
                    'dateTime' => $nextDay ? $nextDay . 'T' . $dateEnd . ':00+03:00' : $date . 'T' . $dateEnd . ':00+03:00',
                ),
                'reminders' => array(
                    'useDefault' => FALSE,
                ),
            ));
            $nextDay = null;

            $event = $service->events->insert($calendarId, $event);
            printf('Event created: %s' . PHP_EOL, $event->htmlLink);

        }

        // $calendarId = self::WHITE;

        // $client = $this->getClient();
        // $service = new Google_Service_Calendar($client);

        //      $t = date('Y-m-d');


        //     $event = new \Google_Service_Calendar_Event(array(
        //         'summary' => 'Test',
        //         'description' => 'Заказ TEST',
        //         'start' => array(
        //             'dateTime' => '2021-02-27' . 'T' . '08:00' . ':00+03:00',

        //         ),
        //                  'end' => array(
        //             'dateTime' => '2021-02-27' . 'T' . '09:00' . ':00+03:00',
        //         ),
        //         'reminders' => array(
        //             'useDefault' => TRUE,
        //         ),
        //     ));
        //     $nextDay = null;

        //     $event = $service->events->insert($calendarId, $event);
        //     printf('Event created: %s' . PHP_EOL, $event->htmlLink);
    }


    public function actionDeleteEvent($orderId){
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);
        $order = Order::findOne(['order_id' => $orderId]);
        $calendarId = $this->getHall($order->orderProduct->product_id);
        $date = \Yii::$app->formatter->asDate($order->date_due, 'yyyy-MM-dd');

        $events = $service->events->listEvents($calendarId,['timeMax' => date('Y-m-d', strtotime($date. ' + 1 days')) .'T23:00:00Z','timeMin' => $date .'T00:00:00Z',]);
        while (true) {
            foreach ($events->getItems() as $event) {
                if($service->events->get($calendarId,$event->getId())->getDescription() === 'Заказ №' . $order->order_id){
//                    echo 'Удалние...' . $event->getId() . PHP_EOL;
                    $service->events->delete($calendarId,$event->getId());
                }
            }
            $pageToken = $events->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $events = $service->events->listEvents($calendarId, $optParams);
            } else {
                break;
            }
        }

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