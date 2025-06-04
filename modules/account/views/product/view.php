<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sku',
            'name',
            'brand',
            'description:ntext',
            'price',
            'quantity',
           [
                'attribute' => 'image_url',
                'format' => 'html',
                'value' => function($model) {
                    return Html::img($model->image_url, ['class' => 'w-25', 'alt' => 'photo']);
                },
            ],
            'created_at',
            'updated_at',
            'attributes:ntext',
        ],
    ]) ?>

</div>
