<?php
$pbstr='template,author,page';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$author=$path[2];
$page=$path[3];
$author=strip_tags($author);
$author=urldecode($author);
$template='author.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>