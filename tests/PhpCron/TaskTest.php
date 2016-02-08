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
}