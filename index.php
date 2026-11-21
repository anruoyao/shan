<?php
$qs=$_SERVER['QUERY_STRING'];
if(!$qs){
	$f='pc.html';
	if(file_exists($f)){
	    $x=filesize($f);
	    if($x>1515){
	    	require('base.inc.php');
	    	require('include/rep.fun.php');
	        reptkd($f);
	        exit;
	    }
	}
}
define('IS_INDEX',true);
define('URL_BIND','home');
require dirname(__FILE__).'/core/start.php';