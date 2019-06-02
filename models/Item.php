<?php

namespace app\models;
use app\helpers\DaHelper;
use app\helpers\DeviantHelper;
use app\helpers\DeviantClient;
// use yii\httpclient\Client;
// use Guzzle\Http\Client;
// use yii\httpclient\CurlTransport;
use yii\base\Model;
use Httpful;
use Httpful\Mime;
use Yii;

/**
 * This is the model class for a single stack item
 *
 */
class Item extends Model
{
    public $itemid;
    public $stackid;
    public $title;
    public $tags;
    public $files = [];
    public $artist_comments = "";
    public $original_url;
    public $category;
    public $creation_time;
    public $is_dirty = "false";
    public $mature_content = "true";

    public $description;
    public $is_mature = "true";
    public $agree_submission = "true";
    public $agree_tos = "true";
    public $feature = "true";
    public $galleryids = [];
    public $catpath = '/manga/digital/3d'; // Manga & Anime / Digital Media / 3D

    public function setAttributes($values, $safeOnly = true)
    {
        if (isset($values['tags']) && is_array($values['tags'])) {
            $values['tags'] = implode(' ', $values['tags']);
        }
        parent::setAttributes($values, $safeOnly);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'itemid',
                'stackid',
                'title',
                'description',
                'files',
                'tags',
                'galleryids',
                'artist_comments',
            ], 'safe'],
        ];
    }

    /**
     * Publish this item
     * @return [type] [description]
     */
    public function publish()
    {
        // save the stash item first
        $data = [
            // 'access_token' => $token,
            'title' => $this->title,
            'artist_comments' => $this->artist_comments,
            'is_dirty' => $this->is_dirty,
            'itemid' => $this->itemid,
            'mature_content' => $this->mature_content,
            'tags' => explode(' ', $this->tags)
        ];
        $client = new DeviantClient;
        $response = $client->post('/stash/submit')->setData($data)->send();
        // var_dump($response);exit;

        // then publish it
        $data2 = [
            // 'access_token' => $token,
            'itemid' => $this->itemid,
            'description' => $this->description,
            'is_mature' => $this->is_mature,
            'agree_submission' => $this->agree_submission,
            'agree_tos' => $this->agree_tos,
            'feature' => $this->feature,
            'galleryids' => $this->galleryids,
            'catpath' => $this->catpath
        ];
        $client2 = new DeviantClient;
        $response2 = $client2->post('/stash/publish')->setData($data2)->send();
        if (isset($response2->url)) {
            return $response2->url;
        }
    }

    public static function publishMany($post, $items)
    {
        // var_dump($post);var_dump($items);exit;
        $itemCount = sizeof($items);
        for($i=0; $i<$itemCount; $i++) {
            $indexTitle = str_pad($i + 1, 2);
            $newTitle = "{$post['title']} - {$indexTitle} of {$itemCount}";
            $item = $items[$i];

            $itemModel = new Item;
            $itemModel->title  = $newTitle;
            $itemModel->galleryids  = $post['galleryids'];
            $itemModel->tags  = $post['tags'];

            // need to get the contents to get the itemid (what the fuck man)
            $stash = new Stash;
            $stashItem = $stash->findOne($item->stackid);
            $itemModel->itemid = $stashItem['itemid'];
            $itemModel->stackid  = $item->stackid;

            // publish the individual item
            $itemModel->publish();
        }
    }
}
