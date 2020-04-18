<?php
/**
 * Created by PhpStorm.
 * User: zrhm7232
 * Date: 4/10/20
 * Time: 12:06 AM
 */

namespace Slack;

require_once 'Slack.php';

class Chat extends Slack
{
    /**
     * @param $text
     * @param null $reply_to
     */
    public function postMessage($text, $reply_to = null)
    {
//        token=xoxb-1051478991574-1046145010503-Ci6VcTRHEtliVPtlCsloCI9F&channel=C011HE301C6&text=:D:&parse=full
        $this->uri = 'chat.postMessage';
        $this->addToArgs([
            'text' => $text,
            'parse' => 'full'
        ]);

        if ($reply_to) {
            $this->addToArgs([
                'thread_ts' => $reply_to
            ]);
        }
    }

    /**
     * @param $timestamp
     */
    public function delete($timestamp)
    {
        $this->uri = 'chat.delete';
        $this->addToArgs([
            'ts' => $timestamp
        ]);

    }
}