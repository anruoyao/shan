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
if($x=='connect'){
	require_once('../connect.html');
	exit;
}
require_once('../include/rep.fun.php');
if(strstr($qs,'category') || strstr($qs,'book')){
	if(strstr($qs,'book')){
		$qsArr=explode('?',$qs);
		$qs=$qsArr[0];
		$dir='../'.$qs;
		$file1=$dir.'/index.html';
		$file2=$dir.'/index.php';
		$file3='../include/contents_697code_com/index.php';
		if(is_file($file1)){
			$template='category-xxx.html';
			$x=filesize($file1);
			if($x>1515){
				$x=sql("select name from ay_content_sort where filename='$qs' limit 1");
				$name=$x[name];
				reptkd($file1);
				exit;
			}else if($x>0){
				require_once($file1);
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
				reptkd($file);
				exit;
			}
		}
	}
	if($id){
		$x='book';
		$qs=$id;
		$url=pcbase('all').'?pbcode/contents/'.$scode.'/'.$qs.'.html';
	}else{
		$x='category';
		if($qs2){
			$qs2='_'.$qs2;
		}
		$url=pcbase('all').'?'.$qs.$qs2.'/';
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
	$file='../include/article_pbcode_xyz/index.php';
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
		if($idx=='xid'){
			$url=pcbase('all').'?pbcode/article/'.$qs.$qs2.'/';
		}else{
			$x=sql("select scode from ay_content where id=$qs limit 1");
			$scode=$x[scode];
			$url=pcbase('all').'?pbcode/contents/'.$scode.'/'.$qs.$qs2.'.html';
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
	$x=sql("select id from ay_content where filename='$qs' limit 1");
	$id=$x[id];
	if($qs2){
		$qs2='/'.$qs2;
	}
	$url=pcbase('all').'?pbcode/chapter/'.$id.$qs2.'/';
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
	if($qs3){
		$qs3.='/';
	}
	$url=pcbase('all').'?pbcode/'.$qs.'/'.$qs2.'/';
	if(strstr($url,'/txt/') || strstr($url,'/zip/')){
		$arr=explode('/',$qs2);
		$scode=$arr[0];
		$id=$arr[1];
		$x=sql("select filename from ay_content_sort where scode=$scode limit 1");
		$filename=$x[filename];
		$x=sql("select title,subtitle from ay_content where id=$id limit 1");
		$title=$x[title];
		$subtitle=$x[subtitle];
		$txt='../txt/txt/'.$filename.'/'.$id.'.txt';
		$zip='../txt/zip/'.$filename.'/'.$id.'.zip';
		if(strstr($url,'/txt/')){
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
		}else{
			if(file_exists($zip)){
				if(!$subtitle){
					$filemtime=filemtime($zip);
					$t=time();
					$x1=$t-$filemtime;
					$x2=24*60*60;
					$x=$x1-$x2;
					if($x>0){
						$newtxt=1;
					}
				}
				if(!$newtxt){
					downtxt($zip,$title);
					exit;
				}
			}else if(file_exists($txt)){
				makezip($zip,$txt,$title,$filename);
				exit;
			}
		}
	}
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