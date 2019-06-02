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
        $galleries = Gallery::findAll();
        return $this->render('index', ['galleries'=>$galleries]);
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
