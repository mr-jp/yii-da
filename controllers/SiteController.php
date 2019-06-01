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
use app\models\Item;
use app\models\Stash;
use app\helpers\DeviantClient;

class SiteController extends Controller
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
            $client = new DeviantClient;
            $token = $client->getAccessToken(Yii::$app->request->get('code'));
            if ($token !== false) {
                Yii::$app->session->set('access_token', $token);
                // Redirect to stash page
                if(Yii::$app->user->login($model)) {
                    return $this->redirect(['site/stash']);
                }
            } else {
                Yii::$app->session->setFlash('error', "Error getting access token!");
            }
        } else {
            $authUrl = DeviantClient::getAuthUrl();
        }

        return $this->render('index', [
            'authUrl' => $authUrl
        ]);
    }

    public function actionUpload()
    {
        return $this->render('upload');
    }

    /**
     * Displays your stash
     * @return string
     */
    public function actionStash($id = 0)
    {
        $client = new Stash();
        $results = $client->find($id);
        $stacks = $results['stacks'];
        $items = $results['items'];

        return $this->render('stash', [
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
            return $this->redirect(['stash']);
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
            'galleries' => Item::galleries(),
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
            return $this->redirect(['stash']);
        }

        return $this->render('publish-many', [
            'model' => $model,
            'items' => $items,
            'galleries' => Item::galleries(),
            'stashId' => $stashId,
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

