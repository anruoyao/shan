<?php
$pbstr='scode,name,id,xid,title,status,likes,oppose,author,ico,visits,update_time,subtitle,subscode,lastid,lasttitle,firstid,keywords,description,over,mcode,tip_,uid,qyxxx';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$cs=$_SERVER["QUERY_STRING"];
$cs=cleanstr($cs);
$cs=explode('&',$cs);
$cs=$cs[0];
$cs=explode('=',$cs);
$cs=$cs[0];
$cs=explode('?',$cs);
$cs=$cs[0];
$cs=str_replace('.html','',$cs);
$path=explode('/',$cs);
$scode=$path[2];
$id=$path[3];
$arr=explode('_',$id);
$id=$arr[0];
$xid=$arr[1];
if($scode!='none' && !is_numeric($scode) && is_numeric($id)){
	require_once(ROOT_PATH.'/include/article_pbcode_xyz/index.php');
}else{
	if(!$scode || !$id){
		require_once(ROOT_PATH.'/404.html');
		exit;
	}
	if(!is_numeric($scode) && $scode!='none'){
		$x=$dsql->one("select scode,name,contenttpl from ay_content_sort where filename='$scode' limit 1",1);
		$scode=$x[scode];
		$name=$x[name];
		$template=$x[contenttpl];
	}else{
		$scode='';
	}
	if(is_numeric($id)){
		$sql="id=$id";
	}else{
		$sql="filename='$id'";
		$id='';
	}
	$x=$dsql->one("select * from ay_content where $sql limit 1",1);
	$status=$x[status];
	$s=$x[scode];
	if($s && !$status){
	    $hr=$_SERVER['HTTP_REFERER'];
	    $hr=strtolower($hr);
	    $hua=$_SERVER['HTTP_USER_AGENT'];
	    $hua=strtolower($hua);
	    $arr=array('baidu','google','bing','360','sogou');
	    foreach($arr as $xx){
	    	if(strstr($hr,$xx) || strstr($hua,$xx)){
	    		$no404=1;
	    		break;
	    	}else{
	    		$no404=0;
	    	}
	    }
	    if($no404){
	        $dsql->amd("update ay_content set status=1 where $sql limit 1");
	        $status=1;
	    }else{
    		require_once(ROOT_PATH.'/404.html');
    		exit;
	    }
	}else if(!$s || !$status){
		require_once(ROOT_PATH.'/404.html');
		exit;
	}
	foreach($pbstr as $v){
		if(!$$v){
			$$v=$x[$v];
		}
	}

	
	$x=$dsql->one("select xid,xtitle from ay_article force index(669977_net_six) where status=1 and id=$id order by xid desc limit 1",1);
	$lastid=$x[xid];
	$lasttitle=$x[xtitle];
	$x=$dsql->one("select xid from ay_article force index(669977_net_six) where status=1 and id=$id order by xid asc limit 1",1);
	$firstid=$x[xid];


	$x_=checkuid($mcode,$id);
	$arr=explode('{697}',$x_);
	$tip_=$arr[1];
	$title=decode_string($title);
	$keywords=decode_string($keywords);
	$description=decode_string($description);
	$description=str_replace(array(PHP_EOL,"\t","\n","\r"),'<br>',$description);
	$content=reptags($content);
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
	
	if(!$name){
		$x=$dsql->one("select name,contenttpl from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$template=$x[contenttpl];
	}

	$qyxxx='';
	if($uid){
		$x=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
		$sign=$x[sign];
		if($sign==3){
			$qyxxx='
			<style>.Con_.jj .j_box .title h2{display: flex;align-items: center;}</style>
			<span style="color:#f90303;display:flex;font-size:14px;align-items: center;">
			（
			<svg t="1768111356541" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="11902" width="14" height="14"><path d="M806.592 992H217.152c-76.704 0-141.536-58.272-150.72-135.584L0.416 297.6a52.224 52.224 0 0 1 21.76-48.992 50.816 50.816 0 0 1 52.896-3.136l202.016 107.712 192.064-297.728A51.072 51.072 0 0 1 511.936 32c17.28 0 33.44 8.832 42.848 23.456l192.16 297.856 202.016-107.712a50.816 50.816 0 0 1 52.896 3.136c15.68 10.88 24.064 29.824 21.76 48.96L957.44 856.576C948.16 933.728 883.456 992 806.592 992zM113.632 383.36l54.528 460.736a49.664 49.664 0 0 0 49.12 44.128h589.44c24.96 0 46.048-18.912 49.12-44.128l54.528-460.704-156.896 83.68a50.816 50.816 0 0 1-66.656-17.536l-174.88-270.816-174.752 270.944c-14.432 22.4-43.296 29.952-66.656 17.536l-156.896-83.84z" fill="#f90303" p-id="11903"></path><path d="M511.936 792.576a103.04 103.04 0 0 1-71.36-28.8l-113.248-108.992a52.384 52.384 0 0 1-1.824-73.376 50.72 50.72 0 0 1 72.384-1.856l113.28 109.024 114.848-109.024a50.72 50.72 0 0 1 72.384 1.856c19.456 20.8 18.656 53.632-1.856 73.376l-113.28 108.992a103.04 103.04 0 0 1-71.328 28.8z" fill="#f90303" p-id="11904"></path></svg>
				签约作品）
			</span>';
		}
	}

	$template.='697code.com';

	foreach($pbstr as $v){
		$pbarr[ $v ]=$$v;
	}
	$this->assign('pbarr',$pbarr);
}
?>