<?php
use yii\helpers\Html;

$this->title = "Заказ #{$order->id}";
$this->params['breadcrumbs'][] = ['label'=>'Все заказы','url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<p>
    <strong>Клиент:</strong>
    <?= Html::encode($order->user->getFullName()) ?>
</p>
<p>
    <strong>Дата:</strong>
    <?= Yii::$app->formatter->asDate($order->created_at,'php:d.m.Y') ?>
</p>
<p>
    <strong>Статус:</strong>
    <?= Html::encode($order->status->title) ?>
</p>
<p>
    <strong>Сумма:</strong>
    <?= Yii::$app->formatter->asDecimal($order->total_price,2) ?> ₽
</p>

<h3>Состав заказа</h3>
<table class="table">
    <thead>
      <tr>
        <th>Наименование</th><th>Кол-во</th><th>Цена</th><th>Сумма</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($order->orderItems as $item): ?>
        <?php if ($item->itemModel): ?>
            <?php $m = $item->itemModel; ?>
            <tr>
                <td><?= Html::encode($m->name) ?></td>
                <td><?= $item->quantity ?></td>
                <td><?= Yii::$app->formatter->asDecimal($item->price, 2) ?> ₽</td>
                <td><?= Yii::$app->formatter->asDecimal($item->price * $item->quantity, 2) ?> ₽</td>
            </tr>
        <?php else: ?>
            <tr class="table-warning">
                <td colspan="4">
                    <em>Элемент заказа удалён или недоступен. Обратитесь к клиенту администратором.</em>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>

    </tbody>
</table>

<p>
    <?php if ($order->status_id !== 2): // если ещё не «Утверждён» ?>
        <?= Html::a('Утвердить', ['approve', 'id' => $order->id], [
            'class' => 'btn btn-success',
            'data' => [
                'method'  => 'post',
                'confirm' => 'Вы уверены, что хотите утвердить заказ?',
            ],
        ]) ?>
    <?php endif; ?>

    <?php if ($order->status_id !== 3): // если ещё не «Отклонён» ?>
        <?= Html::a('Отклонить', ['reject', 'id' => $order->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'method'  => 'post',
                'confirm' => 'Вы уверены, что хотите отклонить заказ?',
            ],
        ]) ?>
    <?php endif; ?>

    <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
</p>
