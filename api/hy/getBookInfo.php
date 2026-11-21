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
	$x=$dsql->one("select * from ay_content where id=$bookid limit 1",1);
	$status=$x['status'];

	if(!$status){
		$xxx='{"code": 500,"data": "","msg": "请求数据不存在"}';
	}else{
		$id=$x[id];
		$title=$x[title];
		$author=$x[author];
		$description=$x[description];
		$subtitle=$x[subtitle];

		$ico=$x[ico];
		$scode=$x[scode];
		$tags=$x[tags];
		$update_time=$x[update_time];
		$update_time=strtotime($update_time);
		$update_time.=substr($update_time,-3);


		if(!strstr($ico,'http')){
			$ico_=ltrim($ico,'/');
			$ico_=ROOT_PATH.'/'.$ico;
			$ico_base64=base64_encode(file_get_contents($ico_));
		}else{
			$ico_base64='';
		}

		$x=$catarr[$scode];
		$x=explode(',',$x);
		$sort=$x[0];
		$group=$x[1];
		
		$description=strip_tags($description);
		$description=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$description);
		$description=str_replace('"','\"',$description);
		$ico=ico($ico,1);
		if($subtitle!=1){
			$subtitle='0';
		}else{
			$subtitle='1';
		}
		$xxx='{
			"id": '.$id.',
			"name": "'.$title.'",
			"author": "'.$author.'",
			"authorId": '.$id.',
			"introduce": "'.$description.'",
			"group": '.$group.',
			"sort": '.$sort.',
			"tags": "'.$tags.'",
			"icon": "'.$ico.'",
			"ico_base64": "'.$ico_base64.'",
			"finish": '.$subtitle.',
			"isbn": "",
			"finishTime": "'.$update_time.'",
			"updateTime": "'.$update_time.'"
		}';
		$xxx='{"code": 1,"data": '.$xxx.',"msg": "success"}';
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