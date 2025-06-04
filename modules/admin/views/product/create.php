<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = 'Создание товара';
// $this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
