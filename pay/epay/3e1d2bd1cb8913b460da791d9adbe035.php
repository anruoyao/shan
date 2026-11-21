<?php
require_once(dirname(__FILE__).'/api.php');
$arr=explode('{697}',$sessarr);
$x1=$arr[0];$x2=$arr[1];$x3=$arr[2];$x4=$arr[3];$x5=$arr[4];$x6=$arr[5];
if($x4=='wechat'){
	$x4='wxpay';
}
$x=$dsql->one("select id from ay_member_money where ding='$x1' limit 1",1);
$id=$x[id];
if($id){
    $x=$dsql->one("select title from ay_content where id=$id limit 1",1);
    $x6=$x[title];
}
$notify_url=pcbase('all').'pay/epay/notify.php';
$return_url=pbcode('member','pay','ok/'.$x1,'all');
$out_trade_no=$x1;
$type=$x4;
$name=$x6;
$money=$x2;
$parameter=array(
	"pid"=>$epay_config['pid'],
	"type"=>$type,
	"appid"=>$epay_config['appid'],
    "key"=>$epay_config['key'],
	"out_trade_no"=>$out_trade_no,
	"name"=>$name,
	"money"=>$money,
	"notify_url"=>$notify_url,
	"return_url"=>$return_url
);
if(strstr($order_url,'zpayz') || strstr($order_url,'z-pay')){
    $parameter['key']='';
}
$epay=new EpayCore($epay_config);
$html_text=$epay->pagePay($parameter);
echo $html_text;
?>