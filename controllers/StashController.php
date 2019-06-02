<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;

use app\models\User;
use app\models\Item;
use app\models\Stash;
use app\models\Gallery;
use app\helpers\DeviantClient;

class StashController extends CommonController
{
    /**
     * Displays your stash
     * @return string
     */
    public function actionIndex($id = 0)
    {
        $client = new Stash();
        $results = $client->find($id);
        $stacks = $results['stacks'];
        $items = $results['items'];

        return $this->render('index', [
            'stacks' => $stacks,
            'items' => $items,
            'id' => $id
        ]);
    }

    /**
     * Publish a single item
     * @param  integer $id item id
     * @return string
     */
    public function actionPublish($id = 0)
    {
        if ($id == 0) {
            throw new \Exception("Please provide an id!", 1);
        }

        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            $url = $model->publish();
            Yii::$app->session->setFlash('success', "Item published successfully! <a href='{$url}'>(link)</a>");
            return $this->redirect(['index']);
        } else {
            $client = new Stash();
            $result = $client->findOne($id);
            $model->setAttributes($result);
        }

        $firstImage = '';
        if (sizeof($model->files) !== 0) {
            $firstImage = $model->files[0];
        }

        return $this->render('publish', [
            'model' => $model,
            'firstImage' => $firstImage,
            'galleries' => Gallery::findAll(),
        ]);
    }

    /**
     * Publish all items in the stack
     * @param  integer $stashId stash id
     * @return string
     */
    public function actionPublishMany($stashId = 0)
    {
        if ($stashId === 0) {
            throw new \Exception("Please provide an id!", 1);
        }

        $model = new Item;

        $client = new Stash();
        $results = $client->find($stashId);
        $items = $results['items'];

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            Item::publishMany($post['Item'], $items);
            Yii::$app->session->setFlash('success', "Items published successfully!");
            return $this->redirect(['index']);
        }

        return $this->render('publish-many', [
            'model' => $model,
            'items' => $items,
            'galleries' => Gallery::findAll(),
            'stashId' => $stashId,
        ]);
    }
}
