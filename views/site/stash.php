<?php

/* @var $this yii\web\View */

$this->title = 'Stash';
?>
<div class="site-index">

    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Stash</h2>
                <p><?= Yii::$app->session->get('access_token') ?></p>
            </div>
        </div>
    </div>
</div>
