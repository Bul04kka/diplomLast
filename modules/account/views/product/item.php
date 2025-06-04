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
           <?= Html::button('Добавить в корзину', [
            'class' => 'btn btn-success btn-sm add-to-cart',
            'data-type' => 'product',
            'data-id' => $model->id,
            ]) ?>




        </div>
    </div>
</div>


