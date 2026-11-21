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
$chapterid=gpvar('chapterid');

if($sign!=$sign_){
	$xxx='{"code": 500,"data": "","msg": "身份验证错误"}';
}else if(!$bookid || !is_numeric($bookid) || !$chapterid || !is_numeric($chapterid)){
	$xxx='{"code": 500,"data": "","msg": "请求参数错误"}';
}else{
	$x=$dsql->one("select update_time from ay_content where id=$bookid limit 1",1);
	$update_time=$x['update_time'];
	if(!$update_time){
		$xxx='{"code": 500,"data": "","msg": "请求数据不存在"}';
	}else{
		$x=$dsql->one("select xtitle,content from ay_article where xid=$chapterid limit 1",1);
		$xtitle=$x[xtitle];
		$content=$x[content];
		if(!$xtitle){
			$xxx='{"code": 500,"data": "","msg": "请求数据不存在"}';
		}else{
			$content=sbyouNet_Body($chapterid,$content);
			$content=str_replace(array('<div id="bodybox">','</div>','<p>'),'',$content);
			$content=str_replace('</p>','\r\n',$content);
			$content=str_replace('"','\"',$content);

			$x=$dsql->one("select xid from ay_article where status=1 and id=$bookid order by xid asc limit $free_chapter,1",1);
			$xid=$x[xid];
			if($xid && $chapterid>=$xid){
				$is_vip='1';
			}else{
				$is_vip='0';
			}

			$update_time=strtotime($update_time);
			$update_time.=substr($update_time,-3);
			if($is_vip==1){
				$free=0;
			}else{
				$free=1;
			}
			$xxx='{
				"id": '.$chapterid.',
				"name": "'.$xtitle.'",
				"content": "'.$content.'",
				"publishTime": '.$update_time.',
				"free":'.$free.'
			}';
			$xxx='{"code": 1,"data": '.$xxx.',"msg": "success"}';
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