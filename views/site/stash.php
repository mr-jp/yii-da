<?php

/* @var $this yii\web\View */

$this->title = 'Stash Items';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <!-- Title -->
                <?php if ($id==0): ?>
                    <h2>Root Stack</h2>
                <?php else: ?>
                    <h2>Stack: <?= $id ?></h2>
                    <a href="<?= Yii::$app->urlManager->createUrl(['site/stash', 'id'=>0]) ?>">&lt;&lt; back to root</a>
                <?php endif ?>

                <!-- Display stacks first -->
                <?php if (sizeof($stacks) !== 0): ?>
                    <h3>Stacks:</h3>
                    <ul>
                        <?php foreach ($stacks as $stack): ?>
                            <li>
                                <a href="<?= Yii::$app->urlManager->createUrl(['site/stash', 'id'=>$stack->stackid]) ?>">
                                    <?= $stack->title ?>
                                </a>
                            </li>
                        <?php endforeach?>
                    </ul>
                <?php else: ?>
                    <p>No items in stash!</p>
                <?php endif?>

                <!-- Display items in this stack -->
                <?php if (sizeof($items) !== 0): ?>
                    <h3>Items:</h3>
                    <p><a href="<?= Yii::$app->urlManager->createUrl(['site/publish-many', 'stashId'=>$id]) ?>">&gt;&gt; Publish all items on this stack</a></p>
                    <?php foreach($items as $item): ?>
                        <!-- <?= var_dump($item) ?> -->
                        <div class="stash-item text-center align-text-bottom">
                            <?php if (isset($item->thumb)): ?>
                                <img src="<?= $item->thumb->src ?>" class="stash-thumb">
                            <?php endif ?>
                            <br>
                            <br>
                            <a href="<?= Yii::$app->urlManager->createUrl(['site/publish', 'id'=>$item->stackid]) ?>" class="btn btn-default">Publish</a>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
