<?php
$pbstr='template,ob,page,id,tag,obarr';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$ob=$path[2];
$page=$path[3];
if(!$ob){
	$ob='p1';
}
$obarr=array('按热门排序','按内容量排序','按时间排序');
if(is_numeric($ob)){
	$x=$dsql->one("select id,tag from ay_taglist where tid=$ob limit 1",1);
	$id=$x[id];
	$tag=$x[tag];
}
foreach($pbstr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>