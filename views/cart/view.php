<?php
use yii\helpers\Html;

/** @var \app\models\Cart $cart */
?>

<?php if ($cart && $cart->cartItems): ?>
    <ul class="list-unstyled">
        <?php foreach ($cart->cartItems as $ci): 
    switch ($ci->item_type) {
        case 'product':
            $item = \app\models\Product::findOne($ci->item_id); break;
        case 'work':
            $item = \app\models\Work::findOne($ci->item_id);    break;
        case 'service':
            $item = \app\models\Service::findOne($ci->item_id); break;
    }
    if (!$item) continue;
    // корпус строки:
    $unitPrice = $item->price;
    $qty       = $ci->quantity;
    $lineTotal = $unitPrice * $qty;
?>
<div class="d-flex align-items-center mb-2">
    <span class="me-2"><?= Html::encode($item->name) ?></span>
    <span class="me-2">
        | 
        <span 
            class="item-price" 
            data-unit-price="<?= $unitPrice ?>" 
            data-quantity="<?= $qty ?>" 
            data-id="<?= $ci->id ?>"
        ><?= $lineTotal ?></span> ₽
    </span>

    <?php if ($ci->item_type === 'product'): ?>
        <input 
            type="number" min="1" 
            value="<?= $qty ?>" 
            data-id="<?= $ci->id ?>" 
            class="form-control form-control-sm d-inline-block me-2 cart-quantity-input"
            style="width: 80px;"
        >
    <?php else: ?>
        <span class="me-2">×<?= $qty ?></span>
    <?php endif; ?>

    <button 
        type="button" 
        class="btn btn-danger btn-sm rounded-circle remove-cart-item" 
        data-id="<?= $ci->id ?>" 
        style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;margin-left:20px;"
    >&times;</button>
</div>
<?php endforeach; ?>

    </ul>

    <p class="mt-3 fw-bold">Итого: <span id="cart-total"><?= $cart->getTotalPrice() ?></span> ₽</p>
    <div class="mt-3">
        <!-- <?= Html::a('Оформить заказ', ['/cart/checkout'], ['class' => 'btn btn-primary']) ?> -->

        <?php if ($cart && count($cart->cartItems) > 0): ?>
            <?= Html::a('Оформить заказ', ['/account/order/create'], ['class'=>'btn btn-success']) ?>
        <?php endif; ?>



        <?= Html::button('Очистить корзину', ['class' => 'btn btn-secondary', 'id' => 'clear-cart-button']) ?>
    </div>

    <?php $this->registerJsFile('@web/js/cart.js', ['depends' => \yii\web\JqueryAsset::class]); ?>

<?php else: ?>
    <p>Ваша корзина пуста.</p>
<?php endif; ?>

