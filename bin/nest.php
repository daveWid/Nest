#!/usr/bin/php
<?php

// Bootstrap
include realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").
	DIRECTORY_SEPARATOR."bootstrap.php";

// CLI
$cli = new \Nest\CLI($argv);
$cli->run();
