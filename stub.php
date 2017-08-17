#!/usr/bin/env php
<?php
// application.php

require __DIR__.'/vendor/autoload.php';

date_default_timezone_set("UTC");

use Symfony\Component\Console\Application;
use AO\Command\EmitCommand;

$application = new Application();

// ... register commands
$application->add(new EmitCommand());

$application->run();
