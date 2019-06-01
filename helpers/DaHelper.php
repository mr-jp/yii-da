<?php

namespace app\helpers;
use yii;
use yii\httpclient\Client;

class DaHelper extends Client
{
    public $baseUrl = 'https://www.deviantart.com/api/v1/oauth2';

    /**
     * Get Authentication URL
     * @return string
     */
    public static function getAuthUrl()
    {
        $authUrl = Yii::$app->params['da']['auth_url'];
        $data = [
            'response_type' => Yii::$app->params['da']['response_type'],
            'client_id' => Yii::$app->params['da']['client_id'],
            'redirect_uri' => Yii::$app->params['da']['redirect_uri'],
            'scope' => Yii::$app->params['da']['scope'],
        ];
        $authUrl .= '?' . http_build_query($data, '&amp;');
        return $authUrl;
    }

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
        $json = json_decode($response->getContent());
        // var_dump($json);exit;
        if ($json->access_token) {
            return $json->access_token;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $result = $this->createRequest()
                    ->setMethod('POST')
                    ->setUrl('https://www.deviantart.com/oauth2/revoke')
                    ->setData(['token'=>Yii::$app->session->get('access_token')])
                    ->send();
    }

    /**
     * Creates 'GET' request.
     * @param array|string $url target URL.
     * @param array|string $data if array - request data, otherwise - request content.
     * @param array $headers request headers.
     * @param array $options request options.
     * @return Request request instance.
     */
    public function get($url, $data = null, $headers = [], $options = [])
    {
        $data = $this->addTokenToData($data);
        return parent::get($url, $data, $headers, $options);
    }

    /**
     * Creates 'POST' request.
     * @param array|string $url target URL.
     * @param array|string $data if array - request data, otherwise - request content.
     * @param array $headers request headers.
     * @param array $options request options.
     * @return Request request instance.
     */
    public function post($url, $data = null, $headers = [], $options = [])
    {
        $data = $this->addTokenToData($data);
        // var_dump($data);exit;
        return parent::get($url, $data, $headers, $options);
    }

    /**
     * Add access token to data
     * @param array $data
     */
    protected function addTokenToData($data)
    {
        if ($data === null) {
            $data = [];
        }
        return array_merge($data, ['access_token'=>Yii::$app->session->get('access_token')]);
    }
}
