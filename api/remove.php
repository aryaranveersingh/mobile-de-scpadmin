<?php

include('config.php');
echo exec('rm '.$app_path.date('d-M-Y',strtotime('now')).'.csv');