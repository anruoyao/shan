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
$chapterId=gpvar('chapterId');

if(!$bookId || !is_numeric($bookId) || !$chapterId || !is_numeric($chapterId)){
	$xxx='{"code": 302,"content": "","msg": "参数格式错误"}';
}else if($sign!=$secret_){
	$xxx='{"code": 303,"content": "","msg": "签名错误"}';
}else{
	$x=$dsql->one("select update_time from ay_content where status=1 and id=$bookId limit 1",1);
	$update_time=$x[update_time];
	if(!$update_time){
		$xxx='{"code": 200,"content": "","msg": "内容为空"}';
	}else{
		$x=$dsql->one("select xtitle,content from ay_article where status=1 and xid=$chapterId limit 1",1);
		$xtitle=$x[xtitle];
		$content=$x[content];
		if(!$xtitle){
			$xxx='{"code": 200,"content": "","msg": "内容为空"}';
		}else{
			$content=sbyouNet_Body($chapterId,$content);
			$content=str_replace(array('<div id="bodybox">','</div>','<p>'),'',$content);
			$content=str_replace('</p>','\r\n',$content);
			$content=str_replace('"','\"',$content);
			$xxx='{"code": 200,"content": {"chapterName": "'.$xtitle.'","chapterContent": "'.$content.'","updateDate": "'.$update_time.'"},"msg": "success"}';
		}
	}
}



echo $xxx;
/*
200 成功
302 参数格式错误
303 签名错误
*/
?>