<?php
/* @var $this yii\web\View */

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
            <div class="row">
                <div class="col-lg-12">
                    <h2>Welcome!</h2>
                    <?php if($refresh_token): ?>
                        <p>Click here to test refresh token:</p>
                        <p><a href="<?= Yii::$app->urlManager->createUrl(['site/index', 'refresh_token'=>$refresh_token]) ?>" class="btn btn-success">Refresh Token</a></p>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
