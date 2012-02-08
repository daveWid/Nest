<?php
/**
 * Find the Nest system path
 *
 * If this is a absolute path, then just roll with it
 * Otherwise do some realpath magic on it to get the full server path
 */
$config = parse_ini_file("config.ini");

$system_path = (substr($config['system_path'], 0, 1) === DIRECTORY_SEPARATOR) ?
	$system :
	realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.$config['system_path']);

$system_path = rtrim($system_path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

// Bootsrap it
include $system_path."bootstrap.php";

$config['system_path'] = $system_path;
unset($system_path);

// And rock and roll!
$nest = new \Nest\Core(dirname(__FILE__), new \Nest\Config($config));
echo $nest->execute();