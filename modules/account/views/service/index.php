<?php

use app\models\Service;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\account\models\ServiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
           <?= Html::a('Назад в админку', ['/account/default/index'], ['class' => 'btn btn-secondary']) ?>
    </p> -->

    <?php Pjax::begin(['id' => 'service-list', 'enablePushState' => false]); ?>
    <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => 'item',
        'pager' => [
            'class' => LinkPager::class
        ],
         'layout' => "{summary}\n<div class='cards-wrapper'><div class='cards'>{items}</div></div>\n{pager}",
    ]) ?>

    <?php Pjax::end(); ?>

</div>
<?php
$this->registerJsFile('@web/js/cart.js', ['depends' => \yii\web\JqueryAsset::class]);
?>
<div>
        <?php
    use app\models\Cart;
    /* получим модель корзины текущего пользователя */
    $cart = Cart::findOne(['user_id' => Yii::$app->user->id]);
    echo $this->render('recommendation', [
        'cart' => $cart,
    ]);
    ?>

    <?php $this->registerJsFile(
    '@web/js/recommendations.js',
    ['depends'=>\yii\web\JqueryAsset::class]
); ?>

</div>

