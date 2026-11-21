<?php
$pbstr='jsonline,template,name,scode,id,title,xid,xtitle,status,content,author,update_time,ico,authorTT,tip_,free_chapter,content_words,content_pages,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$tpl='article';
$template=str_replace('_pbcode_xyz','',$template);
$template=str_replace('contents','article',$template);
$xx_=$path[1];
$xid=$path[2];
$x=$path[3];
if($xx_=='contents'){
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
foreach($pbstr as $v){
	if(!$$v){
		$$v=$x[$v];
	}
}
if(!$id || !$status){
	require_once(ROOT_PATH.'/404.html');
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
		$xx=str_replace('<p>','<p onclick="xidcmt(event,'.$xid.','.$ii.')">',$xx);
		$content.=$xx;
		if(!strstr($xx,'</div>')){
			if($count){
				$content.='<span class="review">'.$count.'</span></p>';
			}else{
				$content.='</p>';
			}
		}
	}


$x=$dsql->one("select * from ay_content where id=$id limit 1",1);
$scode=$x[scode];
$title=$x[title];
$author=$x[author];
$update_time=$x[update_time];
$ico=$x[ico];
$ico=ico($ico,1);
if($author){
	$authorTT='|&nbsp;作者:<a href="'.pbcode('author',$author).'" target="_blank">'.$author.'</a>';
}
$mcode=$x[mcode];
$x=checkuid($mcode,$id,$xid,$content);
$arr=explode('{697}',$x);
$content__=$arr[0];
$tip__=$arr[1];
if($tip__){
	if(!$free_chapter){
		$free_chapter=0;
	}
	$x=$dsql->one("select xid from ay_article force index(669977_net_six) where status=1 and id=$id order by xid asc limit $free_chapter,1",1);
	$x=$x[xid];
	if($x && $xid>=$x){
		$content=$content__;
		$tip_=$tip__;
	}
}
$content=reptags($content);
$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
$name=$x[name];
foreach($pbstr as $v){
	$pbarr[ $v ]=$$v;
}
$this->assign('pbarr',$pbarr);
?>