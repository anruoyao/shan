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
	$x=$dsql->one("select * from ay_content where id=$bookId limit 1",1);
	$id=$x[id];
	if(!$id){
		$xxx='{"code": 200,"content": "","msg": "内容为空"}';
	}else{
		$title=$x[title];
		$author=$x[author];
		$description=$x[description];
		$subtitle=$x[subtitle];
		if($subtitle){
			$subtitle=1;
		}else{
			$subtitle=0;
		}
		$ico=$x[ico];
		$scode=$x[scode];
		$tags=$x[tags];
		$update_time=$x[update_time];
		$subscode=$x[subscode];
		$description=strip_tags($description);
		$description=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$description);
		$description=str_replace('"','\"',$description);
		if(!strstr($ico,'http')){
			$ico_=ltrim($ico,'/');
			$ico_=ROOT_PATH.'/'.$ico;
			$ico_base64=base64_encode(file_get_contents($ico_));
		}else{
			$ico_base64='';
		}
		$ico=ico($ico,1);
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
		$xid=$x[xid];
		$xtitle=$x[xtitle];
		$chapterUrl=$pcbaseall.'api/chapter.php?bookId='.$id.'&uid='.$uid.'&sign='.$sign;
		$xxx='{"code": 200,"content": {"authorPename": "'.$author.'","bookId": '.$id.',"bookName": "'.$title.'","bookStatus": '.$subtitle.',"categoryId": '.$scode.',"categoryName": "'.$name.'","coverImageUrl": "'.$ico.'","coverImageBase64": "'.$ico_base64.'","introduction": "'.$description.'","keyWord": "'.$tags.'","lastUpdateChapterName": "'.$xtitle.'","lastUpdateChapterDate": "'.$update_time.'","wordCount": '.$subscode.',"chapterUrl": "'.$chapterUrl.'"},"msg": "success"}';
	}
}



echo $xxx;
/*
200 成功
302 参数格式错误
303 签名错误
*/
?>