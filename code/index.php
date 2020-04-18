<?php
require_once 'Slack/Chat.php';
require_once 'Slack/Reaction.php';
require_once 'Slack/Channel.php';
require_once 'Slack/Oauth.php';
require_once 'Helper/validation.php';

$server = new Swoole\HTTP\Server("0.0.0.0", 9501);

$server->on("start", function (Swoole\Http\Server $server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$server->on("request", function (Swoole\Http\Request $request, Swoole\Http\Response $response) {
    $body = ltrim($request->rawcontent());
    $body = json_decode($body, true);
//    var_dump($request->server);
    $server = $request->server;
//    var_dump($request);
    $uri = $server['request_uri'];
    $method = $server['request_method'];
    $headers = $request->header;
    $path_info = $server['path_info'];
    $query_string = $server['query_string'];

    parse_str($query_string, $args);

    $user_token = 'xoxp-1051478991574-1051478991654-1046145009415-4a648ceda9b3960b73a3c80f4b43e9b5';
    $channel = 'C011HE301C6';
    $client_id = '1051478991574.1054408866133';
    $client_secret = 'b990590e586ce867679176115526aba3';

    $response_data = "";


    switch ($uri) {
        case '/chats': {
            $chat = new \Slack\Chat(null, $channel, $user_token);
            if (strtolower($method) == 'post') {
                $check = checkJSON($headers);
                if (!$check) {
                    $response_data = json_encode([
                        'ok' => false
                    ]);

                    break;
                }
//                if have reply_to means thread_ts!!
                $reply_to = null;
                if (array_key_exists('reply_to', $body)) {
                    $reply_to = $body['reply_to'];
                }
                if (!array_key_exists('text', $body)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'text'
                        ]
                    ]);

                    break;
                }
                $chat->postMessage($body['text'], $reply_to);
                $response_data = $chat->send();

            } elseif (strtolower($method) == 'delete') {
                $check = checkJSON($headers);
                if (!$check) {
                    $response_data = json_encode([
                        'ok' => false
                    ]);

                    break;
                }

                if (!array_key_exists('ts', $body)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'ts'
                        ]
                    ]);

                    break;
                }

                $chat->delete($body['ts']);
                $response_data = $chat->send();
            }
            break;
        }
        case '/reactions': {
            $reaction = new Slack\Reaction(null, $channel, $user_token);
            if (strtolower($method) == 'post') {
                $check = checkJSON($headers);
                if (!$check) {
                    $response_data = json_encode([
                        'ok' => false
                    ]);

                    break;
                }

                if (!array_key_exists('reply_to', $body) || !array_key_exists('name', $body)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'reply_to',
                            'name'
                        ]
                    ]);

                    break;
                }

                $reaction->add($body['name'], $body['reply_to']);
                $response_data = $reaction->send();
            } elseif (strtolower($method) == 'delete') {
                $check = checkJSON($headers);
                if (!$check) {
                    $response_data = json_encode([
                        'ok' => false
                    ]);

                    break;
                }

                if (!array_key_exists('timestamp', $body) || !array_key_exists('name', $body)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'timestamp',
                            'name'
                        ]
                    ]);

                    break;
                }

                $reaction->remove($body['name'], $body['timestamp']);
                $response_data = $reaction->send();
            }
            break;
        }

        case '/channels': {
            $channel = new Slack\Channel(null, $channel, $user_token);
            if (strtolower($method) == 'get') {
                if (array_key_exists('channel', $args)) {
                    $channel->getInfo($args['channel']);
                    $response_data = $channel->send();
                } else {
                    $channel->getList();
                    $response_data = $channel->send();
                }
            } elseif (strtolower($method) == 'put') {
                $check = checkJSON($headers);
                if (!$check) {
                    $response_data = json_encode([
                        'ok' => false
                    ]);

                    break;
                }

                if (!array_key_exists('name', $body)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'name'
                        ]
                    ]);

                    break;
                }

                $channel->join($body['name']);
                $response_data = $channel->send();

            } elseif (strtolower($method) == 'delete') {
                $check = checkJSON($headers);
                if (!$check) {
                    $response_data = json_encode([
                        'ok' => false
                    ]);

                    break;
                }

                if (!array_key_exists('channel', $body)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'channel'
                        ]
                    ]);

                    break;
                }

                $channel->leave($body['channel']);
                $response_data = $channel->send();
            }
            break;
        }

        case '/oauth': {
            $oauth = new \Slack\Oauth(null, $channel, $user_token, $client_id, $client_secret);

            if (!array_key_exists('action', $args)) {
                $response_data = json_encode([
                    'ok' => false,
                    'required' => [
                        'action'
                    ]
                ]);

                break;
            }

            $action = $args['action'];

            if ($action == 'test') {
                $token = $args['token'];
                if (!array_key_exists('token', $args)) {
                    $token = $user_token;
                }

                $oauth->test($token);
                $response_data = $oauth->send();
            } elseif ($action == 'access') {
                if (!array_key_exists('code', $args)) {
                    $response_data = json_encode([
                        'ok' => false,
                        'required' => [
                            'code'
                        ]
                    ]);

                    break;
                }

                $oauth->getAccessToken($args['code']);
                $response_data = $oauth->send();
            }
        }

        break;

    }

    $response->header("Content-Type", "application/json");
    $response->end($response_data);
});

$server->start();

