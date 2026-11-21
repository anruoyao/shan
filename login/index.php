<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','api');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
/*+++++++++++++++++++++++++++++++++++
开发者：	669977.net
业务QQ：	1981-25-5858
源码升级日志：	update.669977.net
更多原创源码：	669977.net
+++++++++++++++++++++++++++++++++++*/
$x='QUERY_STRING';
$x=str_replace('.html','',$_SERVER[ $x ]);
$x=explode('&',$x);
$x=$x[0];
$x=explode('=',$x);
$x=$x[0];
$x=explode('-',$x);
$my=$x[0];
if($my=='weixin'){
	$template=$my.'.html';
	require_once('template/'.$template);
}else{
	$id=$x[1];
	$username=gpvar('u');
	$nickname=gpvar('n');
	if($my=='weibo' && !$username){
		$x=wblogin();
		$arr=explode(',',$x);
		$id=$arr[0];
		$wb=$arr[1];
	}
	if($username){
		$my=gpvar('my');
		$template=$my.'.html';
	}else if(!$my){
		$x='缺少相关参数！';
		error($x,$pcbase);
		exit;
	}else{
		$template=$my.'.html';
	}
	require_once('template/'.$template);
}
?>