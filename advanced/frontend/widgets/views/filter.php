<?php
use common\models\EbayCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$urlToCats = \yii\helpers\Url::to(['getCats/']);
$currentCategory = (empty($model->queryCategory)) ? 6030 : $model->queryCategory;
$currentRootCategory = EbayCategory::find()->where(['category_id' => $currentCategory])->one()->category_root_parent;
switch($currentRootCategory) {
    case 6030: //Включена сортировка по возрастанию
        $currentRootCategory = 6001;
        break;
    case 10063:
        $currentRootCategory = 6024;
        break;
    case 100448:
        $currentRootCategory = 42595;
        break;
    case 43962:
        $currentRootCategory = 6723;
        break;
}
if(empty($model->queryCategory)) {
    $currentRootCategory = "";
}
echo '<div class="filter">';
$form = ActiveForm::begin([
        'id' => 'ebay-form',
        'action' => ['site/filter'],
]);

echo $form->field($model, 'queryText')->textInput(['value' => $model->queryText,  'placeholder' => 'Ищем запчасти...',])->label(false);

echo Html::submitButton('Поиск...', ['class' => 'btn btn-primary', 'name' => 'ebay-button']);

echo $form->field($model, 'queryFilterRoot')->dropDownList(ArrayHelper::map(
            $cats, 'category_id', 'category_name'),
        [
            'options' => [
                $currentRootCategory => ['Selected'=>'selected']
            ],
            'prompt' => 'Выберите тип ТС',
            'class' => 'form-control filter_query_input',
            'onchange' => '
                $.post("'.$urlToCats.'/'.'"+$(this).val(), function(data) {
                    $("select#ebayform-querybrand").html(data);
                    $("select#ebayform-querybrand").prepend(\'<option value="">Выберите марку</option>\');
                    $("select#ebayform-querymodel").html("");
                });'
        ]
)->label(false);
echo $form->field($model, 'queryBrand')->dropDownList(ArrayHelper::map(
            [], 'category_id', 'category_name'),
        [
            'options' => [
                $model->queryBrand => ['Selected'=>'selected']
            ],
                'prompt' => 'Выберите марку',
                'onload' => '
                    if($("select#ebayform-querybrand option:selected").data("catid") === undefined) {
                        $.post("'.$urlToCats.'/'.'"+$("select#ebayform-queryfilterroot").val(), function(data) {
                        $("select#ebayform-querybrand").html(data);
                        $("select#ebayform-querybrand").prepend(\'<option value="">Выберите марку</option>\');
                        $("select#ebayform-querymodel").html("");
                });
                    }
                ',
                'onchange' => '
                    $.post("'.$urlToCats.'/'.'"+$("select#ebayform-querybrand option:selected").data("catid"), function(data) {
                        $("select#ebayform-querymodel").html(data);
                        $("select#ebayform-querymodel").prepend(\'<option value="">Выберите модель</option>\');
                    });'
        ]
)->label(false);
echo $form->field($model, 'queryModel')->dropDownList(ArrayHelper::map(
            [], 'category_id', 'category_name'),
        [
            'options' => [
                $model->queryModel => ['Selected'=>'selected']
            ],
            'prompt' => 'Выберите модель',
        ]
)->label(false);

echo $form->field($model, 'querySort')->dropDownList(ArrayHelper::map($sorts, 'value','name'))->label(false);

echo $form->field($model, 'queryCategory')->hiddenInput(['value' => $currentCategory])->label(false);

echo Html::submitButton('Применить', ['class' => 'btn btn-primary', 'name' => 'ebay-button']);

ActiveForm::end();
echo '</div>';
