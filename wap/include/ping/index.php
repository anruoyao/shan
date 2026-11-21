<?php
$pbstr='template,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$page=$path[2];
$arr=array('&','=','?');
foreach($arr as $v){
	$page=explode($v,$page);
	$page=$page[0];
}
$template='ping.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>