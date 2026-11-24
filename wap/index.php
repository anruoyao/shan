<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
require_once('include/abc.seo.php');
require_once('include/wap.fun.php');
$cs=$_SERVER["QUERY_STRING"];
$path=explode('/',$cs);
$x1=$path[0];
$arr=explode('_',$x1);
$x1=$arr[0];
if(strstr($x1,'&')){
    $x=explode('&',$x1);
    $x1=$x[1];
}
$x2=$path[1];
$x=$dsql->one("select value from ay_config where name='login_statuss' limit 1",1);
$login_status=$x[value];
$x=$dsql->one("select value from ay_config where name='register_statuss' limit 1",1);
$register_status=$x[value];
if($x1=='member'){
	require_once('include/member/index.php');
}else if($x1=='pbcode'){
	require_once('include/'.$x2.'/index.php');
}else if(is_numeric($x1) && (!$x2 || (isset($x2[0]) && ($x2[0]=='&' || $x2[0]=='?')))){
	require_once('include/category/index.php');
}else if($x1=='login'){
	$uid=$path[1];
	$username=$path[2];
	$register_time=$path[3];
	$url=gpvar('url');
	if($uid && $username && $register_time){
		$register_time=date('Y-m-d H:i:s',$register_time);
		$x=$dsql->one("select id,ucode,gid from ay_member where ucode=$uid and username='$username' and register_time='$register_time' limit 1",1);
		$id=$x[id];
		$ucode=$x[ucode];
		$gid=$x[gid];
		if($uid==$ucode){
	        session('pboot_uid',$id);
	        session('pboot_ucode',$ucode);
	        session('pboot_gcode',$gid);
		}
	}else{
        session('pboot_uid','');
        session('pboot_ucode','');
        session('pboot_gcode','');
	}
	if(!$url){
		$url=$pcbaseall;
	}
	$xxx='<script>top.location.href="'.$url.'";</script>';
	echo $xxx;
	exit;
}else{
	if($x1 && !is_numeric($x1)){
		$x=$dsql->one("select scode from ay_content_sort where filename='$x1' limit 1",1);
		$x=$x[scode];
		if($x){
			require_once('include/category/index.php');
			exit;
		}
	}
	$tpl='template';
	$template='index.html';
}
require_once($tpl.'/'.$template);
?>
