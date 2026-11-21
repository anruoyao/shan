<?php
require_once(dirname(__FILE__).'/api.php');
$pid=$epay_config['pid'];
$key=$epay_config['key'];
$url=$order_url.$pid.'&key='.$key.'&out_trade_no='.$ding;
$h=file_get_contents($url);
$arr=json_decode($h,true);
$payok=$arr['status'];
if($payok=='1'){
	maxjie_com(1,$ding,'','','',1,'');
	global $tg_jinbi;
	if($tg_jinbi){
	    $x=$dsql->one("select uid,pay,je,jb,create_time from ay_member_money where ding='$ding' limit 1",1);
	    $uid_=$x[uid];
	    $pay=$x[pay];
	    $je=$x[je];
	    $jb=$x[jb];
	    $create_time=$x[create_time];
	    if($pay==1){
	        $hash=session('tg');
	        $arr=explode('-',$hash);
	        $tgid=$arr[0];
	        $uid=$arr[1];
	        $md5_=$arr[2];
	        if($tgid && $uid && $md5_){
	            $md5=md5($tgid.$uid.'697');
	            if($md5==$md5_){
	                $x=$dsql->one("select id from ay_tg_jinbi where ding='$ding' limit 1",1);
	                $o=$x[id];
	                if(!$o){
	                    $x=str_replace('%','',$tg_jinbi);
	                    $x=$x/100;
	                    $jinbi=ceil($jb*$x);
	                    $x1="tgid,uid,jinbi,uid_,je,ding,update_time";
	                    $x2="'$tgid','$uid','$jinbi','$uid_','$je','$ding','$create_time'";
	                    $dsql->amd("insert into ay_tg_jinbi ($x1) values ($x2)");
	                    $x=$dsql->one("select id from ay_tg_jinbi where ding='$ding' limit 1",1);
	                    $o=$x[id];
	                    if($o){
	                        $dsql->amd("update ay_member set jinbi=jinbi+$jinbi where ucode=$uid limit 1");
	                    }
	                }
	            }
	        }
	    }
	}
	$xxx=1;
}else{
	$xxx=0;
}
echo $xxx;
?>