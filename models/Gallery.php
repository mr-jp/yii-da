<?php

namespace app\models;
use app\helpers\DeviantClient;
use yii\base\Model;
use Yii;

class Gallery extends Model
{
    public $folder;

    public function attributeLabels()
    {
        return [
            'folder' => 'Folder Name',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'folder',
            ], 'required'],
        ];
    }

    public function getContents($id=0)
    {
        $client = new DeviantClient;
        $json = $client->get("/gallery/{$id}")->setData(['mature_content'=>'true'])->send();
        // var_dump($json);exit;
        return $json->results;
    }

    /**
     * Get all galleries
     * @return array
     */
    public static function findAll()
    {
        $galleries = [];
        static::addToGallery($galleries);
        return $galleries;
    }

    /**
     * Recursive function to call until no more galleries
     * @return array
     */
    public static function addToGallery(&$galleries, $hasMore = false, $nextOffset = 0)
    {
        $client = new DeviantClient;

        $data = [
            'limit' => 50,
        ];

        if ($hasMore) {
            $data['offset'] = $nextOffset;
        }

        $response = $client->get('/gallery/folders')->setData($data)->send();
        if (!isset($response->results)) {
            throw new \Exception("Error reading galleries!");
        } else {
            $results = $response->results;
            foreach($results as $result) {
                // add to the referenced $galleries
                $galleries[$result->folderid] = $result->name;
            }

            if ($response->has_more) {
                static::addToGallery($galleries, $response->has_more, $response->next_offset);
            } else {
                return false;
            }
        }

        return;
    }

    public function create()
    {
        $client = new DeviantClient;
        $data = [
            'folder' => $this->folder
        ];
        $response = $client->post('/gallery/folders/create')->setData($data)->send();
        if (isset($response->folderid)) {
            return true;
        }
        return false;
    }

    /**
     * Create 50 folders for testing
     * @return boolean
     */
    public function dummyCreate()
    {
        for ($i = 0; $i < 50; $i++) {
            $client = new DeviantClient;
            $data = [
                'folder' => time()
            ];
            $response = $client->post('/gallery/folders/create')->setData($data)->send();
        }
        return true;
    }
}
