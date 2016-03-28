<?php
namespace frontend\widgets;

use common\models\EbayCategory;
use Yii;
use yii\base\Widget;
use yii\web\JsExpression;
use yii\web\View;

class CustomFilter extends Widget
{
    public $model;
    public $currentCategory;
    public $jsCodeKey = 'filter';
    public $modelCatId = 0;
    public function init() {

        if(isset($this->model->queryBrand)) {
            $this->modelCatId = EbayCategory::find()->where(['category_name' => $this->model->queryBrand])->one()->category_id;
        }

        $js = new JsExpression('
                        $(function() {
                            $(\'.categoryChange\').click(FilterControl.categoryAction);
                        });
                        var FilterControl = {
                        category: 0,
                        page: 1,
                        sort: 0,
                        brand: "'.$this->model->queryBrand.'",
                        model: "",
                        text: "",


                        getParamsFromHref: function() {
                          var check = true;
                          tsTypeSelect = $("select#ebayform-queryfilterroot");
                          FilterControl.getDataMotherFucka(tsTypeSelect);
                          FilterControl.getDataMotherFucka2();
                        },

                    getDataMotherFucka: function(sel) {
                        ebayformquerybrand = $("select#ebayform-querybrand");
                        $.post("/getCats/"+sel.val(), function(data) {
                            ebayformquerybrand.html(data);
                            ebayformquerybrand.prepend(\'<option value="">Выберите марку</option>\');
                            $("select#ebayform-querybrand option").filter(\'[value="'.$this->model->queryBrand.'"]\').attr("selected", "selected")
                        });
                    },
                     getDataMotherFucka2: function() {
                        querymodel = $("select#ebayform-querymodel");
                        $.post("/getCats/'. $this->modelCatId .'", function(data) {
                            querymodel.html(data);
                            querymodel.prepend(\'<option value="">Выберите марку</option>\');
                            $("select#ebayform-querymodel option").filter(\'[value="'.$this->model->queryModel.'"]\').attr("selected", "selected")
                        });
                    },
                     categoryAction: function() {
                            categoryId = +$(this).attr(\'data-target\');
                            $("#ebayform-querycategory").val(categoryId);
                            $("#ebay-form").submit();
                    },
                    };
                    window.onload = FilterControl.getParamsFromHref;
        ');
        $this->view->registerJs($js, View::POS_END, $this->jsCodeKey);
    }

    public function run()
    {
        return $this->render('filter', [
            'model' => $this->model,
            'cats' => $this->getBasicCategoryArray(),
            'sorts' => $this->getSortOptions(),
        ]);
    }

    private function getBasicCategoryArray() {
        $categorys = [
            [
                'category_name' => 'Автомобили',
                'category_id' => 6001,
            ],
            [
                'category_name' => 'Мотоциклы',
                'category_id' => 6024,
            ],
            [
                'category_name' => 'Снегоходы',
                'category_id' => 42595,
            ],
            [
                'category_name' => 'Квадроциклы',
                'category_id' => 6723,
            ],
        ];
        return $categorys;
    }

    private function getSortOptions() {
        $sorts = [
            [
                'name' => 'Наилучшее совпадение',
                'value' => 2,
            ],
            [
                'name' => 'Сортировать по возрастанию',
                'value' => 0,
            ],
            [
                'name' => 'Сортировать по убыванию',
                'value' => 1,
            ],

        ];
        return $sorts;
    }

}

