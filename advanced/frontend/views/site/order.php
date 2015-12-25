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

                            <!-- Форма корзины START -->
                            <table class="table table-hover">
                                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                                <tr><td><input type="text" class="form-control" id="exampleInputEmail1" placeholder="ФИО" name="OrderForm[name]"></td></tr>
                                <tr><td><input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="OrderForm[email]"></td></tr>
                                <tr><td><input type="phone" class="form-control" id="exampleInputEmail1" placeholder="Телефон" name="OrderForm[phone]"></td></tr>
                                <tr><td><textarea class="form-control OrderForm-itemslist" rows="3" placeholder="Перечень товаров" name="OrderForm[itemslist]" style="display:none;"></textarea></p></td></tr>
                                <tr><td>
                                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                            'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
                                        ]) ?>
                                    </td></tr>
                                <tr><td>
                                        <div class="form-group text-center">
                                            <?= Html::submitButton('Оформить заявку', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
                                        </div>
                                    </td></tr>
                                <?php ActiveForm::end(); ?>
                            </table>

                            <!-- Форма корзины END -->



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