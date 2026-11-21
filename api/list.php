<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
require_once(dirname(__FILE__).'/base.inc.php');

$sign=gpvar('sign');
$page=gpvar('page');

if($sign!=$secret_){
	$xxx='{"code": 303,"content": "","msg": "签名错误"}';
}else{
	$sq='sq_'.$qudao;
	if($page && is_numeric($page)){
		$x=$dsql->one("select count(*) from ay_content force index(15fox_com_ss) where status=1 and $sq=1",1);
		$total=$x['count(*)'];
		!$page?$page=1:'';
		$pagenum=ceil($total/$onepagenum);
		$prepg=$page-1;
		$nextpg=($page==$pagenum?0:$page+1);
		$offset=($page-1)*$onepagenum;
		$sql="limit $offset,$onepagenum";
	}else{
		$sql="";
	}
	$dd=$dsql->all("select id from ay_content force index(669977_net_suis) where status=1 and $sq=1 order by update_time desc $sql",1);
	foreach($dd as $xx){
		$id=$xx[id];
		$xx=$dsql->one("select title,update_time from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$update_time=$xx[update_time];
		$url=$pcbaseall.'api/book.php?bookId='.$id.'&uid='.$uid.'&sign='.$sign;
		$xxx.='{"bookId": '.$id.',"bookName": "'.$title.'","bookUrl": "'.$url.'"},';
	}
	if(!$xxx){
		$xxx='{"code": 200,"content": "","msg": "内容为空"}';
	}else{
		$xxx=trim($xxx,',');
		$xxx='{"code": 200,"content": ['.$xxx.'],"msg": "success"}';
	}
}




echo $xxx;
/*
200 成功
302 参数格式错误
303 签名错误
*/
?>