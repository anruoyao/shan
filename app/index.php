<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
if(is_mobile()){
	$template='wap.html';
}else{
	$template='pc.html';
}
$arr=scandir(dirname(__FILE__));
foreach($arr as $k => $v){
	if(strstr($v,'.apk')){
		$apk=$v;
		break;
	}
}
if(!$apk){
	$noapp='<div class="noapp">还未定制app，可找客服了解<br>客服QQ：1981255858</div>';
}
$my=gpvar('my');
if($my=='down'){
	if(!$apk){
		$x=str_replace(array('<div class="noapp">','</div>'),'',$noapp);
		$x.='<br>';
		$url=$pcbaseall.'app/';
		error($x,$url,3);
	}
	$f='down.txt';
	$h=xxx_url($f);
	if(!$h){
		$h=0;
	}
	$h=$h+1;
	file_put_contents($f,$h);
	$url=$pcbaseall.'app/'.$apk;
	$x='
	<script>
	document.location.href="'.$url.'";
    setTimeout(function() {
    	window.opener=null;
    	window.open("","_self");
    	window.close();
    }, 2000);
	</script>';
	echo $x;
	exit;
}else if($my=='total'){
	$f='down.txt';
	$h=xxx_url($f);
	if(!$h){
		$h=0;
	}
	$x='
	<p style="font-size:12px">
	<span style="font-size:16px">APP下载：<font style="font-size:20px;color:red">'.$h.'</font> 次</span>
	</br>
	注：若有需要，删除根目录/app/total.txt可重新统计</p>';
	echo $x;
	exit;
}
include_once('template/'.$template);
?>