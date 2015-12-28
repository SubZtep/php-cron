<?php
require_once(__DIR__.'/classes/task.class.php');

$tasks = [];

// Load tasks from json
$tasks_arr = json_decode(file_get_contents('tasks.json'), true);
foreach ($tasks_arr as $task_arr) {
	$task = new Task($task_arr);
	$tasks[] = $task;
}

// Run tasks
while (true) {
	foreach ($tasks as $task) {
		if (!$task->isRunning()) {
			if ($task->log) {
				$log = __DIR__."/log/{$task->log}.txt";
				file_put_contents($log, "\n\n-----\n".date('Y-m-d H:i:s')."\n-----\n\n", FILE_APPEND);
				$log = " >> $log 2>> $log";
			} else {
				$log = ' > /dev/null 2> /dev/null';
			}

			$task->pid = exec("php task1.php{$log} & echo $!");
		}		
	}

	echo 'Mem: '.number_format(memory_get_usage())."\n";
	sleep(1);
}