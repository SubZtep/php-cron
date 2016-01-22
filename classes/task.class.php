<?php
class Task
{
	public $exec;        // string | execute line
	public $secs;        // int    | frequency in seconds
	public $log;         // string | log identifier
	public $pid = null;  // PID
	public $nextRun = 0; // Next run timestamp

	public function __construct($data = []) {
		$this->exec = $data['exec'];
		$this->secs = isset($data['secs']) ? (int)$data['secs'] : 0;
		$this->log = isset($data['log']) ? $data['log'] : null;
	}

	private function getLogArgv() {
		if ($this->log) {
			$log = __DIR__."/../log/{$this->log}.txt";
			file_put_contents(
				$log,
				"\n\n-----\n".date('Y-m-d H:i:s')."\n-----\n\n",
				FILE_APPEND
			);
			return " >> $log 2>> $log";
		}
		return ' > /dev/null 2> /dev/null';
	}

	public function run() {
		$this->pid = exec($this->exec.$this->getLogArgv().' & echo $!');
		$this->nextRun = time() + $this->secs;
	}

	public function shouldRun() {
		return time() >= $this->nextRun;
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