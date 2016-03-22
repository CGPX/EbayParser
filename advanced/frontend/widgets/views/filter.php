<?php
use common\models\EbayCategory;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;


$form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['site/action'],
]);

echo $form->field($model, 'queryCategory')->dropDownList(ArrayHelper::map(
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
);
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
);
echo $form->field($model, 'queryModel')->dropDownList(ArrayHelper::map(
            [], 'category_id', 'category_name'),
        [
            'prompt' => 'Выберите модель',
        ]
);

echo $form->field($model, 'querySort')->dropDownList(ArrayHelper::map($sorts, 'value','name'));

ActiveForm::end();
