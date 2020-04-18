<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 4/11/20
 * Time: 1:13 AM
 */

namespace Slack;

require_once 'Slack.php';

class Channel extends Slack
{
    public function getList()
    {
        $this->uri = 'channels.list';
    }

    public function getInfo($channel = null)
    {
        $this->uri = 'channels.info';

        if ($channel) {
            $this->setChannel($channel);
        }
    }

    public function join($name)
    {
        $this->uri = 'channels.join';
        $this->setChannel(null);
        $this->addToArgs([
            'name' => $name
        ]);
    }

    public function leave($channel)
    {
        $this->uri = 'channels.leave';
        $this->setChannel($channel);
    }
}