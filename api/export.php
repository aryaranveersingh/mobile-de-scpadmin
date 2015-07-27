<?php

include('config.php');


if(isset($_GET['current']))
	$result = mysql_query("SELECT phone,adddate FROM phoneDatabase WHERE phone != 0 and adddate = '".date('d-M-Y',strtotime('now'))."'");
else if($_GET['fromDate']){
	$from_date = $_GET['fromDate'];
	$result = mysql_query("SELECT phone,adddate FROM phoneDatabase WHERE phone != 0 and adddate >= '".date('d-M-Y',strtotime($from_date))."'");
}
else
	$result = mysql_query("SELECT phone,adddate FROM phoneDatabase WHERE phone != 0 ");

$handle = fopen('export.csv', 'a+');
fputcsv($handle, array('phone','adddate'));

while($row = mysql_fetch_assoc($result))
{
		fputcsv($handle, array($row['phone'],$row['adddate']));
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename=export.csv');
header('Pragma: no-cache');
readfile('export.csv');
unlink('export.csv');