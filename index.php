<?php
include(__DIR__.'/vendor/autoload.php');

// Get parameter
$opt = getopt('f:');
$rulesFile = isset($opt['f']) ? $opt['f'] : __DIR__.'/tasks.json';

// Init taskrunner
try {
	$runner = new PhpCron\TaskRunner($rulesFile);
} catch (Exception $e) {
	echo 'Init error: '.$e->getMessage()."\n";
	exit(1);
}

// Run task(s)
$runner->run();
