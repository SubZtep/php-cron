<?php
class Task
{
	public $exec;       // string | execute line
	public $secs;       // int    | frequency in seconds
	public $log;        // string | log identifier
	public $pid = null; // PID

	public function __construct($data = []) {
		$this->exec = isset($data['exec']) ? $data['exec'] : null;
		$this->secs = isset($data['secs']) ? $data['secs'] : null;
		$this->log = isset($data['log']) ? $data['log'] : null;
	}

	public function isRunning() {
		if (!is_null($this->pid)) {
			if (file_exists('/proc/'.$this->pid)) {
				return true;
			}
			$this->pid = null;
		}
		return false;
	}
}