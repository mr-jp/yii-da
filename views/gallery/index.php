<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Gallery Items';
?>
<div class="gallery-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>New Gallery</h2>
                <p>
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= $form->field($model, 'folder')->textInput() ?>
                            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </p>
                <h2>Galleries</h2>
                <?php if (sizeof($galleries) !== 0): ?>
                    <ol>
                        <?php foreach($galleries as $id => $title): ?>
                        <li><a href="<?= Yii::$app->urlManager->createUrl(['gallery/contents', 'id'=>$id]) ?>"><?= $title ?></a></li>
                        <?php endforeach?>
                    </ol>
                    <?php else: ?>
                        <p>No galleries available ...</p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
