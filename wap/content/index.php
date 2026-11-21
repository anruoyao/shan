<?php
$x='QUERY_STRING';
$qs=str_replace('.html','',$_SERVER[ $x ]);
$qs=strip_tags($qs);
$qs=trim($qs);
$x=explode('?',$qs);
$x=$x[0];
if(!$qs || $x==404){
	require_once('../404.html');
	exit;
}
require_once('../../include/rep.fun.php');
if(strstr($qs,'category') || strstr($qs,'book')){
	if(strstr($qs,'book')){
		$qsArr=explode('?',$qs);
		$qs=$qsArr[0];
		$dir='../'.$qs;
		$file1=$dir.'/index.html';
		$file2=$dir.'/index.php';
		$file3='../include/contents/index.php';
		if(is_file($file1)){
			$template='category-xxx.html';
			$x=filesize($file1);
			if($x>1515){
				$x=sql("select name from ay_content_sort where filename='$qs' limit 1");
				$name=$x[name];
				require_once('../include/abc.seo.php');
				reptkd($file1,'','','','wap');
				exit;
			}
		}else if(is_file($file2)){
			require_once($file2);
			exit;
		}else if(is_file($file3)){
			$x=sql("select id,scode from ay_content where filename='$qs' limit 1");
			$id=$x[id];
			$scode=$x[scode];
		}else{
			require_once('../404.html');
			exit;
		}
	}else{
		$qsArr=explode('?',$qs);
		$qs=$qsArr[0];
		$qsArr=explode('-',$qs);
		$qs=$qsArr[0];
		$qs2=$qsArr[1];
		if(!$qs2){
			$dir='../'.$qs;
			$file=$dir.'/index.html';
			$filesize=filesize($file);
			if($filesize>1515){
				$template='category-xxx.html';
				if(is_numeric($qs)){
					$x=sql("select name,title,keywords,description from ay_content_sort where scode='$qs' limit 1");
					$name=$x[name];
	    			$seotitle=$x[title];
	    			$keywords=$x[keywords];
	    			$description=$x[description];
				}else{
					$x=sql("select name,title,keywords,description from ay_content_sort where filename='$qs' limit 1");
					$name=$x[name];
	    			$seotitle=$x[title];
	    			$keywords=$x[keywords];
	    			$description=$x[description];
				}
				require_once('../include/abc.seo.php');
				reptkd($file,'','','','wap');
				exit;
			}
		}
	}
	if(strstr($WAP_URL,'/wap/') || strstr($_SERVER['PHP_SELF'],'/wap/')){
		$url=pcbase('all').'wap/';
	}else{
		$x=explode('//',$WAP_URL);
		$x=$x[0];
		$url=$x.'//'.$HTTP_HOST.'/';
	}
	if($id){
		$x='book';
		$qs=$id;
		$url.='?pbcode/contents/'.$scode.'/'.$qs.'.html';
	}else{
		$x='category';
		if($qs2){
			$qs2='_'.$qs2;
		}
		$url.='?'.$qs.$qs2.'/';
	}
	$x=xxx_url($url);
	if(strstr($x,'ajax')){
		echo $x;
	}else if(strstr($x,'ERROR')){
		require_once('../404.html');
	}else{
		$x='<script>location.href="'.$url.'";</script>';
		echo $x;
	}
}else if(strstr($qs,'content')){
	$qsArr=explode('?',$qs);
	$qs=$qsArr[0];
	$qsArr=explode('_',$qs);
	$qs=$qsArr[0];
	$qs2=$qsArr[1];
	$qs3=$qsArr[2];
	if($qs3){
		$x='../'.$qs3.'/'.$qs.'.html';
		if(file_exists($x)){
			$x=xxx_url($x);
			if(strstr($x,'script')){
				echo $x;
				exit;
			}
		}
	}
	if($qs2){
		$qs2='_'.$qs2;
	}
	$file='../include/chapter/index.php';
	if(is_file($file)){
		$px='article';
		$sx='ay_'.$px;
		$idx='xid';
	}else{
		$px='content';
		$sx='ay_'.$px;
		$idx='id';
	}
	$x=sql("select status from $sx where $idx=$qs limit 1");
	$status=$x['status'];
	if($status==1){
		if(strstr($WAP_URL,'/wap/') || strstr($_SERVER['PHP_SELF'],'/wap/')){
			$url=pcbase('all').'wap/';
		}else{
			$x=explode('//',$WAP_URL);
			$x=$x[0];
			$url=$x.'//'.$HTTP_HOST.'/';
		}
		if($idx=='xid'){
			$url.='?pbcode/article/'.$qs.$qs2.'/';
		}else{
			$x=sql("select scode from ay_content where id=$qs limit 1");
			$scode=$x[scode];
			$url.='?pbcode/contents/'.$scode.'/'.$qs.$qs2.'.html';
		}
		$x=xxx_url($url);
		if(strstr($x,'script')){
			echo $x;
		}else{
			$x='<script>location.href="'.$url.'";</script>';
			echo $x;
		}
	}else{
		require_once('../404.html');
	}
}else if(strstr($qs,'chapter')){
	$qsArr=explode('?',$qs);
	$qs=$qsArr[0];
	$arr=explode('_',$qs);
	$qs=$arr[0];
	$qs2=$arr[1];
	if($qs2){
		$qs2='/'.$qs2;
	}
	$x=sql("select id from ay_content where filename='$qs' limit 1");
	$id=$x[id];
	if(strstr($WAP_URL,'/wap/') || strstr($_SERVER['PHP_SELF'],'/wap/')){
		$url=pcbase('all').'wap/';
	}else{
		$x=explode('//',$WAP_URL);
		$x=$x[0];
		$url=$x.'//'.$HTTP_HOST.'/';
	}
	$url.='?pbcode/chapter/'.$id.$qs2.'/';
	$x=xxx_url($url);
	if(strstr($x,'ajax')){
		echo $x;
	}else if(strstr($x,'ERROR')){
		require_once('../404.html');
	}else{
		$x='<script>location.href="'.$url.'";</script>';
		echo $x;
	}
}else if(strstr($qs,'pbcode')){
	$qsArr=explode('?',$qs);
	$qs=$qsArr[0];
	$qs=rtrim($qs,'/');
	$qsArr=explode('_',$qs);
	$qs=$qsArr[0];
	$qs2=$qsArr[1];
	$qs3=$qsArr[2];
	$qs2=str_replace('--','_',$qs2);
	if(strstr($WAP_URL,'/wap/') || strstr($_SERVER['PHP_SELF'],'/wap/')){
		$url=pcbase('all').'wap/';
	}else{
		$x=explode('//',$WAP_URL);
		$x=$x[0];
		$url=$x.'//'.$HTTP_HOST.'/';
	}
	if($qs3){
		$qs3.='/';
	}
	$url.='?pbcode/'.$qs.'/'.$qs2.'/'.$qs3;
	$x=xxx_url($url);
	if(strstr($x,'script')){
		echo $x;
	}else if(strstr($x,'ERROR')){
		require_once('../404.html');
	}else{
		$x='<script>location.href="'.$url.'";</script>';
		echo $x;
	}
}
?>