<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 4/11/20
 * Time: 1:56 AM
 */

namespace Slack;

require_once 'Slack.php';

class Oauth extends Slack
{
    public function test($token)
    {
        $this->uri = 'auth.test';
        $this->setToken($token);
    }

    public function getAccessToken($code)
    {
        $this->uri = 'oauth.access';
        $this->addToArgs([
            'code' => $code
        ]);
    }
}