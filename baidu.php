<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once(dirname(__FILE__).'/core/start.php');
}


// 每次推送数量
$num=1;


if(!$BAIDU_TOKEN){
	echo '
	未配置相关参数<br>
	<a style="color:blue;margin-right:20px" href="http://help.15fox.com:697/" target="_blank">help.697code.com</a>搜索：推送';
	exit;
}


$urls=array();
$idArr=array();

$dd=$dsql->all("select id from ay_content where ts=0 limit $num",1);
foreach($dd as $xx)
{
	$id=$xx[id];
	$idArr[]=$id;
	$url=pbcode('book',$id,'','all');
	$urls[]=$url;
	$xxx.=$url.'<br>';
}

$api='http://data.zz.baidu.com/urls?site='.$pcbaseall.'&token='.$BAIDU_TOKEN;
$ch=curl_init();
$options= array(
    CURLOPT_URL=>$api,
    CURLOPT_POST=>true,
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_POSTFIELDS=>implode("\n",$urls),
    CURLOPT_HTTPHEADER=>array('Content-Type: text/plain'),
);
curl_setopt_array($ch,$options);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
curl_setopt($ch,CURLOPT_TIMEOUT,5);
$result=curl_exec($ch);

if(strstr($result,'success') && !strstr($result,'not_same_site')){
	foreach ($idArr as $key => $value) {
		$idCur=$value;
		$dsql->amd("update ay_content set ts=1 where id='$idCur' limit 1");
	}
	$success='<font style="color:red">成功</font>';
}else{
	$success='<font style="color:red">失败</font>';
}

echo '
<p>推送结果：'.$success.'</p>
<p>'.$result.'</p>
<p>推送网址：</p>
<p>'.$xxx.'</p>';
?>