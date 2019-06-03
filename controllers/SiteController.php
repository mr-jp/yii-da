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
use app\helpers\DeviantClient;

class SiteController extends CommonController
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $authUrl = '';
        if (Yii::$app->request->get('code')) {
            // Get token and store in session variable
            $client = new DeviantClient;
            if ($client->getAccessToken(Yii::$app->request->get('code'))) {
                if($this->performLogin()) {
                    // return $this->redirect(['stash/index']);
                }
            } else {
                Yii::$app->session->setFlash('error', "Error getting access token!");
            }
        } else {
            $authUrl = DeviantClient::getAuthUrl();
        }

        if (Yii::$app->request->get('refresh_token')) {
            $client = new DeviantClient;
            if($client->refreshToken(Yii::$app->session->get('refresh_token'))) {
                Yii::$app->session->setFlash('success', "Token refreshed successfully!");
            }
        }

        return $this->render('index', [
            'authUrl' => $authUrl
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        return $this->redirect('index');
    }

    /**
     * Logout action.
     *
     * @return Response
     * @todo  Revoke token access here
     */
    public function actionLogout()
    {
        $client = new DeviantClient;
        $client->logout();
        Yii::$app->user->logout();
        return $this->redirect(['site/index']);
    }
}

