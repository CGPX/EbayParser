<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<?
$detailsTable = '<table>
                    <caption>
                        Клиент
                    </caption>
                    <tr>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Регион</th>
                        <th>Город</th>
                        <th>Индекс</th>
                        <th>Адрес</th>
                        <th>Примечание</th>
                    </tr>
                    <tr>
                        <td>'.$name.'</td>
                        <td>'.$email.' </td>
                        <td>'.$phone.' </td>
                        <td>'.$region.' </td>
                        <td>'.$city.' </td>
                        <td>'.$index.' </td>
                        <td>'.$addres.' </td>
                        <td>'.$body.' </td>
                    </tr>
                </table>';

?>
<?= $title = 'Ebay parts' ?>
<?= $content = $detailsTable .
    '<table><caption>
        Номенклатура
    </caption>
  <tr>
    <th>Наименование</th>
    <th>Цена</th>
    <th>Количество</th>
    <th>Сумма</th>
  </tr>'
    . $text .
    '</table>' ?>

