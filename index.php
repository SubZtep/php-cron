<?php
include(__DIR__.'/vendor/autoload.php');

try {
	$runner = new PhpCron\TaskRunner(__DIR__.'/tasks.json');
} catch (Exception $e) {
	echo 'Init error: '.$e->getMessage()."\n";
	exit(1);
}

$runner->run();
