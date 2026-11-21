<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once(dirname(__FILE__).'/core/start.php');
}
header("Content-type:application/javascript");
$my=$_GET['my'];
$uid=session('pboot_ucode');
if(!$uid){
	$template='login_none.html';
}else{
	$x=$dsql->one("select status,username,nickname,register_time,headpic,validdate,score,autologin from ay_member where ucode=$uid limit 1",1);
	$status=$x[status];
	$username=$x[username];
	$autologin=$x[autologin];
	if(!$username || !$status || $autologin){
		$template='login_none.html';
        session('pboot_uid','');
        session('pboot_ucode','');
        session('pboot_username','');
        session('pboot_useremail','');
        session('pboot_usermobile','');
        session('pboot_gid','');
        session('pboot_gcode','');
        session('pboot_gname','');
	}else{
		$nickname=$x[nickname];
		$register_time=$x[register_time];
		$headpic=$x[headpic];
		$validdate=$x[validdate];
		$score=$x[score];
		if($validdate=='0000-00-00 00:00:00'){
			$x=time()+31536000;
			$validdate=date('Y-m-d H:i:s',$x);
			$dsql->amd("update ay_member set validdate='$validdate' where ucode=$uid limit 1");
		}
		if(!$headpic){
			$x=rand(1,100);
			$headpic='/uploads/empty/'.$x.'.gif';
			$dsql->amd("update ay_member set headpic='$headpic' where ucode=$uid limit 1");
		}
		$headpic=ico($headpic,1);
		membergid($score);
		$template='login_ok.html';
	}
}
pb_tj();
require_once(ROOT_PATH.'/template/tpl/html/'.$template);
?>
