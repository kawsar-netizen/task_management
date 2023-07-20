<?php

//FIND  IP ADDRESSS
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
 //check for ip from share internet
  $ip = $_SERVER["HTTP_CLIENT_IP"];
  $check=1;
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
 // Check for the Proxy User
 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
 $check=2;
 die;

}
else
{
 $ip = $_SERVER["REMOTE_ADDR"];
 $check=3;
}

//$ip='192.168.0.107';
print $ip;
print '<br>'.$check;

?>