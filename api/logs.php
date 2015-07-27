<?php

// include('config.php');

$log = "tail -n 18 scrape.log";
echo passthru($log);

