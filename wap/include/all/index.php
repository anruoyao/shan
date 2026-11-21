<?php
$pbstr='template,$scode';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
		global $$v;
	}
}
$scode='all';
$template='all.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>