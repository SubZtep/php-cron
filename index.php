<?php
require_once(__DIR__.'/classes/rules.class.php');
require_once(__DIR__.'/classes/task.class.php');

$rules = new Rules();


// Run tasks
while ($task = $rules->getNextTask()) {

	if (!$task->isRunning() && $task->shouldRun()) {
		$task->run();
	}

	echo 'Mem: '.number_format(memory_get_usage())."\n";
	sleep(1);
}