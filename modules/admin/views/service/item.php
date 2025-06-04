<?php
use yii\helpers\Html;

/** @var \app\models\Service $model */
?>

<div class="card p-3 mb-3 border rounded">
    <h4><?= Html::encode($model->name) ?></h4>
    <p><?= Html::encode($model->description) ?></p>

    <h5>Что входит в услугу:</h5>

    <!-- Товары -->
    <?php if (!empty($model->serviceProducts)): ?>
        <strong>Товары:</strong>
        <ul style="list-style: none; padding-left: 0;">
            <?php foreach ($model->serviceProducts as $serviceProduct): ?>
                <li>
                    <?= Html::encode($serviceProduct->product->name) ?> — Кол-во: <?= $serviceProduct->quantity ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><em>Товары не указаны, возможно вы удалили товар</em></p>
    <?php endif; ?>

    <!-- Работы -->
    <?php if (!empty($model->serviceWorks)): ?>
        <strong>Работы:</strong>
        <ul style="list-style: none; padding-left: 0;">
            <?php foreach ($model->serviceWorks as $serviceWork): ?>
                <li><?= Html::encode($serviceWork->work->name) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <strong>Работы:</strong>
        <p><em>Работы не указаны, возможно вы удалили работу</em></p>
    <?php endif; ?>

    <strong>Итоговая цена: <?= Yii::$app->formatter->asCurrency($model->price, 'RUB') ?></strong>

    <div class="mt-2">
        <?= Html::a('Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-warning btn-sm']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-sm',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту услугу?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>










