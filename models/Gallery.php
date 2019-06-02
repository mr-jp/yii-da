<?php

namespace app\models;
use app\helpers\DeviantClient;
use Yii;

class Gallery
{
    public function getContents($id=0)
    {
        $client = new DeviantClient;
        $json = $client->get("/gallery/{$id}")->setData(['mature_content'=>'true'])->send();
        // var_dump($json);exit;
        return $json->results;
    }

    public static function findAll()
    {
        $client = new DeviantClient;
        $data = [
            'limit' => 50
        ];
        $response = $client->get('/gallery/folders')->setData($data)->send();
        // var_dump($response);exit;
        $results = $response->results;
        $galleries = [];
        foreach($results as $result) {
            $galleries[$result->folderid] = $result->name;
        }
        return $galleries;
    }
}
