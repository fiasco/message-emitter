<?php

namespace AO\Gateway;

use Symfony\Component\Console\Output\ConsoleOutput;


class StdOut implements GatewayInterface {
  public function emit($topic, $msg) {
    $output = new ConsoleOutput();

    $output->writeln("[{$msg['Date']}] $topic {$msg['Status']} {$msg['E-Tag']}");
    // $output->writeln(json_encode($msg, JSON_PRETTY_PRINT));
  }

}

 ?>
