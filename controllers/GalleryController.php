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
use app\models\Deviation;
use app\helpers\DeviantClient;

class GalleryController extends CommonController
{
    public function actionIndex()
    {
        $model = new Gallery;
        $galleries = Gallery::findAll();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->create()) {
                $this->refresh();
                Yii::$app->session->setFlash('success', "Gallery created!");
            } else {
                Yii::$app->session->setFlash('error', "Error creating gallery!");
            }
        }

        return $this->render('index', [
            'galleries'=>$galleries,
            'model'=>$model,
        ]);
    }

    public function actionContents($id=0)
    {
        $client = new Gallery();
        $contents = $client->getContents($id);
        return $this->render('contents', ['contents'=>$contents]);
    }

    public function actionDeviation($id=0)
    {
        $client = new Deviation;
        $deviation = $client->find($id);
        return $this->render('deviation', ['deviation'=>$deviation]);
    }
}
