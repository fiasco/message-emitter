<?php

namespace AO\gateway;

use Symfony\Component\Console\Output\ConsoleOutput;


class StdOut implements GatewayInterface {
  public function emit($topic, $msg) {
    $output = new ConsoleOutput();
    $output->writeln("---- $topic ----");
    $output->writeln($msg);
    $output->writeln("");
  }

}

 ?>
