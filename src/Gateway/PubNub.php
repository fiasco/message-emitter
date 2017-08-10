<?php

namespace AO\gateway;

use PubNub\PubNub as PubNubClient;
use PubNub\PNConfiguration;

class PubNub extends StdOut {

  static protected $client;

  static public function getClient()
  {
    if (self::$client) {
      return self::$client;
    }

    $keys = json_decode(file_get_contents('pubnub-keys.json'), TRUE);

    $pnConfiguration = new PNConfiguration();
    $pnConfiguration->setSubscribeKey($keys['sub']);
    $pnConfiguration->setPublishKey($keys['pub']);

    self::$client = new PubNubClient($pnConfiguration);

    return self::$client;
  }

  public function emit($topic, $msg)
  {
    parent::emit("pubnub/$topic", $msg);

    $result = self::getClient()
      ->publish()
      ->message($msg)
      ->channel($topic)
      ->usePost(TRUE)
      ->sync();
  }
}

 ?>
