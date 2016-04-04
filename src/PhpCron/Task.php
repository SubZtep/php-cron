<?php
namespace PhpCron;

class Task
{
	public $exec;            // string | execute line
	public $secs;            // int    | frequency in seconds
	public $logFile = null;  // string | log file
	public $report = [];     // Email address(es) to send output report
	public $pid = null;      // PID
	public $nextRun = 0;     // Next run timestamp

	protected $tempFile;     // Store cron output temporary


	public function __construct($data = []) {
		$this->exec = isset($data['exec']) ? $data['exec'] : null;
		$this->secs = isset($data['secs']) ? (int)$data['secs'] : 0;
		$this->tempFile = tempnam(sys_get_temp_dir(), 'php-cron');

		if (isset($data['log'])) {
			$this->logFile = $data['log'];
			if (!$this->hasLogAccess()) {
				throw new \Exception('Access denied to '.$this->logFile);
			}
		}

		if (isset($data['report'])) {
			$this->report = explode(',', $data['report']);
		}
	}

	private function hasLogAccess() {
		if (is_null($this->logFile)) {
			return true;
		}
		if (!file_exists($this->logFile)) {
			if (@touch($this->logFile)) {
				unlink($this->logFile);
				return true;
			}
			return false;
		}
		return is_writable($this->logFile);
	}

	private function getLogArgv() {
		if ($this->logFile || $this->report) {
			file_put_contents(
				$this->logFile,
				'# - '.date('Y-m-d H:i:s')."\n",
				FILE_APPEND
			);
			return ' > '.$this->tempFile.' 2> '.$this->tempFile;
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

	public function isTempAvailable() {
		return file_exists($this->tempFile);
	}

	public function processTemp() {
		if (filesize($this->tempFile) > 0) {
			$content = file_get_contents($this->tempFile);

			if ($this->logFile) {
				file_put_contents($this->logFile, $content."\n", FILE_APPEND);
			}

			// Send emails
			foreach ($this->report as $email) {
				mail($email, '[Cron] '.$this->exec, $content);
			}
		}
		unlink($this->tempFile);
	}
}