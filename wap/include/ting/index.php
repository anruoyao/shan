<?php
$pbstr='template';
$pbstr=explode(',',$pbstr);
foreach($pbstr as $v){
	if(!isset($$v)){
  global $$v;
	}
}
$template='ting.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>