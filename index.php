<?php
require_once(__DIR__.'/classes/taskrunner.class.php');
require_once(__DIR__.'/classes/rules.class.php');
require_once(__DIR__.'/classes/task.class.php');

$runner = new TaskRunner(__DIR__.'/tasks.json');
$runner->run();
