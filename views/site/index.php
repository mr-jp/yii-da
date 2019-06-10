<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Login';

$refresh_token = Yii::$app->session->get('refresh_token');
?>
<div class="site-index">

    <div class="body-content">
        <?php if (Yii::$app->user->isGuest === true): ?>
            <div class="row">
                <div class="col-lg-12">
                    <h2>Login</h2>
                    <p><a class="btn btn-success" href="<?= $authUrl ?>">Login to Deviantart account</a></p>
                </div>
            </div>
        <?php else: ?>
            <!-- Refresh Token -->
            <div class="row">
                <div class="col-lg-12">
                    <h2>Token</h2>
                    <?php if($refresh_token): ?>
                        <p>Click here to test refresh token:</p>
                        <p><a href="<?= Yii::$app->urlManager->createUrl(['site/index', 'refresh_token'=>$refresh_token]) ?>" class="btn btn-success">Refresh Token</a></p>
                    <?php endif ?>
                </div>
            </div>
            <!-- Create Gallery -->
            <div class="row">
                <div class="col-lg-12">
                    <h2>Create Folder</h2>
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= $form->field($galleryModel, 'folder')->textInput() ?>
                            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <!-- Stacks -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- Display stacks first -->
                    <h2>Stacks:</h2>
                    <p>Only root level is displayed:</p>
                    <?php if (sizeof($stacks) !== 0): ?>
                        <ul>
                            <?php foreach ($stacks as $stack): ?>
                                <li>

                                        <?= $stack->title ?>

                                    (<a href="<?= Yii::$app->urlManager->createUrl(['stash/publish-many', 'stashId'=>$stack->stackid]) ?>">
                                         Publish
                                    </a>)
                                </li>
                            <?php endforeach?>
                        </ul>
                    <?php else: ?>
                        <p>No stacks on this level</p>
                    <?php endif?>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
