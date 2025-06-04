<?php
use yii\helpers\Html;

$this->title = 'Заказ оформлен';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Ваш заказ #<?= Html::encode($order->id) ?> успешно оформлен.</p>

<p><?= Html::a('Вернуться к услугам', ['/account/service/index'], ['class' => 'btn btn-primary']) ?></p>
