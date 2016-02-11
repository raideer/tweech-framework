<?php

namespace Raideer\Tweech\Services;

class Emotes
{
    protected $globalApi = 'http://twitchemotes.com/api_cache/v2/global.json';
    protected $subscriberApi = 'http://twitchemotes.com/api_cache/v2/subscriber.json';

    protected $sql;

    public function __construct()
    {
        $this->sql = new \NoSQLite\NoSQLite('emotes.sqlite');
    }

    public function fetchGlobal()
    {
        $json = file_get_contents($this->globalApi);

        $data = json_decode($json, true);

        if ((json_last_error() == JSON_ERROR_NONE)) {
            $store = $this->sql->getStore('global');
            $store->set('meta.generated_at', $data['meta']['generated_at']);
            $store->set('template.small', $data['template']['small']);
            $store->set('template.medium', $data['template']['medium']);
            $store->set('template.large', $data['template']['large']);

            foreach ($data['emotes'] as $emote => $about) {
                $store->set("emote.$emote.description", $about['description']);
                $store->set("emote.$emote.id", $about['image_id']);
            }
        }
    }

    public function queryGet($storeName, $id)
    {
        $store = $this->sql->getStore($storeName);

        return $store->get($id);
    }

    public function fetchSubscribers()
    {

    // return file_put_contents(
    //   __DIR__ . "/subscribers.json",
    //   file_get_contents($this->subscriberApi)
    // );
    }
}
