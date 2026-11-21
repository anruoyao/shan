<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
$x=$_SERVER['HTTP_USER_AGENT'];
$x=strtolower($x);
if(strstr($x,'spider') || strstr($x,'robot')){
	exit;
}
$id=str_replace(array('id=','.html'),'',$_SERVER['QUERY_STRING']);
$view=gpvar('view');
if($view){
	$id=$view;
}
if(strstr($id,'?') && strstr($id,'&')){
	$arr=explode('&',$id);
	$id=$arr[1];
	$arr=explode('?',$id);
	$id=$arr[0];
}
if(!$id || !is_numeric($id)){
	echo 'error...';
	exit;
}
if($pay_ad=='1' || $pay_ad=='2'){
	$uid=session('pboot_ucode');
	if($pay_ad=='1'){
		if($uid){
			exit;
		}
	}else{
		if($uid){
			$x=$dsql->one("select idx from ay_member_money where uid=$uid and (lx=1 or lx=2) and pay=1 limit 1",1);
			$o=$x[idx];
			if($o){
				$x=$dsql->one("select validdate from ay_member where status=1 and ucode=$uid limit 1",1);
				$validdate=$x[validdate];
				$validdate=strtotime($validdate);
				$time=get_datetime();
				$time=strtotime($time);
				if($validdate>$time){
					exit;
				}
			}
		}
	}
}
$x=$dsql->one("select adbody from ay_ad where id=$id limit 1",1,usedbname());
$adbody=$x[adbody];
if(!$view){
	$adbody=str_replace('"','\"',$adbody);
	$adbody=str_replace("\r","\\r",$adbody);
	$adbody=str_replace("\n","\\n",$adbody);
	$adbody=str_replace('ad.6','697.6',$adbody);
	$arr=explode('.',$HTTP_HOST);
	$st=$arr[0];
	$st=preg_replace("/\\d+/",'',$st);
	$adbody=str_replace('tool&','tool&st='.$st.'&',$adbody);
	$adbody="document.write(\"{$adbody}\");";
	if(strstr($HTTP_HOST,':697')){
		$x1='https://697.697code.com/';
		$x2=strstr('.',$HTTP_HOST);
		$x2='http://697'.$x2.'/';
		$adbody=str_replace($x1,$x2,$adbody);
	}else if(strstr($HTTP_HOST,'pbcode.xyz')){
		$adbody=str_replace('697.697code.com','697.pbcode.xyz',$adbody);
	}
}
echo $adbody;
?>