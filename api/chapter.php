<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
require_once(dirname(__FILE__).'/base.inc.php');

$sign=gpvar('sign');
$bookId=gpvar('bookId');

if(!$bookId || !is_numeric($bookId)){
	$xxx='{"code": 302,"content": "","msg": "参数格式错误"}';
}else if($sign!=$secret_){
	$xxx='{"code": 303,"content": "","msg": "签名错误"}';
}else{
	$x=$dsql->one("select update_time from ay_content where id=$bookId limit 1",1);
	$update_time=$x[update_time];
	$dd=$dsql->all("select xid from ay_article force index(669977_net_six) where status=1 and id=$bookId order by xid asc",1);
	foreach($dd as $xx){
		$ii++;
		$xid=$xx[xid];
		$xx=$dsql->one("select xtitle from ay_article where xid=$xid limit 1",1);
		$xtitle=$xx[xtitle];
		$url=$pcbaseall.'api/article.php?bookId='.$bookId.'&chapterId='.$xid.'&uid='.$uid.'&sign='.$sign;
		$xxx.='{"bookId": '.$bookId.',"chapterId": '.$xid.',"chapterIndex": '.$ii.',"chapterName": "'.$xtitle.'","updateDate": "'.$update_time.'","articleUrl": "'.$url.'"},';
	}
	if(!$xxx){
		$xxx='{"code": 200,"content": "","msg": "内容为空"}';
	}else{
		$xxx=trim($xxx,',');
		$xxx='{"code": 200,"content": [{"bookId": '.$bookId.',"chapterList": ['.$xxx.']}],"msg": "success"}';
	}
}



echo $xxx;
/*
200 成功
302 参数格式错误
303 签名错误
*/
?>