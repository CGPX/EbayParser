<?php
/**
 * Created by PhpStorm.
 * User: Hank
 * Date: 07.12.2015
 * Time: 9:48
 */

namespace frontend\models;

use common\models\Item;
use yii;
use yii\base\Model;

class OrderForm extends Model {

    public $name;
    public $email;
    public $phone;
    public $addres;
    public $body;
    public $region;
    public $index;
    public $city;
    public $itemslist;
    public $verifyCode;
    private $subject = 'Заказ';


    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'itemslist'], 'required'],
            [['body', 'region', 'index', 'city', 'addres'], 'default'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    public function sendEmail($email) {
        $text = '';
        $cart = json_decode($this->itemslist);
        foreach($cart as $item) {
            $ebayItem = Item::findOne(['ebay_item_id' =>$item[0]]);
            $text .= '<tr>'.' <td>'.$ebayItem['ebay_item_id'].'</td> '.'<td>'.$ebayItem['title'].'</td>'.' <td>'.$ebayItem['current_price_value'].'</td>'.'<td>'. $item[3] .'</td>>'.'<td>'.$ebayItem['current_price_value'] * $item[3].'</td><td>'.$ebayItem['viewItemURL'].'</td>></tr>';
        }
        return Yii::$app->mailer->compose('order-link', [
            'user' => Yii::$app->user->identity,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'addres' => $this->addres,
            'body' => $this->body,
            'region' => $this->region,
            'index' => $this->index,
            'city' => $this->city,
            'text' => $text,
        ])
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setSubject($this->subject)
            ->send();
    }

}