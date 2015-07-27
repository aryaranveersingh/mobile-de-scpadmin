<?php

include('config.php');
echo exec('rm -r '.$app_path.'*');

if(isset($_REQUEST['deleteall'])) {
	exec('rm -r *');
}