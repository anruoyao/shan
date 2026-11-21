<?php
$scode=$path[0];
if(strstr($scode,'&')){
    $x=explode('&',$scode);
    $scode=$x[1];
}
$arr=explode('_',$scode);
$scode=$arr[0];
$page=$arr[1];
if(!is_numeric($scode)){
	$x=$dsql->one("select scode from ay_content_sort where filename='$scode' limit 1",1);
	$scode=$x['scode'];
}
$x=$dsql->one("select scode,name,title,keywords,description,filename from ay_content_sort where scode=$scode limit 1",1);
$scode=$x['scode'];
$name=$x['name'];
$title=$x['title'];
$keywords=$x['keywords'];
$description=$x['description'];
$filename=$x['filename'];
if(!$name){
	require_once(dirname(__FILE__).'/../../404.html');
	exit;
}
$template='category.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>