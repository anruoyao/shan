<?php
$pbstr='template,key,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$key=$path[2];
$page=$path[3];
$key=strip_tags($key);
$key=urldecode($key);
$template='so.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>