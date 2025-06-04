<?php
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Все заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions'  => ['class' => 'order-item mb-3 p-3 border rounded'],
    'itemView'     => function($model) {
        /* @var $model \app\models\Order */
        $date   = Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y');
        $sum    = Yii::$app->formatter->asDecimal($model->total_price, 2);
        //$status = Html::encode($model->status->title);
        $status = $model->status ? $model->status->title : '—';
        // ФИО клиента (предполагаем getFullName() или замените на нужные поля)
        $client = Html::encode($model->user->getFullName());

        // Ссылка на просмотр
        $link = Html::a("Заказ #{$model->id}", ['view', 'id' => $model->id], [
            'class' => 'fw-bold'
        ]);

        // Кнопки Утвердить / Отклонить
        $buttons = '';
        if ($model->status_id != 2) {
            $buttons .= Html::a('Утвердить', ['approve', 'id' => $model->id], [
                'class' => 'btn btn-success btn-sm me-1',
                'data'  => ['method' => 'post', 'confirm' => 'Уверены?']
            ]);
        }
        if ($model->status_id != 3) {
            $buttons .= Html::a('Отклонить', ['reject', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm',
                'data'  => ['method' => 'post', 'confirm' => 'Уверены?']
            ]);
        }

        return <<<HTML
<div>
    {$link} от {$date} — {$sum} ₽ — <em>{$status}</em><br>
    Клиент: {$client}<br>
    {$buttons}
</div>
HTML;
    },
    'layout' => "{items}\n{pager}",
    'summary' => false,
]) ?>
