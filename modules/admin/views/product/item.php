<?php
use yii\bootstrap5\Html;
?>

<div class="card m-2" style="width: 18rem;">
    <?= Html::img($model->image_url ?: '/uploads/products/placeholder.png', [
        'class' => 'card-img-top',
        'alt' => Html::encode($model->name)
    ]) ?>
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->name) ?></h5>
        <p class="card-text">
            Производитель: <?= Html::encode($model->brand) ?><br>
            Цена: <?= Yii::$app->formatter->asCurrency($model->price, 'RUB') ?>
        </p>
        <div class="d-grid gap-2">
            <?= Html::a('Подробно', ['product/view', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::a('Редактировать', ['product/update', 'id' => $model->id], ['class' => 'btn btn-warning btn-sm']) ?>
            <?= Html::a('Удалить', ['product/delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>


