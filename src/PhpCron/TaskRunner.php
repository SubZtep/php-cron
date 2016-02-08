<?php
namespace PhpCron;

class TaskRunner
{
	private $rules;

	public function __construct($fileName) {
		$this->rules = new Rules($fileName);
	}

	public function runOnce() {
		while ($task = $this->rules->getNextTask()) {

			if (!$task->isRunning() && $task->shouldRun()) {
				$task->run();
			}

			echo 'Mem: '.number_format(memory_get_usage())."\n";
			sleep(1);

			if ($this->rules->isTasksUpdated()) {
				$this->rules->populateTasks();
				echo "Rules has been updated.\n";
				break;
			}
		}
	}

	public function run() {
		while (true) {
			$this->runOnce();
			$this->rules->resetTasks();
		}
	}
}