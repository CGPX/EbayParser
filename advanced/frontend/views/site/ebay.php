<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Ebay parser';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="ebay-index">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
                    <?php foreach ($categories['autocat']->CategoryArray->Category as $category):?>
                        <li><a href="#"> <?php echo $category->CategoryName; ?> </a></li>
                    <?php endforeach ?>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">Запчасти мотоциклетные</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">Запчасти снегоходные</a></li>
            </ul>

<!--            --><?php //foreach ($list as $l):?>
<!--                --><?php //var_dump($l)?>
<!--            --><?php //endforeach?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>

            <div class="row">
                <div class="col-lg-5">
                    <?php $form = ActiveForm::begin(['id' => 'ebay-form']); ?>
                    <!---->
                    <?= $form->field($model, 'queryText') ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'ebay-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="row placeholders">
<!--                --><?//
//                if($model->ebayresult !== 'empty') {
//                    $rs = $model->ebayresult;
//                    echo $rs->paginationOutput->totalEntries;
//                    foreach($rs->searchResult->item as $item) {
//                        $pic   = $item->galleryURL;
//                        $link  = $item->viewItemURL;
//                        $title = $item->title;
//                        $price = $item->sellingStatus->currentPrice->value;
//                        $results .= '<div class="col-xs-6 col-sm-3 placeholders"><h4>'.$title.'</h4><img src='.$pic.' class="img-thumbnail"><p>'.$price.'</p><p><a class="btn btn-default" href="'.$link.'" role="button">Глянуть на ebay »</a></p></div>';
//                    }
//                    echo $results;
//                } else {
//                    echo 'Nothing to show...';
//                }
//                ?>
            </div>
        </div>
    </div>
</div>
