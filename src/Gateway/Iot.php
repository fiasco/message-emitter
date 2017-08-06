<?php

namespace AO\gateway;

use LibMQTT\Client;

class Iot extends StdOut {

  const serverName = "";
  const serverPort = "";
  const clientID = "";
  const caCert = "";

  static protected $client;

  static public function getClient()
  {
    if (self::$client) {
      return self::$client;
    }

    self::$client = new Client(Iot::serverName, Iot::serverPort, Iot::clientID);
    self::$client->setCryptoProtocol('tls');
    self::$client->setCAFile(Iot::caCert);

    if (!self::$client->connect()) {
      throw new Exception("Cannot connect to IoT Gateway.");
    }

    register_shutdown_function(function () {
      Iot::getClient()->close();
    });

    return self::$client;
  }

  public function emit($topic, $msg)
  {
    parent::emit("iot/$topic", $msg);
    $client = self::getClient();
    $client->publish($topic, $msg, 0);
  }
}

 ?>
