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
        <?= Html::button('Добавить в корзину', [
            'class' => 'btn btn-success btn-sm add-to-cart',
            'data-type' => 'work',
            'data-id' => $model->id,
        ]) ?>

        </div>
    </div>
</div>
