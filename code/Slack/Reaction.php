<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 4/11/20
 * Time: 12:49 AM
 */

namespace Slack;

require_once 'Slack.php';

class Reaction extends Slack
{
    public function add($name, $reply_to)
    {
        $this->uri = 'reactions.add';
        $this->addToArgs([
            'name' => $name,
            'timestamp' => $reply_to
        ]);
    }

    public function remove($name, $timestamp)
    {
        $this->uri = 'reactions.remove';
        $this->addToArgs([
            'name' => $name,
            'timestamp' => $timestamp
        ]);
    }
}