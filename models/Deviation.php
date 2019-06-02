<?php

namespace app\models;
use app\helpers\DeviantClient;
use Yii;

class Deviation
{
    public function find($id=0)
    {
        $client = new DeviantClient;
        $json = $client->get("/deviation/{$id}")->send();
        // var_dump($json);exit;
        return $json;
    }
}
