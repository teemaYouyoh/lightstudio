<?php


namespace api\components;

use AmoCRM\Client;
use backend\models\Option;
use backend\models\Order;
use Yii;

class Crm {

    const RESERVED_PAYED = 21984616;

    const BRON = 30334255;

    const DATE_FIELD = 388437;
    const TIME_FIELD = 649435;
    const HALL_FIELD = 392471;
    const PROMOTE_FIELD = 392475;
    const PHONE_FIELD = 656647;
    const EMAIL_FIELD = 656649;
    const FIRST_LEAD = 21984610;
    const SCANDINAVIA = 703711;
    const LOFT = 703713;
    const WHITE = 703715;
    const FASHION = 703717;
    const OPENSPACE = 703719;
    const CICLORAMA = 703721;
    const GRIMERKA = 703723;

    public $customer;
    public $date;
    public $amount;
    public $crmId;
    public $hall;
    public $phone;
    public $email;
    public $dress;
    public $intervals;
    private $amocrm;
    private $crm_intervals = [];
    public $order_id;


    public function __construct()
    {
        $this->amocrm = Yii::$app->amocrm->getClient();
    }

    public function createOrder(){

        $account = $this->amocrm;
        $lead = $account->lead;
//        $lead->debug(false);
        $lead['status_id'] = self::RESERVED_PAYED;
        $lead['price'] = $this->amount;
        $lead['name'] = implode(' ',[$this->customer,Yii::$app->formatter->format($this->date, 'date') ,'Заказ #'.$this->order_id]);
        $lead['tags'] = ['Сайт'];
        $lead->addCustomField(self::DATE_FIELD,  Yii::$app->formatter->format($this->date, 'date'));
        $lead->addCustomField(self::PROMOTE_FIELD,  703733);
        $lead->addCustomField(self::PHONE_FIELD, $this->phone);
        $lead->addCustomField(self::EMAIL_FIELD,  $this->email);
        $lead->addCustomField(self::HALL_FIELD,[
            ['',$this->getHall()]
        ]);

        $this->setCrmInterlvals();

        $lead->addCustomField(self::TIME_FIELD, $this->crm_intervals );

        $options = $this->getOptions();


        if(!empty($options['persons'])){
            $lead->addCustomField(657691,  $options['persons']['description']);
        }

        if(!empty($options['light'])){

            if($options['light'] == 4) {
                $lead->addCustomField(242985, [
                    [
                        "",
                        475671
                    ]
                ] );
            }


            if($options['light'] == 5) {
                $lead->addCustomField(242985,  [
                    [
                        "",
                        475673
                    ]
                ] );
            }

            if($options['light'] == 7) {
                $lead->addCustomField(242985, [
                    [
                        "",
                        715875
                    ]
                ] );
            }
        }

        if(!empty($options['dressing'])){
            $lead->addCustomField(657315,  $options['dressing']['description']);
        }


        $id = $lead->apiAdd();
    }


    private function getOptions(){
        $result = null ;
        $order = Order::findOne(['order_id' => $this->order_id]);

        $result['persons'] = [];
        $result['light'] = '';
        $result['dressing'] = [];

        foreach ($order->options as $item){
            $option = Option::findOne(['option_id' => $item['option_id']]);
            switch ($option->option_group_id){
                case 1:
                    $result['persons']['id'] = $option->option_id;
                    $result['persons']['description'] =  $option->optionDescription->value;
                    break;
                case 2:
                    $result['light'] = $option->option_id;
                    break;
                case 3:
                    $result['dressing']['id'] =  $option->option_id;
                    $result['dressing']['description'] =  $option->optionDescription->value;
                    break;
            }
        }

        return $result;

    }


    public function createLead(){
       
        $account = $this->amocrm;
        $lead = $account->lead;
        $lead['status_id'] = self::FIRST_LEAD;
        $lead['name'] = implode(' ',[$this->customer,Yii::$app->formatter->asDate('now', 'dd-MM-YYYY')]);

        if(!empty($this->dress)){
            $lead['tags'] = ['Платье ' . $this->dress];
            $lead['price'] = $this->amount;
        } else {
             $lead['tags'] = ['Заявка сайт'];
        }

        // $lead['note_lead'] = 'tst';
        $lead->addCustomField(self::DATE_FIELD,  Yii::$app->formatter->asDate('now', 'dd-MM-YYYY'));
        $lead->addCustomField(self::PHONE_FIELD, $this->phone);
        $lead->addCustomField(self::EMAIL_FIELD,  $this->email);
        $id = $lead->apiAdd();
    }



    private function setCrmInterlvals(){
        foreach ($this->intervals as $value) {
            array_push( $this->crm_intervals,['', $this->getIntervlas($value->interval)]);
        }
    }

    private function getIntervlas($interval){
        switch ($interval){
            case 0:
                return 1092919;
                break;
            case 1:
                return 1092921;
                break;
            case 2:
                return 1092923;
                break;
            case 3:
                return 1092925;
                break;
            case 4:
                return 1092927;
                break;
            case 5:
                return 1092929;
            case 6:
                return 1092931;
            case 7:
                return 1092933;
            case 8:
                return 1092935;
            case 9:
                return 1092937;
            case 10:
                return 1092939;
            case 11:
                return 1092941;
            case 12:
                return 1092943;
            case 13:
                return 1092945;
            case 14:
                return 1092947;
            case 15:
                return 1092949;
            case 16:
                return 1092951;
            case 17:
                return 1092953;
            case 18:
                return 1092955;
            case 19:
                return 1092957;
            case 20:
                return 1092959;
            case 21:
                return 1092961;
            case 22:
                return 1092963;
            case 23:
                return 1092965;
            default:
                return false;
        }
    }


    private function getHall(){

        switch ($this->hall){
            case 1:
                return self::OPENSPACE;
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
                return self::SCANDINAVIA;
                break;
            case 19:
                return self::GRIMERKA;
            default:
                return false;
        }

    }

}