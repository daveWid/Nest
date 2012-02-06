#!/usr/bin/php
<?php

// Bootstrap
include "Nest/bootstrap.php";

// CLI
$cli = new \Nest\CLI($argv);
$cli->run();
