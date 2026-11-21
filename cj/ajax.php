<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once(dirname(__FILE__).'/../core/start.php');
}
require_once('include/cj.fun.php');
$my=gpvar('my');
if($my=='cj'){
	$xxx=cj();
}
if($my=='go'){
	$xxx=goo();
}
echo $xxx;
?>