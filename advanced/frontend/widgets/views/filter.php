<?php
use common\models\EbayCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$urlToCats = \yii\helpers\Url::to(['getCats/']);
$currentCategory = (empty($model->queryCategory)) ? 6030 : $model->queryCategory;
$currentRootCategory = EbayCategory::find()->where(['category_id' => $currentCategory])->one()->category_root_parent;
switch($currentRootCategory) {
    case 6030:
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



echo $form->field($model, 'queryFilterRoot')->dropDownList(ArrayHelper::map(
            $cats, 'category_id', 'category_name'),
        [
            'options' => [
                $currentRootCategory => ['Selected'=>'selected']
            ],
            'prompt' => 'Выберите тип ТС',
            'class' => 'form-control filter_query_input hidden',
            'onchange' => '
                $.post("'.$urlToCats.'/'.'"+$(this).val(), function(data) {
                    $("select#ebayform-querybrand").html(data);
                    $("select#ebayform-querybrand").prepend(\'<option value="">Выберите марку</option>\');
                    $("select#ebayform-querymodel").html("");
                });
                    switch($(this).val()) {
                        case "6001":
                        $("#ebayform-querycategory").val(6030);
                        break
                        case "6024":
                        $("#ebayform-querycategory").val(10063);
                        break
                        case "42595":
                        $("#ebayform-querycategory").val(100448);
                        break
                        case "6723":
                        $("#ebayform-querycategory").val(43962);
                        break
                    }
                '
        ]
)->label(false);


echo $form->field($model, 'queryBrand')->dropDownList(ArrayHelper::map(
            EbayCategory::find()->where(['category_parent_id'=>$currentRootCategory])->all(), 'category_id', 'category_name'),
        [
            'options' => [
                $modelCatid  => ['Selected'=>'selected']
            ],
                'prompt' => 'Выберите марку',
                'onchange' => '
                    $.post("'.$urlToCats.'/'.'"+$(this).val(), function(data) {
                        $("select#ebayform-querymodel").html(data);
                        $("select#ebayform-querymodel").prepend(\'<option value="">Выберите модель</option>\');
                        $("select#ebayform-querymodel").prop("disabled", false)
                    });'
        ]
)->label(false);
if(empty($model->queryModel)) {
        $disabled = ['disabled' => 'disabled'];
    }
    else {
        $disabled = ['disabled' => 'false'];
    }
echo $form->field($model, 'queryModel')->dropDownList(ArrayHelper::map(
    EbayCategory::find()->where(['category_parent_id' => $modelCatid])->all(), 'category_id', 'category_name'),
        [
            'options' => [
                $serCatId => ['Selected'=>'selected']
            ],
            'prompt' => 'Выберите модель',
            $disabled['disabled'] => $disabled['disabled'],
        ]
)->label(false);

echo $form->field($model, 'querySort')->dropDownList(ArrayHelper::map($sorts, 'value','name'),
    [

    ])->label(false);

echo $form->field($model, 'queryCategory')->hiddenInput(['value' => $currentCategory])->label(false);

echo Html::submitButton('Применить', ['class' => 'btn btn-primary', 'name' => 'ebay-button']);

ActiveForm::end();
echo '</div>';
