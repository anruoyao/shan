<?php
$pbstr='template,scode,page,name';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$x=$path[2];
$arr=explode('_',$x);
$scode=$arr[0];
$page=$arr[1];
if($scode && !$page){
	$page=$scode;
	$scode='';
}else if(!$scode && !$page){
	$page='';
	$scode='';
}
if($scode){
	$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
	$name=$x[name];
}
foreach($pbstr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>