<?php
$x=$_SERVER['HTTP_USER_AGENT'];
$x=strtolower($x);
if(strstr($x,'html5plus') && !$appno){
	$x='<link rel="stylesheet" type="text/css" href="'.$pcbaseall.'app/append/style/css/common.css" />';
	echo $x;
}
?>