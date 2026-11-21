<?php
$pbstr='template,scode,name,id,page,title,status,likes,oppose,author,ico,visits,update_time,subtitle,subscode,keywords,description,over,$orderby,zd,zdurl,mcode,tip_';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$id=$path[2];
$page=$path[3];
$arr=array('&','=','?');
foreach($arr as $v){
	$id=explode($v,$id);
	$id=$id[0];
	$page=explode($v,$page);
	$page=$page[0];
}
if(strstr($id,'x') && is_numeric(substr($id,1))){
	$id=ltrim($id,'x');
	$orderby='desc';
}
if(is_numeric($id)){
	$sql="id=$id";
}else{
	$sql="filename='$id'";
	$id='';
}
$x=$dsql->one("select * from ay_content where $sql limit 1",1);
foreach($pbstr as $v){
	if(!$$v){
		$$v=$x[$v];
	}
}
if(!$scode || !$status){
	require_once(dirname(__FILE__).'/../../404.html');
	exit;
}
if($orderby){
	$zd='正序';
	$zdurl=pbcode('chapter',$id);
}else{
	$zd='倒序';
	$zdurl=pbcode('chapter','x'.$id,'','','','','',1);
}
$x_=checkuid($mcode,$id,'','','wap');
$arr=explode('{697}',$x_);
$tip_=$arr[1];
$title=decode_string($title);
$keywords=decode_string($keywords);
$description=decode_string($description);
$author=decode_string($author);
$ico=ico($ico,1);
if($subtitle==1){
	$over='完结';
}else{
	$over='连载';
}
$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid desc limit 1",1);
$lastid=$x[xid];
$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
$name=$x[name];
$template='chapter.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>