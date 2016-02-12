<?php
namespace PhpCron;

class RulesTest extends \PHPUnit_Framework_TestCase
{
	public function testGeNextTask() {
		$rules = new Rules();
		$this->assertEmpty($rules->getNextTask());
	}
}