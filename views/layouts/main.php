<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\web\View;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);

$cart = !Yii::$app->user->isGuest ? \app\models\Cart::findOne(['user_id' => Yii::$app->user->id]) : null;
$cartCount = $cart ? $cart->getTotalQuantity() : 0;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php

    NavBar::begin([
        'brandLabel' => Html::tag('span', Html::encode(Yii::$app->name), [
                'class' => 'brand-text'
            ]) .
            Html::img('@web/pics/logo.png', [
                'alt' => 'Логотип',
                'class' => 'brand-logo'
            ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top'],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => array_filter([
            ['label' => 'Главная', 'url' => ['/site/index']],
            !Yii::$app->user->isGuest && !Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Товары', 'url' => ['/account/product/index']] : null,
            !Yii::$app->user->isGuest && !Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Работы', 'url' => ['/account/work/index']] : null,
            !Yii::$app->user->isGuest && !Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Услуги', 'url' => ['/account/service/index']] : null,
            Yii::$app->user->isGuest ? ['label' => 'Регистрация', 'url' => ['/site/register']] : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Панель администратора', 'url' => ['/admin']] : null,
            // !Yii::$app->user->isGuest && !Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Панель клиента', 'url' => ['/account']] : null,
            !Yii::$app->user->isGuest && !Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Заказы', 'url' => ['/account/order/index']] : null,
            !Yii::$app->user->isGuest && Yii::$app->user->identity->getIsAdmin() ? ['label' => 'Заказы клиентов', 'url' => ['/admin/order/index']] : null,

            !Yii::$app->user->isGuest && !Yii::$app->user->identity->getIsAdmin()
                ? '<li class="nav-item">'
                    . Html::button(
                        'Корзина (<span id="cart-count">' . $cartCount . '</span>)',
                        ['id' => 'cart-button', 'class' => 'nav-link btn btn-link']
                    )
                    . '</li>'
                : null,
            Yii::$app->user->isGuest
                ? ['label' => 'Войти', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton('Выйти (' . Yii::$app->user->identity->login . ')', ['class' => 'nav-link btn btn-link'])
                    . Html::endForm()
                    . '</li>',
        ]),
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Домологика <?= date('Y') ?></div>
            <!-- <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div> -->
        </div>
    </div>
</footer>

<?php
Modal::begin([
    'id' => 'cart-modal',
    'title' => 'Ваша корзина',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
]);
echo '<div id="cart-modal-content"></div>';
Modal::end();

$cartUrl = Url::to(['/cart/view']);
$js = <<<JS
$('#cart-button').on('click', function() {
    $('#cart-modal-content').load('$cartUrl', function() {
        if (typeof bindCartButtons === 'function') bindCartButtons();
        if (typeof recalculateCart === 'function') recalculateCart();
        if (typeof updateCartCount === 'function') updateCartCount();
        $('#cart-modal').modal('show');
    });
});
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
