<?php
$page=$path[2];
if(!$page){
	$page=1;
}
$arr='page,template';
$arr=explode(',',$arr);
foreach($arr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>