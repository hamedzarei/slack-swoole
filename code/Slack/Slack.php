<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 4/10/20
 * Time: 12:05 AM
 */

namespace Slack;


class Slack
{
    protected $base_url;

    public $uri;

    public $args = [];

    public $body;

    protected $method = 'get'; // get is default value!

//    channel'id of slack that you are going to work with
    protected $channel;

//    access token
    /*
     * first must create app and go to 'OAuth & Permissions' of app and get 'OAuth Access Token' for user!
     */
    protected $token;


    protected $client_id;

    protected $client_secret;
    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->modifyArgs('token', $token);
    }
    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
        $this->modifyArgs('channel', $channel);
    }

    public function __construct($base_url = null, $channel = null, $token = null, $client_id = null, $client_secret = null)
    {
        if ($base_url == null) {
            $base_url = 'https://slack.com/api';
        }

        if ($token == null) {
            $token = 'xoxp-1051478991574-1051478991654-1046145009415-4a648ceda9b3960b73a3c80f4b43e9b5';
        }

        if ($channel == null) {
            $channel = 'C011HE301C6';
        }

        if ($client_id) {
            $this->client_id = $client_id;
            $this->addToArgs([
                'client_id' => $client_id
            ]);
        }

        if ($client_secret) {
            $this->client_secret = $client_secret;
            $this->addToArgs([
                'client_secret' => $client_secret
            ]);
        }

        $this->base_url = $base_url;
        $this->token = $token;
        $this->addToArgs([
            'token' => $token,
            'channel' => $channel
        ]);
    }

    public function addToArgs($args)
    {
        $this->args = array_merge($this->args, $args);
    }

    public function modifyArgs($key, $value)
    {
        $this->args[$key] = $value;
    }

    public function send()
    {
        if (strtolower($this->method) == 'get') {
            $curl = curl_init();

            $args = http_build_query($this->args);
            $url = "https://slack.com/api/{$this->uri}?{$args}";
            printf($url);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            return $response;
        }
    }


}