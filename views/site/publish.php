<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Publish an item';
?>
<div class="site-publish">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
                <h1>Publish</h1>
                <p><?= $model->itemid ?></p>
                <?php $form = ActiveForm::begin(); ?>
                    <?php if(isset($firstImage['src'])): ?>
                        <p><img src="<?= $firstImage['src'] ?>" alt=""></p>
                    <?php endif?>
                    <?= $form->field($model, 'itemid')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'stackid')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'tags')->textInput() ?>
                    <?= $form->field($model, 'galleryids')->checkboxList($galleries) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <!-- <?= var_dump($model) ?> -->
    </div>
</div>
