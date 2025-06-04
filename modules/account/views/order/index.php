<?php

use app\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'     => function($model) {
        /* @var $model \app\models\Order */
        $num    = $model->userOrderNumber;
        $date   = Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y');
        $sum    = Yii::$app->formatter->asDecimal($model->total_price, 2);
        $status = $model->status ? $model->status->title : '—';
        
        // Ссылка только на «Заказ №...»
        $link = Html::a(
            "Заказ №{$num}",
            ['view', 'id' => $model->id],
            ['class' => 'me-1'] // немного отступа
        );
        
        // Оставшийся текст
        $text = "от {$date} — {$sum} ₽ — Статус: {$status}";
        
        return $link . Html::encode($text);
    },
    'summary' => false,
]) ?>
