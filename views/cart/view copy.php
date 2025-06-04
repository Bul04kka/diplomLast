<?php
use yii\helpers\Html;

/** @var \app\models\Cart $cart */
?>

<?php if ($cart && $cart->cartItems): ?>
    <ul class="list-unstyled">
        <?php foreach ($cart->cartItems as $cartItem): ?>
            <?php
                switch ($cartItem->item_type) {
                    case 'product':
                        $item = \app\models\Product::findOne($cartItem->item_id);
                        break;
                    case 'work':
                        $item = \app\models\Work::findOne($cartItem->item_id);
                        break;
                    case 'service':
                        $item = \app\models\Service::findOne($cartItem->item_id);
                        break;
                    default:
                        $item = null;
                }

                if ($item):
                    $itemPrice = $item->price * $cartItem->quantity;
            ?>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2"><?= Html::encode($item->name) ?></span>
                    <span class="me-2">
                        | <span class="item-price" data-unit-price="<?= $item->price ?>" data-id="<?= $cartItem->id ?>">
                            <?= $itemPrice ?>
                        </span> ₽
                    </span>

                    <?php if ($cartItem->item_type === 'product'): ?>
                        <input type="number" min="1" value="<?= $cartItem->quantity ?>"
                            data-id="<?= $cartItem->id ?>"
                            class="form-control form-control-sm d-inline-block me-2 cart-quantity-input cart-quantity-field"
                            style="width: 80px;">
                    <?php else: ?>
                        <span class="me-2">Кол-во: <?= $cartItem->quantity ?></span>
                    <?php endif; ?>

                    <button type="button"
                        class="btn btn-danger btn-sm rounded-circle remove-cart-item"
                        data-id="<?= $cartItem->id ?>"
                        style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; margin-left: 20px;">
                        &times;
                    </button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <p class="mt-3 fw-bold">Итого: <span id="cart-total"><?= $cart->getTotalPrice() ?></span> ₽</p>
    <div class="mt-3">
        <?= Html::a('Оформить заказ', ['/cart/checkout'], ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Очистить корзину', ['class' => 'btn btn-secondary', 'id' => 'clear-cart-button']) ?>
    </div>

    <?php $this->registerJsFile('@web/js/cart.js', ['depends' => \yii\web\JqueryAsset::class]); ?>

<?php else: ?>
    <p>Ваша корзина пуста.</p>
<?php endif; ?>

