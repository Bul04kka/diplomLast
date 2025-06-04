<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Order $model */

$this->title = "Заказ #{$order->userOrderNumber}";
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDate($order->created_at, 'php:d.m.Y') ?></p>

<p><strong>Сумма:</strong> <?= Yii::$app->formatter->asDecimal($order->total_price, 2) ?> ₽</p>

<p><strong>Статус:</strong>
    <?= Html::encode($order->status ? $order->status->title : '—') ?>
</p>
<h3>Позиции заказа</h3>
<table class="table">
    <thead>
        <tr>
            <th>Наименование</th>
            <th>Кол-во</th>
            <th>Цена за шт.</th>
            <th>Сумма</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($order->orderItems as $item): /** @var \app\models\OrderItem $item */ ?>
        <?php if ($item->itemModel): ?>
            <?php $m = $item->itemModel; ?>
            <tr>
                <td><?= Html::encode($m->name) ?></td>
                <td><?= Html::encode($item->quantity) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($item->price, 2) ?> ₽</td>
                <td><?= Yii::$app->formatter->asDecimal($item->quantity * $item->price, 2) ?> ₽</td>
            </tr>
        <?php else: ?>
            <tr class="table-warning">
                <td colspan="4">
                    <em>Элемент заказа был удалён или произошла ошибка. Пожалуйста, свяжитесь с администратором.</em>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>

<p>
    <?= Html::a('Вернуться к списку заказов', ['index'], ['class' => 'btn btn-secondary']) ?>
    <!-- <?= Html::a('Вернуться к услугам', ['/account/service/index'], ['class' => 'btn btn-primary']) ?> -->
</p>
