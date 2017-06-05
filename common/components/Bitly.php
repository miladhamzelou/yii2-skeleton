<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace common\components;

use common\models\Setting;
use yii\base\InvalidConfigException;
use yii\base\Object;
use yii\httpclient\Client;

/**
 * Class Bitly
 * @package common\components
 */
class Bitly extends Object
{
    protected $token;
    protected $api = 'https://api-ssl.bitly.com/v3';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $token = Setting::getValue('bitly');
        if (empty($token)) {
            throw new InvalidConfigException($this->className() . '::token cannot be empty.');
        } else {
            $this->token = $token;
        }
    }

    /**
     * shorten url
     * @param $url
     * @return string
     */
    public function shorten($url)
    {
        $client = new Client([
            'baseUrl' => $this->api
        ]);
        $p = ['longUrl' => $url, 'access_token' => $this->token];
        $r = $client->get('shorten', $p)->send()->getData();
        if (!empty($r['data']['url'])) {
            return $r['data']['url'];
        }
        return $url;
    }
}