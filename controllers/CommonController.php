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

class CommonController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // non logged in only allowed in index and login actions
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['index', 'login'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['get'],
            //     ],
            // ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        $controllerName = $action->controller->id;
        $actionName = $action->id;

        // if refresh token exists (and user is not logged in), try to login again
        if ( Yii::$app->session->get('refresh_token') && Yii::$app->user->isGuest ) {
            // try to refresh token
            $refreshToken = Yii::$app->session->get('refresh_token');
            if ($refreshToken) {
                $client = new DeviantClient;
                if ($client->refreshToken($refreshToken)) {
                    $this->performLogin();
                    return true;
                }
            }

            // redirect to site/index
            Yii::$app->session->setFlash('error', "Please login to continue!");
            return $this->redirect(['site/index'])->send();
        }

        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true; // or false to not run the action
    }

    /**
     * Perform fake login
     * @todo  There must be a better way to do this, perhaps some other kidn of authentication model
     * @return boolean
     */
    public function performLogin()
    {
        // Create a new user model and login
        $model = new User;
        $model->id = '100';
        $model->username = 'admin';
        $model->password = 'admin';
        return Yii::$app->user->login($model);
    }
}

