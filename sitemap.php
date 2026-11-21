<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('core/start.php');
}
header("Content-type:text/xml");
$f='sitemap.xml';
$h=file_get_contents($f);
foreach($pc2url as $k => $v){
	$h=str_replace($k,$HTTP_HOST,$h);
	$h=str_replace($v,$HTTP_HOST,$h);
}
echo $h;
?>