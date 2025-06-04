<?php

use app\models\Product;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
           <?= Html::a('Назад в лк', ['/account/default/index'], ['class' => 'btn btn-secondary']) ?>
    </p> -->

    
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['id' => 'product-list', 'enablePushState' => false]); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        // 'itemView' => function ($model, $key, $index, $widget) {
        //     return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
        // },
        'itemView' => 'item',
        'pager' => [
            'class' => LinkPager::class
        ],
        'layout' => "{summary}\n<div class='cards-wrapper'><div class='cards'>{items}</div></div>\n{pager}",

    ]) ?>
    <!-- СБРОС ОБРАБОТКИ ПЕРЕРИСОВКА СТРАНИЦЫ -->
    <?php Pjax::end(); ?>

</div>
<?php
$this->registerJsFile('@web/js/cart.js', ['depends' => \yii\web\JqueryAsset::class]);
?>

