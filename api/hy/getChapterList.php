<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../../core/start.php');
}
require_once(dirname(__FILE__).'/base.inc.php');

$sign=gpvar('sign');
$bookid=gpvar('bookid');

if($sign!=$sign_){
	$xxx='{"code": 500,"data": "","msg": "身份验证错误"}';
}else if(!$bookid || !is_numeric($bookid)){
	$xxx='{"code": 500,"data": "","msg": "请求参数错误"}';
}else{
	$x=$dsql->one("select status from ay_content where id=$bookid limit 1",1);
	$status=$x['status'];
	if(!$status){
		$xxx='{"code": 500,"data": "","msg": "请求数据不存在"}';
	}else{
		$x=$dsql->one("select xid from ay_article where status=1 and id=$bookid limit $free_chapter,1",1);
		$xid_start=$x['xid'];
		$dd=$dsql->all("select xid from ay_article where status=1 and id=$bookid order by xid asc",1);
		foreach($dd as $xx){
			$ii++;
			$xid=$xx['xid'];
			if($xid<$xid_start){
				$is_vip=0;
			}else{
				$is_vip=1;
			}
			$xxx.='{
				"chapterId": '.$xid.',
				"chapterNumber": "'.$ii.'"
			},';
		}
		if(!$xxx){
			$xxx='{"code": 500,"data": "","msg": "请求数据不存在"}';
		}else{
			$xxx=trim($xxx,',');
			$xxx='{
				"volumeId":'.$bookid.',
				"volumeTitle":"正文",
				"volumeNumber":1,
				"chapters":['.$xxx.']
			}';
			$xxx='{"code": 1,"data": {"volumes": ['.$xxx.']},"msg": "success"}';
		}
	}
}



echo $xxx;
/*
1 success
501 请求参数错误
502 身份验证错误
503 请求数据不存在
504 未知错误
*/
?>