<?php
$pbstr='template,scode,name,id,title,xid,xtitle,status,author,ico,subtitle,content,mcode,tip_,free_chapter,content_words,content_pages,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$xid=$path[2];
$x=$path[3];
if($x && (strstr($x,'_') || is_numeric($x))){
	$arr=explode('_',$x);
}else{
	$arr=explode('_',$xid);
}
$x=$arr[0];
$page=$arr[1];
if(!$page){
	$page=1;
}
if($x && is_numeric($x)){
	$xid=$x;
	$id='';
}
$x=$dsql->one("select * from ay_article where xid=$xid limit 1",1);
$status=$x[status];
$id=$x[id];
$xtitle=$x[xtitle];
$content=$x[content];
if(!$id || !$status){
	require_once(dirname(__FILE__).'/../../404.html');
	exit;
}
$xtitle=decode_string($xtitle);


if(strstr($content,'http')){
	$x=mb_substr($content,0,1);
	if($x!='h'){
		$content=str_replace('http','htttp',$content);
		$content=sbyouNet_Body($xid,$content,$jsonline);
		$content=str_replace('htttp','http',$content);
	}else{
		$content=sbyouNet_Body($xid,$content,$jsonline);
	}
}else{
	$content=sbyouNet_Body($xid,$content,$jsonline);
}


content_page();




	$arr=explode('</p>',$content);
	$ii=0;
	$content='';
	foreach($arr as $xx){
		$ii++;
		$x=$dsql->one("select count(*) from ay_member_xidcmt where xid=$xid and idx=$ii",1);
		$count=$x['count(*)'];
		$content.=$xx;
		if(!strstr($xx,'</div>')){
			if(!$count){
				$count='~.~';
			}
			$content.='<span class="review" onclick="xidcmt(event,'.$xid.','.$ii.')">'.$count.'</span></p>';
		}
	}

$x=$dsql->one("select * from ay_content where id=$id limit 1",1);
$scode=$x[scode];
$title=$x[title];
$author=$x[author];
$mcode=$x[mcode];
$ico=$x[ico];
$ico=ico($ico,1);
$subtitle=$x[subtitle];
$title=decode_string($title);
$author=decode_string($author);
if($subtitle==1){
	$over='完结';
}else{
	$over='连载';
}
$x=checkuid($mcode,$id,$xid,$content,'wap');
$arr=explode('{697}',$x);
$content__=$arr[0];
$tip__=$arr[1];
if($tip__){
	if(!$free_chapter){
		$free_chapter=0;
	}
	$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid asc limit $free_chapter,1",1);
	$x=$x[xid];
	if($x && $xid>=$x){
		$content=$content__;
		$tip_=$tip__;
	}
}
$content=reptags($content);
$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
$name=$x[name];
$template='article.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>