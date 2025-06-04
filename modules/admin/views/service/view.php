<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Service $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


?>
<div class="service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту услугу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'description:ntext',
        'price',
        'created_at',
        'updated_at',

        // Товары в составе услуги
        [
            'label' => 'Товары в составе',
            'format' => 'raw',
            'value' => function($model) {
                if (empty($model->serviceProducts)) {
                    return '<span class="text-muted">Товары не указаны, возможно вы удалили товары</span>';
                }
                $list = '<ul style="list-style: none; padding-left: 0;">';
                foreach ($model->serviceProducts as $serviceProduct) {
                    $list .= '<li>' . Html::encode($serviceProduct->product->name) . ' - Кол-во: ' . $serviceProduct->quantity . '</li>';
                }
                $list .= '</ul>';
                return $list;
            },
        ],

        // Работы в составе услуги
        [
            'label' => 'Работы в составе',
            'format' => 'raw',
            'value' => function($model) {
                if (empty($model->serviceWorks)) {
                    return '<span class="text-muted">Работы не указаны,  возможно вы удалили работы</span>';
                }
                $list = '<ul style="list-style: none; padding-left: 0;">';
                foreach ($model->serviceWorks as $serviceWork) {
                    $list .= '<li>' . Html::encode($serviceWork->work->name) . '</li>';
                }
                $list .= '</ul>';
                return $list;
            },
        ],
    ],
]) ?>

    
</div>
