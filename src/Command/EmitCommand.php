<?php

namespace AO\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use AO\Gateway;

class EmitCommand extends Command
{
    protected function configure()
    {
      $this
      ->setName('emit')
      ->setDescription('Emits payload data as a message stub')
      ->setHelp('This commands allows you to emulate linear message load to the gateway')
      ->addOption(
          'rate',
          null,
          InputOption::VALUE_OPTIONAL,
          'How many messages should be emited on average per minute?',
          1
      )
      ->addOption(
          'vary-rate',
          null,
          InputOption::VALUE_OPTIONAL,
          'How much variation should occur in randomising the messaging rate?',
          0.2
      )
      ->addOption(
          'size',
          null,
          InputOption::VALUE_OPTIONAL,
          'What is the average size of an emitted message in kb?',
          4
      )
      ->addOption(
          'vary-size',
          null,
          InputOption::VALUE_OPTIONAL,
          'How much variation should occur in randomising the messaging size?',
          0.2
      )
      ->addOption(
          'start-time',
          null,
          InputOption::VALUE_OPTIONAL,
          'The datetime sucessful emissions should begin.',
          date('c')
      )
      ->addOption(
          'limit',
          null,
          InputOption::VALUE_OPTIONAL,
          'Limit the number of messages emitted',
          100
      )
      ->addOption(
          'gateway',
          null,
          InputOption::VALUE_OPTIONAL,
          'The gateway to emit the message out to.',
          'stdout'
      );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $osize      = $input->getOption('size') * 1024;
      $size_vary = $input->getOption('vary-size') * $osize;

      $rate      = 60 / $input->getOption('rate') * 1000000;
      $rate_vary = $input->getOption('vary-rate') * $rate;

      $valid_after = strtotime($input->getOption('start-time'));

      $count = 0;

      do {
        $size = mt_rand($osize - $size_vary, $osize + $size_vary);
        $sleep = mt_rand($rate - $rate_vary, $rate + $rate_vary);
        $status = 404;

        if ($valid_after < time()) {
          $status = 200;
          $count++;
        }

        $msg = 'Date: ' . date('c') . PHP_EOL;
        $msg .= 'Content-Length: ' . $size . ' bytes' . PHP_EOL;
        $msg .= 'Cache-Control: max-age=' . $sleep/1000000 . PHP_EOL;
        $msg .= 'E-Tag: ' . $count . PHP_EOL;
        $msg .= 'Status: ' . $status . PHP_EOL;
        while (strlen($msg) < $size) {
          $msg .= md5($msg);
        }

        $msg = substr($msg, 0, $size);
        $this->emit($msg, $input->getOption('gateway'), $output);
        usleep($sleep);
      }
      while ($input->getOption('limit') > $count);
    }

    protected function emit($message, $gateway, OutputInterface $output) {
      switch ($gateway) {
        case 'iot':
          $channel = new Gateway\Iot();
          break;

        case 'pubnub':
          $channel = new Gateway\PubNub();
          break;

        default:
          $channel = new Gateway\StdOut();
          break;
      }
      $channel->emit('test', $message);
      return $this;
    }
}
