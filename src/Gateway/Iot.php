<?php

namespace AO\gateway;

use LibMQTT\Client;

class Iot extends StdOut {

  static protected $client;

  static public function getClient()
  {
    if (self::$client) {
      return self::$client;
    }
    $config = json_decode(file_get_contents('iot-keys.json'), TRUE);

    $certDir = realpath(dirname(__FILE__) . '/../../certs');
    self::$client = new Client($config['host'], $config['port'], 'pocMessageEmitter');
    self::$client->setCryptoProtocol('ssl');
    self::$client->setCAFile($certDir . '/' . $config['caCert']);
    self::$client->setClientCert($certDir .  '/' . $config['sslCrt'], $certDir .  '/' . $config['sslKey']);

    if (!self::$client->connect()) {
      throw new \Exception("Cannot connect to IoT Gateway.");
    }

    register_shutdown_function(function () {
      Iot::getClient()->close();
    });

    return self::$client;
  }

  public function emit($topic, $msg)
  {
    parent::emit("iot/$topic", $msg);
    $msg = json_encode($msg);
    $client = self::getClient();
    $client->publish($topic, $msg, 0);
  }
}

 ?>
