<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../../core/start.php');
}
require_once(dirname(__FILE__).'/base.inc.php');

$sign=gpvar('sign');

if($sign!=$sign_){
	$xxx='{"code": 500,"data": "","msg": "身份验证错误"}';
}else{
	$sq='sq_'.$qudao;
	$dd=$dsql->all("select id from ay_content where status=1 and $sq=1 order by id desc",1);
	foreach($dd as $xx){
		$id=$xx[id];
		$xxx.='{"id":'.$id.'},';
	}
	if(!$xxx){
		$xxx='{"code": 500,"data": "","msg": "请求数据不存在"}';
	}else{
		$xxx=trim($xxx,',');
		$xxx='{"code": 1,"data": {"books":['.$xxx.']},"msg": "success"}';
	}
}




echo $xxx;
/*
1 success
500 失败
*/
?>