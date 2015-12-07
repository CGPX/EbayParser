<?php
/**
 * Created by PhpStorm.
 * User: Hank
 * Date: 07.12.2015
 * Time: 9:48
 */

namespace frontend\models;

use yii;
use yii\base\Model;

class OrderForm extends Model {

    public $name;
    public $email;
    public $phone;
    public $addres;
    private $subject = 'Заказ';
    public $body;
    public $verifyCode;
    private $mailText;

    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone', 'body'], 'required'],
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

    public function sendEmail($email)
    {
        $this->mailText = 'Доброго времени суток, от '.$this->name.' поступил заказ '.$this->email.' '.$this->phone.' '.$this->addres;
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->mailText)
            ->send();
    }

}