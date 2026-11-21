<?php
global $key,$page;
$key=$path[2];
$page=$path[3];
$arr='key,page';
$arr=explode(',',$arr);
foreach($arr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>