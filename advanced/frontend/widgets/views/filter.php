<?php
use common\models\EbayCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'action' => ['site/filter'],
]);

echo $form->field($model, 'queryFilterRoot')->dropDownList(ArrayHelper::map(
            $cats, 'category_id', 'category_name'),
        [
            'prompt' => 'Выберите тип ТС',
            'onchange' => '
                $.post("cats/'.'"+$(this).val(), function(data) {
                    $("select#ebayform-querybrand").html(data);
                    $("select#ebayform-querybrand").prepend(\'<option value="">Выберите марку</option>\');
                    $("select#ebayform-querymodel").html("");
                });'
        ]
)->label("");
echo $form->field($model, 'queryBrand')->dropDownList(ArrayHelper::map(
            [], 'category_id', 'category_name'),
        [
                'prompt' => 'Выберите марку',
                'onchange' => '
                    $.post("cats/'.'"+$(this).val(), function(data) {
                        $("select#ebayform-querymodel").html(data);
                        $("select#ebayform-querymodel").prepend(\'<option value="">Выберите модель</option>\');
                    });'
        ]
)->label("");
echo $form->field($model, 'queryModel')->dropDownList(ArrayHelper::map(
            [], 'category_id', 'category_name'),
        [
            'prompt' => 'Выберите модель',
        ]
)->label("");

echo $form->field($model, 'querySort')->dropDownList(ArrayHelper::map($sorts, 'value','name'))->label("");

echo Html::submitButton('Применить', ['class' => 'btn btn-primary', 'name' => 'blog-button']);

ActiveForm::end();
