<?php
include(__DIR__.'/vendor/autoload.php');

$runner = new PhpCron\TaskRunner(__DIR__.'/tasks.json');
$runner->run();
