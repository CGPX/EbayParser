<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Оформление заявки';

?>

<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">

                <!-- Данные заказчика на оформление -->
                <h4 class="text-uppercase"><small>Оформление заявки</small></h4>

                <div class="panel-group">
                    <div class="panel panel-default">
                        <div id="collapse1" class="panel-collapse collapse in">
                            <?php $form = ActiveForm::begin(['id' => 'order-form']); ?>
                            <?= $form->field($model, 'name') ?>
                            <?= $form->field($model, 'email') ?>
                            <?= $form->field($model, 'phone') ?>
                            <textarea class="form-control OrderForm-itemslist" rows="3" placeholder="Перечень товаров" name="OrderForm[itemslist]" style="display:none;"></textarea>

                            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
                            ]) ?>
                            <div class="form-group text-center">
                                <?= Html::submitButton('Оформить заявку', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-12">


                <!-- Данные корзины на оформление -->
                <h4><small>КОРЗИНА</small></h4>

                <div class="list-group">
                    <div class="panel panel-default">

                        <?= \Yii::$app->view->renderFile('@app/views/site/cart-inside.php'); ?>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>