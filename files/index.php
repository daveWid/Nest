<?php

// Where is the Nest bootstrap.php file at?
$system = "../../nest";

/**
 * Configuration is done,
 * Don't modify below unless you really know what you are doing!
 */

/**
 * Find the Nest system path
 *
 * If this is a absolute path, then just roll with it
 * Otherwise do some realpath magic on it to get the full server path
 */
$config = parse_ini_file("config.ini");

$system_path = (substr($system, 0, 1) === DIRECTORY_SEPARATOR) ?
	$system :
	realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.$system);

$system_path = rtrim($system_path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

// Bootsrap it
include $system_path."bootstrap.php";

// And rock and roll!
$nest = new \Nest\Core(dirname(__FILE__), new \Nest\Config($config));
echo $nest->execute();