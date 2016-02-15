<?php
namespace PhpCron;

class Task
{
	public $exec;        // string | execute line
	public $secs;        // int    | frequency in seconds
	public $log = null;  // string | log identifier
	public $pid = null;  // PID
	public $nextRun = 0; // Next run timestamp

	public function __construct($data = []) {
		$this->exec = isset($data['exec']) ? $data['exec'] : null;
		$this->secs = isset($data['secs']) ? (int)$data['secs'] : 0;
		if (isset($data['log'])) {
			$this->log = $data['log'];
			if (!$this->hasLogAccess()) {
				throw new \Exception('Access denied to '.$this->log);
			}
		}
	}

	private function hasLogAccess() {
		if (is_null($this->log)) {
			return true;
		}
		if (!file_exists($this->log)) {
			if (@touch($this->log)) {
				unlink($this->log);
				return true;
			}
			return false;
		}
		return is_writable($this->log);
	}

	private function getLogArgv() {
		if ($this->log) {
			file_put_contents(
				$this->log,
				"\n\n-----\n".date('Y-m-d H:i:s')."\n-----\n\n",
				FILE_APPEND
			);
			return ' >> '.$this->log.' 2>> '.$this->log;
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