<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\User;
use app\models\Stash;
use app\helpers\DaHelper;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $authUrl = '';
        if(Yii::$app->request->get('code')) {
            // Create a new user model and login
            $model = new User;
            $model->id = '100';
            $model->username = 'admin';
            $model->password = 'admin';

            // Store code in session variable
            $client = new DaHelper();
            $token = $client->getToken(Yii::$app->request->get('code'));
            if ($token !== false) {
                $this->setToken($token);
                // Redirect to stash page
                if(Yii::$app->user->login($model, 3600*24*30)) {
                    return $this->redirect(['site/stash']);
                }
            } else {
                Yii::$app->session->setFlash('error', "Error getting access token!");
            }
        } else {
            $authUrl = $this->getAuthUrl();
        }

        return $this->render('index', [
            'authUrl' => $authUrl
        ]);
    }

    /**
     * Displays your stash
     * @return string
     */
    public function actionStash()
    {
        $items = Stash::findAll();
        return $this->render('stash', ['items' => $item]);
    }

    /**
     * Get Authentication URL
     * @return string
     */
    private function getAuthUrl()
    {
        $authUrl = Yii::$app->params['da']['auth_url'];
        $data = [
            'response_type' => Yii::$app->params['da']['response_type'],
            'client_id' => Yii::$app->params['da']['client_id'],
            'redirect_uri' => Yii::$app->params['da']['redirect_uri'],
        ];
        $authUrl .= '?' . http_build_query($data, '&amp;');
        return $authUrl;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     * @todo  Revoke token access here
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/index']);
    }

    protected function getToken()
    {
        return Yii::$app->session->get('access_token');
    }

    protected function setToken($token)
    {
        Yii::$app->session->set('access_token', $token);
    }
}

