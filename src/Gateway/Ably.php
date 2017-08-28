<?php

namespace AO\gateway;

use Ably\AblyRest;

class Ably extends StdOut {

  static protected $client;

  static public function getClient()
  {
    if (self::$client) {
      return self::$client;
    }

    $keys = json_decode(file_get_contents('ably-keys.json'), TRUE);

    self::$client = new AblyRest($keys['appkey']);

    return self::$client;
  }

  public function emit($topic, $msg)
  {
    parent::emit("ably/$topic", $msg);
    $channel = self::getClient()->channel($topic);
    return $channel->publish('msg-' . $msg['E-Tag'], $msg);
  }
}

 ?>
