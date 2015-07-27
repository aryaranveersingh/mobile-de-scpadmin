<?php


//"fayhan@hotmail.de"; 
//"lalezar8485"
$username=$_POST['user'];
$password=$_POST['pass']; 


$url="https://kleinanzeigen.ebay.de/anzeigen/m-einloggen.html"; 


$postdata = "loginMail=".$username."&password=".$password."&targetUrl=";

$ch = curl_init(); 
curl_setopt ($ch, CURLOPT_URL, $url); 
// curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt ($ch, CURLOPT_COOKIEJAR,"ebay.txt"); 
curl_setopt ($ch, CURLOPT_COOKIEFILE,"ebay.txt");
echo $result = curl_exec ($ch); 
curl_close($ch);

$ch = curl_init(); 
curl_setopt ($ch, CURLOPT_URL, $url); 
// curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt ($ch, CURLOPT_COOKIEJAR,"ebay.txt"); 
curl_setopt ($ch, CURLOPT_COOKIEFILE,"ebay.txt");

curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata); 
curl_setopt ($ch, CURLOPT_POST, TRUE); 
echo $result = curl_exec ($ch); 
curl_close($ch);