<?php

/* @var $this yii\web\View */

$this->title = 'Stash Items';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Stash</h2>
                <ul>
                    <?php foreach($items as $item): ?>
                        <!-- <?php var_dump($item->metadata); ?> -->
                        <li>
                            <a href="<?= Yii::$app->urlManager->createUrl(['site/stashItem']) ?>">
                                <?= $item->metadata->title ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</div>
