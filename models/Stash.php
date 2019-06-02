<?php

namespace app\models;
use app\helpers\DeviantClient;
use Yii;

class Stash
{
    public function find($id = 0)
    {
        $stacks = [];
        $items = [];

        $client = new DeviantClient;
        $json = $client->get("/stash/{$id}/contents")->setData(['limit'=>50])->send();
        $results = $json->results;
        foreach ($results as $result) {
            if ($result->size > 1) {
                $stacks[] = $result;
            } else {
                $items[] = $result;
            }
        }

        return compact('stacks', 'items');
    }

    /**
     * Find a single stash item
     * @param  int $id
     * @return json
     */
    public function findOne($id)
    {
        // read everything
        $client = new DeviantClient;
        $response = $client->get("/stash/{$id}/contents")->returnAsArray()->send();
        $results = $response['results'];
        return $results[0];
    }
}
