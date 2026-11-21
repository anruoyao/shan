<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('core/start.php');
}
$xxx='';
$dd=$dsql->all("select idx from ay_member_add_update",1);
foreach($dd as $xx){
	$idx=$xx[idx];
	$xx=$dsql->one("select xid,update_time from ay_member_add_update where idx=$idx limit 1",1);
	$xid=$xx[xid];
	$update_time=$xx[update_time];
	$time=get_datetime();
	if($update_time<=$time){
		$dsql->amd("update ay_article set status=1 where xid=$xid limit 1");
		$dsql->amd("delete from ay_member_add_update where idx=$idx limit 1");
		$xxx.=$xid.',';
	}
}
$xxx=trim($xxx,',');
if(!$xxx){
	$xxx='暂无内容';
}else{
	$xxx='成功发布：'.$xxx;
}
echo $xxx;
?>