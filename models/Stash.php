<?php

namespace app\models;
use app\helpers\DaHelper;

class Stash extends \yii\base\BaseObject
{
    public static function findAll()
    {
        $client = new DaHelper();
        $data = DaHelper::call('get', '/stash/delta');
        $response = $client->get('/stash/delta')->send();
        var_dump($response);exit;
    }
}
