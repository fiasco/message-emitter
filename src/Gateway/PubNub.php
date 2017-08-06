<?php

namespace AO\gateway;

use PubNub\PubNub;
use PubNub\PNConfiguration;

class PubNub extends StdOut {

  static protected $client;

  static public function getClient()
  {
    if (self::$client) {
      return self::$client;
    }

    $pnConfiguration = new PNConfiguration();
    $pnConfiguration->setSubscribeKey("my_sub_key");
    $pnConfiguration->setPublishKey("my_pub_key");
    $pnConfiguration->setSecure(false);

    self::$client = new PubNub($pnConfiguration);

    return self::$client;
  }

  public function emit($topic, $msg)
  {
    parent::emit("pubnub/$topic", $msg);

    $result = self::getClient()->publish()
              ->channel($topic)
              ->message($msg)
              ->sync();
  }
}

 ?>
