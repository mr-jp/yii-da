<?php

/* @var $this yii\web\View */

$this->title = 'Gallery Items';
?>
<div class="gallery-gallery">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-12">
                <h2>Gallery</h2>
                    <p>
                        <a href="<?= Yii::$app->urlManager->createUrl(['gallery/index']) ?>">&lt;&lt; back to all galleries</a>
                    </p>
                    <?php if(sizeof($contents) > 0): ?>
                        <table class="table table-striped">
                            <?php foreach($contents as $content): ?>
                                <tr>
                                    <td>
                                        <?php if (isset($content->preview->src)): ?>
                                            <img src="<?= $content->preview->src ?>" class="stash-thumb">
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <p>
                                            <a href="<?= Yii::$app->urlManager->createUrl(['gallery/deviation', 'id'=>$content->deviationid]) ?>">
                                                <?= $content->title ?>
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </table>
                    <?php else:?>
                        <p>This gallery has no contents!</p>
                    <?php endif?>
            </div>
            <div class="col-lg-12">
                <h2>Test Data</h2>
                <?= var_dump($contents) ?>
            </div>
        </div>
    </div>
</div>
