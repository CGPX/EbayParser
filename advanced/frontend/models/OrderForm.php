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
    private $subject = 'Заказ';
    public $body;
    public $itemslist;
    public $verifyCode;
    private $mailText;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone', 'body', 'itemslist'], 'required'],
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
        $ids = explode(",", $this->itemslist);
        $items = Item::find()->where(['ebay_item_id' => $ids])->asArray()->all();
        $text = '';
        foreach ($items as $item) {
            $text .= $item['title'].' сумма: '.$item['current_price_value'] .' ';
        }

        $this->mailText = 'Доброго времени суток, от '.$this->name.' поступил заказ '.$this->email.' '.$this->phone.' '.$this->addres.' '.$text.' ';
        return Yii::$app->mailer->compose()
            ->setTo('null@binaryworld.ru')
            ->setFrom('satan1988@list.ru')
            ->setSubject($this->subject)
            ->setTextBody($this->mailText)
            ->send();
    }

}