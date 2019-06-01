<?php

/* @var $this yii\web\View */

$this->title = 'Publish ';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Stash</h2>
                <ul>
                    <?php foreach($items as $item): ?>
                        <!-- <?php var_dump($item->metadata); ?> -->
                        <li><?= $item->metadata->title ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</div>
