<?php

// Setup autoloading
include "classes".DIRECTORY_SEPARATOR."Autoloader.php";

$autoload = new \Nest\Autoloader;
$autoload->register();

// Load up the Markdown renderer
include "vendor".DIRECTORY_SEPARATOR."markdown".DIRECTORY_SEPARATOR."markdown.php";