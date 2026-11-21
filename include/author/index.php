<?php
$pbstr='template,author,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$x=$path[2];
$arr=explode('_',$x);
$author=$arr[0];
$page=$arr[1];
if($author && !$page){
	if(is_numeric($author)){
		$page=$author;
		$author='';
	}else{
		$page=1;
	}
}else if(!$author && !$page){
	$page=1;
	$author='';
}
foreach($pbstr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>