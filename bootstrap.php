<?php

// Setup autoloading
$path = __DIR__.DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR;
include $path."SplClassLoader.php";

$autoload = new SplClassLoader(null, $path);
$autoload->register();
