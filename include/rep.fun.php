<?php
require_once(dirname(__FILE__).'/../base.inc.php');
$HTTP_HOST=$_SERVER['HTTP_HOST'];
function pcbase($my=null){
	global $PC_URL,$WAP_URL,$HTTP_HOST,$pc2url,$LOCK_SERVER_PORT;
	$xxx=str_replace(array('http://','https://','//'),'',$PC_URL);
	$xxx=strstr($xxx,'/');
	if(!$xxx){
		$xxx='/';
	}
	if($my=='all'){
		$x='http';
		if(is_https()){
			$x.="s";
		}
		$x.='://';
		$SERVER_PORT=$_SERVER["SERVER_PORT"];
		if($SERVER_PORT!="80"){
			if($SERVER_PORT=="443"){
				$SERVER_PORT='';
			}else{
				$SERVER_PORT=':'.$SERVER_PORT;
			}
		}else{
			$SERVER_PORT='';
		}
		if($LOCK_SERVER_PORT){
			$SERVER_PORT=':'.$LOCK_SERVER_PORT;
			$x_=explode(':',$HTTP_HOST);
			$x_=$x_[0];
			$HTTP_HOST=$x_.$SERVER_PORT;
		}
		if(!$SERVER_PORT && strstr($HTTP_HOST,'.ceodog.com')){
			$x_=explode(':',$PC_URL);
			$x_=$x_[count($x_)-1];
			$x_=trim($x_,'/');
			if($x_){
				$SERVER_PORT=':'.$x_;
				if(!strstr($HTTP_HOST,':')){
					$HTTP_HOST.=':'.$x_;
				}
			}
		}
		if($pc2url){
			$x0=array_key_exists($HTTP_HOST,$pc2url);
			if($x0){
				$HH=$HTTP_HOST;
			}else{
				$x0=in_array($HTTP_HOST,$pc2url);
				if(!$x0){
					$HH=key($pc2url);
				}else{
					$HH=array_search($HTTP_HOST,$pc2url);
				}
			}
		}else{
			$sf=$_SERVER['SCRIPT_FILENAME'];
			if(!$sf || strstr($sf,'wap/')){
				if($sf && strstr($WAP_URL,'wap/')){
					$PHP_SELF=$_SERVER["PHP_SELF"];
					if(strstr($PHP_SELF,'wap/')){
						$HH=$HTTP_HOST;
					}else{
						$x0=str_replace(array('http://','https://','//'),'',$PC_URL);
						$arr=explode('/',$x0);
						$HH=$arr[0];
					}
				}else{
					$x0=str_replace(array('http://','https://','//'),'',$PC_URL);
					$arr=explode('/',$x0);
					$HH=$arr[0];
				}
			}else{
				$HH=$HTTP_HOST;
			}
		}
		if($SERVER_PORT){
			$arr=explode(':',$HH);
			$HH=$arr[0];
		}
		$x.=$HH.$SERVER_PORT;
	}
	$xxx=$x.$xxx;
	return $xxx;
}
function reptkd($file=null,$scode=null,$id=null,$page=null,$wap=null){
	global $PC_URL,$WAP_URL,$pc2url,$HTTP_HOST;
	if(strstr($PC_URL,$HTTP_HOST)){
		$md='yes';
	}else if(strstr($WAP_URL,$HTTP_HOST)){
		$md='yes';
	}

	$md='';
	
	if($md){
		require_once($file);
	}else{
		$xxx=xxx_url($file);
		if($pc2url){
			$x01=strstr($xxx,'<title>');
			$x02=strstr($x01,'<base');
			$x00=str_replace($x02,'',$x01);
			$x00=trim($x00);
			global $template,$sitetitle,$indextitle,$sitekeywords,$sitedescription;
			$x=sql("select title,subtitle,keywords,description from ay_site where id=1 limit 1");
			$sitetitle=$x[title];
			$indextitle=$x[subtitle];
			$sitekeywords=$x[keywords];
			$sitedescription=$x[description];
			$webname00=$sitetitle;
			if(!$wap){
				$x11=head4pc($scode,$id,$page);
			}else{
				$x11=head4wap($scode,$id,$page);
			}
			$webname11=$sitetitle;
			$xxx=str_replace($x00,$x11,$xxx);
			$xxx=str_replace($webname00,$webname11,$xxx);
			if(!$wap){
				$logo=dirname(__FILE__).'/../template/tpl/image/'.$HTTP_HOST.'.png';
			}else{
				$logo=dirname(__FILE__).'/../wap/style/image/'.$HTTP_HOST.'.png';
			}
			if(file_exists($logo)){
				$xxx=str_replace('logo.png',$HTTP_HOST.'.png',$xxx);
			}
			$x02=$pc2url[ $HTTP_HOST ];
			if($x02){
				$arr=explode('//',$WAP_URL);
				$x01=$arr[1];
				$arr=explode('/',$x01);
				$x01=$arr[0];
				$xxx=str_replace($x01,$x02,$xxx);
				$pcbaseall=pcbase('all');
				$xxx=str_replace($PC_URL,$pcbaseall,$xxx);
			}
			$x02=array_search($HTTP_HOST,$pc2url);
			if($x02){
				$arr=explode('//',$PC_URL);
				$x01=$arr[1];
				$arr=explode('/',$x01);
				$x01=$arr[0];
				$xxx=str_replace($x01,$x02,$xxx);
				$arr=explode('//',$WAP_URL);
				$x01=$arr[1];
				$arr=explode('/',$x01);
				$x01=$arr[0];
				$xxx=str_replace($x01,$HTTP_HOST,$xxx);
			}
		}else{
			$pcbaseall=pcbase('all');
			$xxx=str_replace($PC_URL,$pcbaseall,$xxx);
			if(strstr($HTTP_HOST,':697')){
				$arr=explode('.',$HTTP_HOST);
				$x_=$arr[0];
				$x_='https://'.$x_.'.669977.net/';
				$xxx=str_replace($x_,$pcbaseall,$xxx);
				$x=sql("select domain from ay_site where id=1 limit 1");
				$domain=$x[domain];
				$arr=explode('.',$domain);
				$x01=$arr[0];
				$x02=strstr($HTTP_HOST,'.');
				$url='http://'.$x01.$x02.'/';
				$xxx=str_replace($pcbaseall.'files',$url.'files',$xxx);
				$xxx=str_replace($pcbaseall.'uploads',$url.'uploads',$xxx);
				$arr=explode('//',$PC_URL);
				$x=$arr[0];
				$x='https://'.$domain.'/';
				$xxx=str_replace($x.'files',$url.'files',$xxx);
				$xxx=str_replace($x.'uploads',$url.'uploads',$xxx);
			}else if(strstr($PC_URL,':697')){
				$x=sql("select domain from ay_site where id=1 limit 1");
				$domain=$x[domain];
				$x=explode('.',$domain);
				$x01=$x[0];
				$x=explode('//',$PC_URL);
				$x=$x[1];
				$x=explode('.',$x);
				$x02=$x[0];
				$x=str_replace('http:','https:',$pcbaseall);
				$rep_=str_replace($x02,$x01,$x);
				$rep1=str_replace($x02,$x01,$PC_URL);
				$rep2='https://'.$domain.'/';
				$rep3=str_replace('https:','http:',$rep_);
				$xxx=str_replace(array($rep1,$rep2,$rep3),$rep_,$xxx);
			}
		}
		echo $xxx;
	}
}
function sql($sql=null){
	if(!$sql){
		$xxx='error...';
		echo $xxx;
		exit;
	}
	$f=dirname(__FILE__).'/../config/database.php';
	$h=xxx_url($f);
	$arr=explode('=>\'',$h);
	foreach($arr as $v){
		$ii++;
		$v=explode("'",$v);
		$v=$v[0];
		$x='x'.$ii;
		$$x=$v;
	}
	$db_host=$x3;
	$db_user=$x4;
	$db_pass=$x5;
	$db_port=$x6;
	$db_name=$x7;
	$db_host.=':'.$db_port;
	$db_host=str_replace(':3306','',$db_host);
    $dsql=new mysqli($db_host,$db_user,$db_pass,$db_name);
    $x=$dsql->connect_error;
    if($x){
        $xxx='mysql can not connect...';
        echo $xxx;
        exit;
    }else{
		$x=$dsql->query($sql);
		$xxx=mysqli_fetch_array($x,1);
    }
    return $xxx;
}
function xxx_url($url){
	if(strstr($url,'http')){
		$t1=microtime(true);
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5); 
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_TIMEOUT,5);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		$xxx=curl_exec($curl);
		curl_close($curl);
		$t2=microtime(true);
		$x=$t2-$t1;
	}
	if(!$xxx && $x<5){
		ini_set('default_socket_timeout',1);
		$x=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			)
		);
		$xxx=file_get_contents($url,false,stream_context_create($x));
	}
	return $xxx;
}
function downtxt($file=null,$title=null){
	global $dsql,$useCNname,$HTTP_HOST,$TXT_AUTO,$ZIP_AUTO;
	if(strstr($file,'.zip')){
		$hz='zip';
	}else{
		$hz='txt';
	}
	if($useCNname && $title){
		$x='-['.$HTTP_HOST.']';
		$filename=$title.$x.'.'.$hz;
	}else{
		if($file){
			$filename=basename($file);
		}else{
			$filename=$title.'.'.$hz;
		}
	}
	$xxx=xxx_url($file);
	$len=strlen($xxx);
	header("Content-type: application/octet-stream");
	header("Accept-Ranges: bytes");
	header("Accept-Length: ".$len);
	header("Content-Length: ".$len);
	header("Content-Disposition:attachment;filename=".$filename);
	if(!$TXT_AUTO){
		$file=str_replace('zip','txt',$file);
		unlink($file);
	}
	if(!$ZIP_AUTO){
		$file=str_replace('/txt/txt/','/txt/zip/',$file);
		$file=str_replace('.txt','.zip',$file);
		unlink($file);
	}
	echo $xxx;
}
function makezip($zip=null,$txt=null,$title=null,$filename=null){
	$x=dirname(__FILE__).'/../txt/zip';
	mkdir($x);
	$x.='/'.$filename;
	mkdir($x);
	$z=new ZipArchive();
	if($z->open($zip,ZIPARCHIVE::OVERWRITE | ZIPARCHIVE::CREATE)!==TRUE){
		error('暂不支持ZIP',$pcbase);
		exit;
	}
	$z->addFile($txt,$title.'.txt');
	$ad=dirname(__FILE__).'/../txt/ad';
	$arr=scandir($ad);
	foreach($arr as $k=>$v){
		if($v=='.' || $v=='..'){
			continue;
		}
		$f=$ad.'/'.$v;
		$z->addFile($f,$v);
	}
	$z->close();
	if(file_exists($zip)){
		downtxt($zip,$title);
	}else{
		error('ZIP打包出错，请稍后重试',$pcbase);
	}
}
function is_https(){
    global $HTTP_HOST;
    $x=$HTTP_HOST;
    if(strstr($x,'669977.net') || strstr($x,'697code.com') || strstr($x,'pbcode.xyz')){
        return true;
    }
    if ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
        return true;
    } elseif (isset($_SERVER['REQUEST_SCHEME']) && strtolower($_SERVER['REQUEST_SCHEME']) == 'https') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_CLIENT_SCHEME']) && strtolower($_SERVER['HTTP_X_CLIENT_SCHEME']) == 'https') {
        return true;
    } else {
        return false;
    }
}
?>