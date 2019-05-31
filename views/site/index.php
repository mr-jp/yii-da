<?php

/* @var $this yii\web\View */

$this->title = 'Login';
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
        <?php endif ?>
    </div>
</div>
