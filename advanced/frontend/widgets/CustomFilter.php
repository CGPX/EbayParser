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
    public $modelRootId = 0;
    public $serCatId = 0;

    public function init() {

        if(isset($this->model->queryCategory)) {
            switch(EbayCategory::find()->where(['category_id' => $this->model->queryCategory])->one()->category_root_parent) {
                case 6030:
                    $this->modelRootId = 6001;
                    break;
                case 10063:
                    $this->modelRootId = 6024;
                    break;
                case 100448:
                    $this->modelRootId = 42595;
                    break;
                case 43962:
                    $this->modelRootId = 6723;
                    break;
            }
        }

        if(isset($this->model->queryBrand)) {
            $categoryBrand = EbayCategory::find()->where(['category_name' => $this->model->queryBrand, 'category_root_parent' => $this->modelRootId])->one();
            if($categoryBrand != false) {
                $this->modelCatId = $categoryBrand->category_id;
            }

        }
        if(isset($this->model->queryModel)) {
            $categoryModel =  EbayCategory::find()->where(['category_name' => $this->model->queryModel, 'category_root_parent' => $this->modelRootId])->one();
            if($categoryModel != false) {
                $this->serCatId = $categoryModel->category_id;
            }
        }
        $js = new JsExpression('
                        $(function() {
                            $(\'.categoryChange\').click(FilterControl.categoryAction);
                        });
                        var FilterControl = {
                        category: 0,
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
            'modelCatid' => $this->modelCatId,
            'serCatId' => $this->serCatId,
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
                'name' => 'Сортировать по убыванию',
                'value' => 0,
            ],
            [
                'name' => 'Сортировать по возрастанию',
                'value' => 1,
            ],
            [
                'name' => 'Наилучшее совпадение',
                'value' => 2,
            ],

        ];
        return $sorts;
    }

}

