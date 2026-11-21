<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','api');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
require_once('base.inc.php');
use app\admin\model\IndexModel;
$x=new IndexModel;
checksid($x);
foreach ($staticArr as $key => $value){
	$djtArr.='"'.$value.'",';
}
$djtArr=rtrim($djtArr,',');
$djtArr='var djtArr=['.$djtArr.'];';
$auto=$_GET['auto'];
if($auto){
	$auto=1;
	$class=' class="auto"';
}else{
	$auto=0;
	$class='';
}
echo '
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="renderer" content="webkit" />
<meta name="force-rendering" content="webkit" />
<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
<meta name="applicable-device" content="pc" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title>更新页面 - '.$sitetitle.'</title>
<base href="'.$pcbase.'" />
<link rel="stylesheet" type="text/css" href="make/css/common.css" />
<script type="text/javascript">
'.$djtArr.'var x2x="index";var page=1;var total=1;var auto='.$auto.';
</script>
<script type="text/javascript" src="make/js/common.js"></script>
<script type="text/javascript" src="make/js/ajax.js"></script>
<div id="conBox"'.$class.'><script type="text/javascript">conBox();</script></div>';
?>