<?php
$pbstr='template,my,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$my=$path[2];
$page=$path[3];
$arr=array('&','=','?');
foreach($arr as $v){
	$my=explode($v,$my);
	$my=$my[0];
	$page=explode($v,$page);
	$page=$page[0];
}
if(!$my){
	$my='visits';
}
$template='paihang.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>