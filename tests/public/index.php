<?php

// Bootstrap the library
include "/usr/lib/php/Nest/bootstrap.php";

// And rock and roll!
$nest = new \Nest\Core(__DIR__);
echo $nest->execute();