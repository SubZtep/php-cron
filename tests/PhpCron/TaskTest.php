<?php
namespace PhpCron;

class TaskTest extends \PHPUnit_Framework_TestCase
{
	public function testShouldRun() {
		$task = new Task();
		$this->assertTrue($task->shouldRun());
		$task->nextRun = time();
		$this->assertTrue($task->shouldRun());
		$task->nextRun += 3600;
		$this->assertFalse($task->shouldRun());
	}

	public function testIsRunning() {
		$task = new Task(['exec' => 'sleep 1']);
		$this->assertFalse($task->isRunning());
		$task->run();
		$this->assertTrue($task->isRunning());
	}
}