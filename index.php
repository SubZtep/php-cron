<?php
require_once(__DIR__.'/classes/task.class.php');

$tasks = [];

// Load tasks from json
$tasks_arr = json_decode(file_get_contents(__DIR__.'/tasks.json'), true);
foreach ($tasks_arr as $task_arr) {
	$task = new Task($task_arr);
	$tasks[] = $task;
}

// Run tasks
while (true) {
	foreach ($tasks as $task) {
		if (!$task->isRunning() && $task->shouldRun()) {
			$task->run();
		}
	}

	echo 'Mem: '.number_format(memory_get_usage())."\n";
	sleep(1);
}