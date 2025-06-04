<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* проверяем, есть ли в корзине продукт ID=27 и работа ID=14 */
$hasProduct = false;
$hasWork    = false;
if ($cart) {
    foreach ($cart->cartItems as $item) {
        if ($item->item_type === 'product' && $item->item_id === 27) {
            $hasProduct = true;
        }
        if ($item->item_type === 'work' && $item->item_id === 14) {
            $hasWork = true;
        }
    }
}

if ($hasProduct && $hasWork): ?>

    <div class="alert alert-info">
        Рекомендация применена
    </div>

<?php else: ?>

    <div id="recommendation-container">
        <h5>Рекомендации</h5>

        <?php $form = ActiveForm::begin([
            'id'     => 'recommendation-form',
            'action' => Url::to(['/cart/recommendation']),
        ]); ?>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox"
                   id="curtain-checkbox" name="enable_curtains">
            <label class="form-check-label" for="curtain-checkbox">
                Хотите добавить модуль управления шторами и жалюзями?
            </label>
        </div>

        <div id="curtain-quantity-block" class="mb-3" style="display:none;">
            <?= Html::label('Укажите количество штор или жалюзей', 'curtains_quantity',
                ['class'=>'form-label']) ?>
            <?= Html::input('number','curtains_quantity',1,[
                'min'=>1,'class'=>'form-control','style'=>'width:100px;']) ?>
        </div>

        <div class="mb-3">
            <?= Html::submitButton('Применить рекомендации', [
                'class'=>'btn btn-primary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <?php
    $js = <<<JS
    document.getElementById('curtain-checkbox').addEventListener('change', function(){
        document.getElementById('curtain-quantity-block')
            .style.display = this.checked ? 'block' : 'none';
    });
    // AJAX-сабмит
    document.getElementById('recommendation-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this, container = document.getElementById('recommendation-container');
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.ok ? r.json() : Promise.reject(r))
        .then(json => {
            if (json.success) {
                container.innerHTML = '<div class="alert alert-success">Рекомендация применена</div>';
                if (typeof updateCartCount === 'function') updateCartCount();
            } else {
                alert(json.message || 'Ошибка');
            }
        })
        .catch(() => alert('Не удалось применить рекомендации. Попробуйте ещё раз.'));
    });
    JS;
    $this->registerJs($js);
    ?>

<?php endif; ?>
