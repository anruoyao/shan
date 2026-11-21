<?php
global $downTXT;
if(!$downTXT){
	error('暂未开放下载',$pcbase);
	exit;
}
$scode=$path[2];
$id=$path[3];
$x=$dsql->one("select id,title,subtitle from ay_content where scode=$scode and id=$id limit 1",1);
$title=$x[title];
$subtitle=$x[subtitle];
$o=$x[id];
if(!$o){
	require_once(ROOT_PATH.'/404.html');
	exit;
}
checkuid('txt',$id);
$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
$filename=$x[filename];
$txt=ROOT_PATH.'/txt/txt/'.$filename.'/'.$id.'.txt';
if(file_exists($txt)){
	$filesize=filesize($txt);
	if($filesize>50000000){
		unlink($txt);
	}
}
if(file_exists($txt)){
	if(!$subtitle){
		$filemtime=filemtime($txt);
		$t=time();
		$x1=$t-$filemtime;
		$x2=24*60*60;
		$x=$x1-$x2;
		if($x>0){
			$newtxt=1;
		}
	}
	if(!$newtxt){
		downtxt($txt,$title);
		exit;
	}
}
$arr='scode,id';
$arr=explode(',',$arr);
foreach($arr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>