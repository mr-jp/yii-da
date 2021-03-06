<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */

$this->title = 'Publish Many';

// Add the javascript
$this->registerJsFile(
    '@web/js/publish-many.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);

// stackids in comma delimited form
$stackids = implode(',', array_map(function($item) {return $item->stackid;}, $items));
$stackcount = sizeof($items);
$currentstack = 0;  // used for progress bar
?>
<div class="stash-publish-many">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <p>
                    <div id="progress-bar-div" class="progress hidden">
                        <div id="upload-progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>

                        <input type="hidden" name="stackcount" value="<?= $stackcount ?>">
                        <input type="hidden" name="currentstack" value="<?= $currentstack ?>">
                        <input type="hidden" name="stackids" value="<?= $stackids ?>" class="form-control" disabled=disabled>
                    </div>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h2>Publish Stack</h2>
                <h3><?= $stashId ?></h3>
                <!-- <p><?= $stackids ?></p> -->
                <?php $form = ActiveForm::begin([
                    'action' => ['stash/publish-ajax'],
                    'options' => [
                        'class' => 'publish-many-form'
                    ]
                ]); ?>
                    <?= $form->field($model, 'itemid')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'stackid')->hiddenInput()->label(false) ?>
                    <!-- <?= $form->field($model, 'title')->textInput() ?> -->
                    <?= $form->field($model, 'artist_comments')->textArea(['rows'=>10]) ?>
                    <?= $form->field($model, 'tags')->textInput() ?>
                    <?= $form->field($model, 'galleryids')->checkboxList($galleries) ?>
                    <div class="form-group">
                        <!-- <a href="#" id="publish-all-button" class="btn btn-success">Publish All</a> -->
                        <?= Html::submitButton('Publish All', ['class' => 'btn btn-success']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
            <!-- <div class="col-lg-6 console-output-div">
                <pre id="console-output" class="xdebug-var-dump" dir="ltr">Waiting for input ...</pre>
            </div> -->
            <div class="col-lg-6">
                <ol>
                    <?php foreach($items as $item): ?>
                        <li id="<?= $item->stackid ?>"><?= $item->stackid ?></li>
                    <?php endforeach ?>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <!-- <p>Items to be published: <span id="item-count"><?= $stackcount ?></span></p> -->
                <div class="form-group">

                </div>

            </div>
        </div>
<!--
        <div class="row">
            <div class="col-lg-12">
                <?= var_dump($items) ?>
                <?= var_dump($model) ?>
            </div>
        </div>
 -->
    </div>
</div>
