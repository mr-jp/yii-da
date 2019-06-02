<?php

/* @var $this yii\web\View */

$this->title = 'Gallery Items';
?>
<div class="gallery-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Gallery</h2>
                <?php if (sizeof($galleries) !== 0): ?>
                    <ul>
                        <?php foreach($galleries as $id => $title): ?>
                        <li><a href="<?= Yii::$app->urlManager->createUrl(['gallery/contents', 'id'=>$id]) ?>"><?= $title ?></a></li>
                        <?php endforeach?>
                    </ul>
                    <?php else: ?>
                        <p>No galleries available ...</p>
                <?php endif ?>
                <h3>Test Data</h3>
                <?= var_dump($galleries) ?>
            </div>
        </div>
    </div>
</div>
