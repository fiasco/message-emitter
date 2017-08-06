<?php

namespace AO\Gateway;

interface GatewayInterface {

  public function emit($topic, $msg);
}


 ?>
