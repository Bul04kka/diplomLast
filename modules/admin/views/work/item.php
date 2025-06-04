<?php

use yii\bootstrap5\Html;
?>
<div class="card m-2" style="width: 16rem;">
    <div class="card-body">
        <h5 class="card-title"><?= \yii\helpers\Html::encode($model->name) ?></h5>
        <p class="card-text">
            <?= \yii\helpers\Html::encode($model->description) ?><br>
            <strong>Цена:</strong> <?= Yii::$app->formatter->asCurrency($model->price, 'RUB') ?>
        </p>
        <div class="d-grid gap-2">
        <?= Html::a('Подробно', ['work/view', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('Редактировать', ['work/update', 'id' => $model->id], ['class' => 'btn btn-warning btn-sm']) ?>
            <?= Html::a('Удалить', ['work/delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                    'method' => 'post',
                ],
            ]) ?>
            </div>
    </div>
</div>
