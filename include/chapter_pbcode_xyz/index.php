<?php
$pbstr='template,scode,name,id,xid,title,status,likes,oppose,author,ico,visits,update_time,subtitle,subscode,lastid,lasttitle,firstid,keywords,description,tip_';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$template=str_replace('_pbcode_xyz','',$template);
$id=$path[2];
$arr=explode('_',$id);
$id=$arr[0];
$xid=$arr[1];
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
	require_once(ROOT_PATH.'/404.html');
	exit;
}
$mcode=$x[mcode];
$x_=checkuid($mcode,$id);
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
if(!$likes){
	$likes=rand(1,50);
	$dsql->amd("update ay_content set likes=$likes where id=$id limit 1");
}
if(!$oppose){
	$oppose=rand(1,50);
	$dsql->amd("update ay_content set oppose=$oppose where id=$id limit 1");
}
$x=$dsql->one("select xid,xtitle from ay_article force index(669977_net_six) where status=1 and id=$id order by xid desc limit 1",1);
$lastid=$x[xid];
$lasttitle=$x[xtitle];
$x=$dsql->one("select xid from ay_article force index(669977_net_six) where status=1 and id=$id order by xid asc limit 1",1);
$firstid=$x[xid];
$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
$name=$x[name];
foreach($pbstr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>