<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div id="recommendation-container">
    <h5>Рекомендации</h5>

    <?php $form = ActiveForm::begin([
        'id'      => 'recommendation-form',
        'action'  => Url::to(['/cart/recommendation']),
        // убираем data-pjax, нам не нужно
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
// Подключаем JS для показа/скрытия количества
$js = <<<JS
document.getElementById('curtain-checkbox').addEventListener('change', function(){
    document.getElementById('curtain-quantity-block')
        .style.display = this.checked ? 'block' : 'none';
});
JS;
$this->registerJs($js);
?>
