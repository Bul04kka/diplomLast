<div class="account-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
<div class="d-flex flex-wrap gap-3">
    <?= \yii\helpers\Html::a('Товары', ['product/index'], ['class' => 'btn btn-primary w-25']) ?>
    <?= \yii\helpers\Html::a('Работы', ['work/index'], ['class' => 'btn btn-success w-25']) ?>
    <?= \yii\helpers\Html::a('Услуги', ['service/index'], ['class' => 'btn btn-info w-25']) ?>
</div>