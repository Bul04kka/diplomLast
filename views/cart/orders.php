<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => function($model) {
        /* @var $model \app\models\Order */
        return Html::a(
            "Заказ #{$model->id} от " . Yii::$app->formatter->asDatetime($model->created_at),
            ['cart/view-order', 'id' => $model->id],
            ['class' => 'order-link d-block mb-2']
        );
    },
    'summary' => false,
]) ?>
