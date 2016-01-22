<?php
class Rules
{
	public $tasksArr = [];
	public $tasks = [];
	private $lastTaskIdx = 0;

	public function __construct() {
		$this->populateTasks();
	}

	public function processJson() {
		$json = file_get_contents(__DIR__.'/../tasks.json');
		$json = preg_replace('!/\*.*?\*/!s', '', $json); // remove comments
		$this->tasksArr = json_decode($json, true);
	}

	public function populateTasks() {
		$this->processJson();
		$this->tasks = [];
		foreach ($this->tasksArr as $task_arr) {
			$this->tasks[] = new Task($task_arr);
		}
	}

	public function getTasks() {
		if (empty($this->tasks)) {
			$this->populateTasks();
		}
		return $this->tasks;
	}

	public function getNextTask() {
		if (empty($this->tasks)) return false;
		if (!isset($this->tasks[$this->lastTaskIdx])) $this->lastTaskIdx = 0;
		return $this->tasks[$this->lastTaskIdx++];
	}
}