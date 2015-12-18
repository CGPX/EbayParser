<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Оформление заявки';

?>
<div class="col-sm-2">

</div>
<div class="col-sm-8">


    <div class="row">
        <div class="col-md-4">

            <!-- Данные заказчика на оформление -->
            <h4 class="text-uppercase"><small><?= Html::encode($this->title) ?></small></h4>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div id="collapse1" class="panel-collapse collapse in">

                        <!-- Форма корзины START -->
                        <table class="table table-hover">
                            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                            <tr><td><input type="text" class="form-control" id="exampleInputEmail1" placeholder="ФИО" name="OrderForm[name]"></td></tr>
                            <tr><td><input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="OrderForm[email]"></td></tr>
                            <tr><td><input type="phone" class="form-control" id="exampleInputEmail1" placeholder="Телефон" name="OrderForm[phone]"></td></tr>
                            <tr><td><input type="text" class="form-control" id="exampleInputEmail1" placeholder="Регион" name="OrderForm[region]"></td></tr>
                            <tr><td><input type="text" class="form-control" id="exampleInputEmail1" placeholder="Город" name="OrderForm[city]"></td></tr>
                            <tr><td><input type="text" class="form-control" id="exampleInputEmail1" placeholder="Индекс" name="OrderForm[index]"></td></tr>
                            <tr><td><input type="text" class="form-control" id="exampleInputEmail1" placeholder="Адрес" name="OrderForm[addres]"></td></tr>
                            <tr><td><textarea class="form-control" rows="3" placeholder="Дополнительная информация" name="OrderForm[body]"></textarea></td></tr>
                            <tr><td><textarea class="form-control OrderForm-itemslist" rows="3" placeholder="Перечень товаров" name="OrderForm[itemslist]" style="display:none;"></textarea></p></td></tr>

                            <tr><td>
                                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                        'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
                                    ]) ?>
                                </td></tr><tr><td>
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
        <div class="col-md-8">

            <!-- Данные корзины на оформление -->
            <h4><small>КОРЗИНА</small></h4>



                        <?php
                        $this->beginContent('@frontend/views/site/cart.php');
                        echo $content;
                        $this->endContent();
                        ?>
<?php /*
             <div class="panel-group">
                <div class="panel panel-default">
                    <div id="collapse1" class="panel-collapse collapse in">

                        <!-- Форма корзины START -->
                        <table class="table table-hover">

                            <tr>
                                <td><a href="#">Левая дверь Mazda rx7</a></td>
                                <td style="width:350px; max-width: 1000px;">
                                    <div class="input-group">
                                        <div class="input-group-addon">600 р.</div>
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-menu-left"></span></button>
                                        </div>
                                        <input name="quantity" type="text" value="1" min="1" max="9999" maxlength="5" class="form-control" autocomplete="off" />
                                        <div class="input-group-addon">600 р.</div>
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-menu-right"></span></button>
                                            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-remove"></span></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </table>

                        <div class="panel-footer"><strong>ИТОГ: 1200р. </strong></div>
                        <!-- Форма корзины END -->

                     </div>
                </div>
            </div>
*/?>



        </div>
    </div>


</div>
<div class="col-sm-2">

</div>

