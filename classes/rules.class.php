<?php
class Rules
{
	private $tasks = [];
	private $lastTaskIdx;
	private $tasksFileName;
	private $lastFileMod = 0;

	public function __construct($tasksFileName) {
		$this->tasksFileName = $tasksFileName;
		$this->populateTasks();
	}

	private function getJsonData() {
		$json = file_get_contents($this->tasksFileName);
		$this->lastFileMod = filemtime($this->tasksFileName);
		$json = preg_replace('!/\*.*?\*/!s', '', $json); // remove comments
		return json_decode($json, true);
	}

	public function resetTasks() {
		$this->lastTaskIdx = 0;
	}

	public function populateTasks() {
		$this->tasks = [];
		$data = $this->getJsonData();
		foreach ($data as $taskData) {
			$this->tasks[] = new Task($taskData);
		}
		$this->resetTasks();
	}

	public function getTasks() {
		if (empty($this->tasks)) {
			$this->populateTasks();
		}
		return $this->tasks;
	}

	public function isTasksUpdated() {
		clearstatcache();
		$lastMod = filemtime($this->tasksFileName);
		if ($this->lastFileMod < $lastMod) {
			$this->lastFileMod = $lastMod;
			return true;
		}
		return false;
	}

	public function getNextTask() {
		if (empty($this->tasks) || !isset($this->tasks[$this->lastTaskIdx])) {
			return false;
		}
		return $this->tasks[$this->lastTaskIdx++];
	}
}