<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Publish Many';
?>
<div class="stash-publish-many">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
                <h1>Publish Stack</h1>
                <h2><?= $stashId ?></h2>
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'itemid')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'stackid')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'title')->textInput() ?>
                    <?= $form->field($model, 'artist_comments')->textInput() ?>
                    <?= $form->field($model, 'tags')->textInput() ?>
                    <p>tags: 3d girlfight honeyselect</p>
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
