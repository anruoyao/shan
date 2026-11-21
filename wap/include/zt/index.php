<?php
global $scode,$page,$name;
$scode=$path[2];
$page=$path[3];
if($scode){
	$x=$dsql->one("select name from ay_zt where scode=$scode limit 1",1);
	$name=$x[name];
}
$arr='scode,page,name';
$arr=explode(',',$arr);
foreach($arr as $v){
	$pbarr[ $v ]=$$v;
}
require_once(dirname(__FILE__)."/../../template/zt.html");
?>