<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Product;
use app\models\Work;

/** @var yii\web\View $this */
/** @var app\models\Service $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="service-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Название и описание услуги -->
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
    // Подключение JS
    $this->registerJsFile('@web/js/product-selection.js', ['depends' => \yii\web\JqueryAsset::class]);
    $this->registerJsFile('@web/js/work-selection.js', ['depends' => \yii\web\JqueryAsset::class]);

    // Данные товаров
    $products = Product::find()->select(['id', 'name'])->asArray()->all();
    $productDataJson = Json::encode(ArrayHelper::map($products, 'id', 'name'));

    // Данные работ
    $works = Work::find()->select(['id', 'name'])->asArray()->all();
    $workDataJson = Json::encode(ArrayHelper::map($works, 'id', 'name'));
    ?>


    <!-- ПОКАЗАТЬ СУЩЕСТВУЮЩИЕ ТОВАРЫ В УСЛУГЕ -->
    <div id="selected-products">
        <?php foreach ($model->serviceProducts as $serviceProduct): ?>
            <div class="form-group" id="product-row-<?= $serviceProduct->product_id ?>">
                <label><?= Html::encode($serviceProduct->product->name) ?></label>
                <input type="hidden" name="Service[product_selection][<?= $serviceProduct->product_id ?>][id]" value="<?= $serviceProduct->product_id ?>">
                <input type="number" name="Service[product_selection][<?= $serviceProduct->product_id ?>][quantity]" value="<?= $serviceProduct->quantity ?>" min="1" style="width:100px; display:inline-block;">
                <button type="button" class="btn btn-danger btn-sm remove-product" data-id="<?= $serviceProduct->product_id ?>">Удалить</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Выбор товара -->
    <div class="form-group">
        <?= Html::dropDownList('product_selector', null,
            ArrayHelper::map($products, 'id', 'name'),
            [
                'prompt' => 'Выберите товар',
                'id' => 'product-selector',
                'class' => 'form-control',
                'data-products' => $productDataJson
            ]
        ) ?>
    </div>
    <div id="selected-products"></div>

    <hr>
    <!-- ПОКАЗАТЬ СУЩЕСЬВУЮЩИЕ РАБОТЫ В УСЛУГЕ -->
     <div id="selected-works">
        <?php foreach ($model->serviceWorks as $serviceWork): ?>
            <div class="form-group" id="work-row-<?= $serviceWork->work_id ?>">
                <label><?= Html::encode($serviceWork->work->name) ?></label>
                <input type="hidden" name="Service[work_selection][<?= $serviceWork->work_id ?>]" value="<?= $serviceWork->work_id ?>">
                <button type="button" class="btn btn-danger btn-sm remove-work" data-id="<?= $serviceWork->work_id ?>">Удалить</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Выбор работы -->
    <div class="form-group">
        <?= Html::dropDownList('work_selector', null,
            ArrayHelper::map($works, 'id', 'name'),
            [
                'prompt' => 'Выберите работу',
                'id' => 'work-selector',
                'class' => 'form-control',
                'data-works' => $workDataJson
            ]
        ) ?>
    </div>
    <div id="selected-works"></div>

    <div class="form-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
