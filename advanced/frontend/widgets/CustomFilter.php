<?php
namespace frontend\widgets;

use frontend\widgets\models\CustomFilterModel;
use Yii;
use yii\base\Widget;

class CustomFilter extends Widget
{
    public $model;
    public $currentCategory;

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

