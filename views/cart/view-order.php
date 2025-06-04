<?php
use yii\helpers\Html;

/* @var $order app\models\Order */
$this->title = "Заказ #{$order->id}";
?>
<h1><?= Html::encode($this->title) ?></h1>
<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDatetime($order->created_at) ?></p>
<p><strong>Сумма:</strong> <?= Yii::$app->formatter->asDecimal($order->total_price, 2) ?> ₽</p>

<h3>Позиции заказа</h3>
<ul>
    <?php /** @var \app\models\OrderItem $item */ ?>
    <?php foreach ($order->orderItems as $item): ?>
        <?php $model = $item->itemModel; ?>
        <li>
            <?php if ($model): ?>
                <?= Html::encode($model->name) ?> 
                — <?= Html::encode("{$item->quantity} шт. × {$item->price} ₽") ?>
            <?php else: ?>
                <?= Html::encode("{$item->item_type} #{$item->item_id}") ?> 
                — <?= Html::encode("{$item->quantity} шт. × {$item->price} ₽") ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
