<?php

namespace app\helpers;
use yii;
use yii\httpclient\Client;

class DaHelper extends Client
{
    public $baseUrl = 'https://www.deviantart.com/oauth2';

    public function getToken($code)
    {
        $data = [
            'grant_type' => 'authorization_code',
            'client_id' => Yii::$app->params['da']['client_id'],
            'client_secret' => Yii::$app->params['da']['client_secret'],
            'code' => $code,
            'redirect_uri' => Yii::$app->params['da']['redirect_uri'],
        ];
        $query = http_build_query($data, '&amp;');
        $url = Yii::$app->params['da']['token_url'].'?'.$query;

        $client = new Client();
        $response = $client->createRequest()->setMethod('GET')->setUrl($url)->send();
        $json = json_decode($response);
        if ($json['access_token']) {
            return $json['access_token'];
        } else {
            return false;
        }
    }

    public function getTokenPost($code)
    {
        $data = [
            'grant_type ' => 'authorization_code',
            'client_id ' => Yii::$app->params['da']['client_id'],
            'client_secret ' => Yii::$app->params['da']['client_secret'],
            'code' => $code,
            'redirect_uri ' => Yii::$app->params['da']['redirect_uri'],
        ];

        $response = $this->post('/token', $data)->send();
        var_dump($this->request->getUrl());exit;
        $json = json_decode($response);
        if ($json['access_token']) {
            return $json['access_token'];
        } else {
            return false;
        }
    }

    public static function call($method, $url, $data = false)
    {
        $newUrl = Yii::$app->params['da']['api_url'].$url;
        $newUrl .= '?access_token=' . Yii::$app->session->get('access_token');

        $newData = ['access_token'=>Yii::$app->session->get('access_token')];
        if ($data) {
            $newData = array_merge($newData, $data);
        }
        var_dump($newUrl);
        var_dump($newData);exit;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod(strtoupper($method))
            ->setUrl($newUrl)
            ->setData($data)
            ->send();

        return $response;
    }

    public function revoke($token)
    {

    }
}
