<?php

namespace app\helpers;

use Yii;

class DeviantClient
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_UPDATE = 'UPDATE';
    const METHOD_DELETE = 'DELETE';

    public $url = '';
    public $uri = '';
    public $baseUrl = 'https://www.deviantart.com/api/v1/oauth2';
    public $tokenUrl = 'https://www.deviantart.com/oauth2/token';
    public $authUrl = 'https://www.deviantart.com/oauth2/authorize';
    public $method;
    public $data = [];
    public $includeToken = true;
    public $returnJson = true;
    public $returnArray = false;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setIncludeToken($includeToken)
    {
        $this->includeToken = $includeToken;
        return $this;
    }

    public function setReturnJson($returnJson)
    {
        $this->returnJson = $returnJson;
        return $this;
    }

    public function setReturnArray($returnArray)
    {
        $this->returnArray = $returnArray;
        return $this;
    }

    public function returnAsArray()
    {
        $this->setReturnJson(false);
        $this->setReturnArray(true);
        return $this;
    }

    public function disableToken()
    {
        $this->includeToken = false;
        return $this;
    }

    public function send()
    {
        $curl = curl_init();

        if ($this->url == '') {
            $this->url = $this->baseUrl;

            if ($this->uri !== '') {
                $this->url = $this->url . $this->uri;
            } else {
                throw new Exception("Either URL or URI must be provided!", 1);
            }
        }

        if ($this->includeToken) {
            $this->data['access_token'] = $this->getToken();
        }

        if ($this->method == static::METHOD_GET && sizeof($this->data) !== 0) {
            $this->url .= '?' . http_build_query($this->data,'&amp;');
        }
        // echo $this->url;exit;

        $optionsArray = [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => "title=test&artist_comments=information&is_dirty=false&itemid=4344961365178228&mature_content=true&access_token=4f7691186b790515d1d7bd3423c7f030a990cc81e8bc8bd886",
            CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: application/x-www-form-urlencoded",
              "postman-token: cbf79665-541f-8be8-ffe3-061507fde271"
            ),
        ];

        if ($this->method == static::METHOD_POST && sizeof($this->data) !== 0) {
            $optionsArray[CURLOPT_POSTFIELDS] = http_build_query($this->data,'&amp;');
        }

        curl_setopt_array($curl, $optionsArray);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception("Error Processing Request", $err);
        }

        if ($this->returnJson) {
            return json_decode($response);
        } elseif ($this->returnArray) {
            return json_decode($response, true);
        } else {
            return $response;
        }
    }

    public function get($uri)
    {
        return $this->setMethod(self::METHOD_GET)->setUri($uri);
    }


    public function post($uri)
    {
        return $this->setMethod(self::METHOD_POST)->setUri($uri);
    }

    public function getAccessToken($code)
    {
        $data = [
            'grant_type' => 'authorization_code',
            'client_id' => Yii::$app->params['da']['client_id'],
            'client_secret' => Yii::$app->params['da']['client_secret'],
            'code' => $code,
            'redirect_uri' => Yii::$app->params['da']['redirect_uri'],
        ];
        $url = Yii::$app->params['da']['token_url'];
        $result = $this->setUrl($url)->setMethod(self::METHOD_GET)->setData($data)->disableToken()->send();
        if ($result->access_token) {
            return $result->access_token;
        } else {
            return false;
        }
    }

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

    public function logout()
    {
        $url = 'https://www.deviantart.com/oauth2/revoke';
        $data = ['token'=>static::getToken()];
        $result = $this->setUrl($url)->setMethod(self::METHOD_POST)->setData($data)->send();
    }

    public static function getToken()
    {
        return Yii::$app->session->get('access_token');
    }
}
