<?php
$HTTP_HOST=$_SERVER['HTTP_HOST'];
$pcbase=pcbase();
$pcbaseall=pcbase('all');
$wapbase=wapbase();
function HiddenBadWord($word=null){
	global $HiddenBadWord,$BadWordFile,$BadWordMark;
	if(!$word || !$HiddenBadWord){
		return $word;
	}
	if(!$BadWordFile){
		$BadWordFile='badword.txt';
	}
	$f=ROOT_PATH.'/include/'.$BadWordFile;
	$h=xxx_url($f);
	if(!$h){
		return $word;
	}
	$arr=explode("\n",$h);
	foreach($arr as $k => $v){
		$v=trim($v);
		if(!$v){
			continue;
		}
		$word=str_replace($v,$BadWordMark,$word);
	}
	return $word;
}
function SBYOU_net_error($style=null,$obj=null){
	if($style=='1'){
		echo '<center>'.$obj.'</center>';
		exit;
	}
	if($style=='2'){
		header('Location:'.$obj);
		exit;
	}
}
function xxx_DES($description=null){
	$description=str_replace('&nbsp;','',$description);
	$description=strip_tags($description);
	return $description;
}
function srcBlank(){
	$xxx='template/tpl/image/blank.gif';
	return $xxx;
}
function pbcode($my=null,$id=null,$page=null,$bs=null,$ov=null,$ob=null,$pw=null,$dt=null){
	global $dsql,$AUTO_STATIC,$pcbase,$pcbaseall,$wapbase,$HTTP_HOST,$USE_MINE;
	if($bs=='all'){
		$baseurl='all';
		$bs='';
	}else if($bs=='no'){
		$baseurl='no';
		$bs='';
	}
	if($AUTO_STATIC=='0' || $AUTO_STATIC=='3' || $dt){
		if($my=='category' && $id){
			if($AUTO_STATIC=='0' || $dt){
				if($page<=1){
					$x=$id;
					$url=homeurl($x,'','','','no');
				}else{
					$x=$id.'_'.$page;
					$url=homeurl($x,'','','','no');
				}
			}else{
				if($page<=1){
					$x=$dsql->one("select filename from ay_content_sort where scode=$id limit 1",1);
					$filename=$x['filename'];
					$url=$filename.'/';
				}else{
					$x=$id.'_'.$page;
					$url=homeurl($x,'','','','no');
				}
			}
		}else if(($my=='content' || $my=='book') && $id){
			$x=explode('_',$id);
			$x=$x[0];
			$x=$dsql->one("select scode from ay_content where id=$x limit 1",1);
			$scode=$x['scode'];
			if($page>1){
				$x='_'.$page;
			}else{
				$x='';
			}
			$url='?pbcode/contents/'.$scode.'/'.$id.$x.'.html';
		}else if($my=='member' || $my=='comment'){
			if($page && ($page>1 || !is_numeric($page) || strstr($id,'pay') || strstr($id,'del'))){
				$page='/'.$page;
			}else{
				$page='';
			}
			$x=$my.'/'.$id.$page;
			$url=homeurl($x,'','','','no');
		}else{
			if($id=='p1' && $page<2){
				$id='';
			}
			($id || $id==='0')?$idx='/'.$id:'';
			$page>1?$pagex='/'.$page:'';
			$bs?$bsx='/'.$bs:'';
			$ov?$ovx='/'.$ov:'';
			$ob?$obx='/'.$ob:'';
			$x='pbcode/'.$my.$idx.$pagex.$bsx.$ovx.$obx;
			$url=homeurl($x,'','','','no');
		}
	}else if($AUTO_STATIC=='1'){
		if($my=='category' && $id){
			if($page<=1){
				$x=$dsql->one("select filename from ay_content_sort where scode=$id limit 1",1);
				$filename=$x['filename'];
				$url=$filename.'/';
			}else{
				$x=$id.'_'.$page;
				$url=homeurl($x,'','','','no');
			}
		}else if($my=='content' && $id){
			$x=$dsql->one("select scode from ay_content where id=$id limit 1",1);
			$scode=$x['scode'];
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x['filename'];
			$url=$filename.'/'.$id.'.html';
		}else if(($my=='book' || $my=='chapter') && $id){
			$x=$dsql->one("select scode from ay_content where id=$id limit 1",1);
			$scode=$x['scode'];
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x['filename'];
			if($my=='chapter'){
				$x='1.html';
			}else{
				$x='';
			}
			$url=$filename.'/'.$id.'/'.$x;
		}else if($my=='article' && $id){
			$x=$dsql->one("select id from ay_article where xid=$id limit 1",1);
			$idcur=$x['id'];
			$x=$dsql->one("select scode from ay_content where id=$idcur limit 1",1);
			$scode2=$x['scode'];
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode2 limit 1",1);
			$filename=$x['filename'];
			$url=$filename.'/'.$idcur.'/'.$id.'.html';
		}else if($my=='member' || $my=='comment'){
			if($page && ($page>1 || !is_numeric($page) || strstr($id,'pay') || strstr($id,'del'))){
				$page='/'.$page;
			}else{
				$page='';
			}
			$x=$my.'/'.$id.$page;
			$url=homeurl($x,'','','','no');
		}else{
			if($id=='p1' && $page<2){
				$id='';
			}
			($id || $id==='0')?$idx='/'.$id:'';
			$page>1?$pagex='/'.$page:'';
			$bs?$bsx='/'.$bs:'';
			$ov?$ovx='/'.$ov:'';
			$ob?$obx='/'.$ob:'';
			$x='pbcode/'.$my.$idx.$pagex.$bsx.$ovx.$obx;
			$url=homeurl($x,'','','','no');
		}
	}else if($AUTO_STATIC=='2' || $AUTO_STATIC=='4'){
		if($my=='category' && $id){
			$x=$dsql->one("select filename from ay_content_sort where scode=$id limit 1",1);
			$filename=$x['filename'];
			if($page<=1){
				$url=$filename.'.html';
			}else{
				$url=$filename.'/'.$id.'-'.$page.'.html';
			}
		}else if($my=='content' && $id){
			$x=explode('_',$id);
			$x=$x[0];
			$x=$dsql->one("select scode from ay_content where id=$x limit 1",1);
			$scode=$x['scode'];
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x['filename'];
			$url=$filename.'/'.$id.'.html';
		}else if(($my=='book' || $my=='chapter') && $id){
			$x=$dsql->one("select filename from ay_content where id=$id limit 1",1);
			$filename=$x['filename'];
			if($my=='chapter'){
				if($page<=1){
					$x='1.html';
				}else{
					$x='1_'.$page.'.html';
				}
			}else{
				$x='';
			}
			$url=$filename.'/'.$x;
		}else if($my=='article' && $id){
			$x=explode('_',$id);
			$x=$x[0];
			$x=$dsql->one("select id from ay_article where xid=$x limit 1",1);
			$idcur=$x['id'];
			$x=$dsql->one("select filename from ay_content where id=$idcur limit 1",1);
			$filename=$x['filename'];
			$url=$filename.'/'.$id.'.html';
		}else if($my=='member' || $my=='comment'){
			if($page && ($page>1 || !is_numeric($page) || strstr($id,'pay') || strstr($id,'del'))){
				$page='/'.$page;
			}else{
				$page='';
			}
			$x=$my.'/'.$id.$page;
			$url=homeurl($x,'','','','no');
		}else{
			if($id=='p1' && $page<2){
				$id='';
			}
			($id || $id==='0')?$idx='/'.$id:'';
			$page>1?$pagex='/'.$page:'';
			$bs?$bsx='/'.$bs:'';
			$ov?$ovx='/'.$ov:'';
			$ob?$obx='/'.$ob:'';
			$x=$my.$idx.$pagex.$bsx.$ovx.$obx;
			$url=homeurl($x,'','','','no');
			$url=ltrim($url,'?');
		}
	}
	if($url){
		$uri=$_SERVER["REQUEST_URI"];
		$x1=strstr($pcbaseall,'//');
		$x2='//'.$HTTP_HOST.$pcbase;
		if($baseurl=='all' && !$pw){
			$x1=$pcbaseall;
			$x2=$pcbase;
		}else if($pw=='wap' || ($x1!=$x2 && $wapbase=='/') || strstr($uri,'/wap')){
			$x1=wapbase('all');
			$x2=$wapbase;
		}else{
			$x1=$pcbaseall;
			$x2=$pcbase;
		}
		if($baseurl=='all'){
			$url=$x1.$url;
		}else if($baseurl=='no'){
			$url=$url;
		}else{
			$url=$x2.$url;
		}
		if($my=='home'){
			if($id=='all'){
				$url=$x1;
			}else{
				$url=$x2;
			}
		}
	}else{
		$url=$pcbaseall;
	}
	$url=str_replace('....','/',$url);
	$url=str_replace('..','/',$url);
	$url=str_replace('pbcode','pbcode/',$url);
	$url=str_replace('pbcode//','pbcode/',$url);
	$url=str_replace('pbcode/.xyz','pbcode.xyz',$url);
	if($my!='book'){
		$url=str_replace($my,$my.'/',$url);
		$url=str_replace($my.'//',$my.'/',$url);
		$url=str_replace('pbcode/content/s/','pbcode/contents/',$url);
	}
	if($USE_MINE && ($my=='txt' || $my=='zip')){
		$arr=explode('/'.$my.'/',$url);
		$x=$arr[1];
		$arr=explode('//',$pcbaseall);
		$x01=$arr[0];
		$x02=strstr($HTTP_HOST,'.');
		$url=$x01.'//book1'.$x02.'/'.$my.'/'.$x;
	}
	$x=substr($url,-1,1);
	if($x!='/' && $x!='l'){
		$url.='/';
	}
	$url=str_replace('/_','/',$url);
	$url=reseturl($my,$url,$id,$page,$pw,$dt);
	return $url;
}
function reimg($img=null,$w=null,$h=null,$c=1,$img2=null){
	if(!$img){
		return false;
	}else if(!strstr($img,':')){
		$img=str_replace(ROOT_PATH,'',$img);
		$img=ltrim($img,'/');
		$img=ROOT_PATH.'/'.$img;
	}
	if(!$img2){
		$img2=$img;
	}else{
		if(!strstr($img2,':')){
			$img2=str_replace(ROOT_PATH,'',$img2);
			$img2=ltrim($img2,'/');
			$img2=ROOT_PATH.'/'.$img2;
		}
	}
	if($c==1){
		resize_img($img,$img2,$w,$h);
		cut_img($img2,$img2,$w,$h);
	}else{
		resize_img($img,$img2,$w,$h);
	}
}
function gpvar($name=null,$strip=1,$escape=1){
	if(isset($_POST[$name])){
		$xxx=$_POST[$name];
	} else{
		$xxx=$_GET[$name];
	}
	if($strip){
		$xxx=strip_tags($xxx);
	}
	if($escape){
		$xxx=escape_string($xxx);
	}
	$xxx=trim($xxx);
	$xxx=preg_replace_r('/(x3c)|(x3e)/','',$xxx);
	$xxx=preg_replace_r('/pboot:if/i','pboot@if',$xxx);
	$xxx=preg_replace_r('/pboot:sql/i','pboot@sql',$xxx);
	$xxx=preg_replace_r('/GET\[/i','GET@[',$xxx);
	$xxx=preg_replace_r('/POST\[/i','POST@[',$xxx);
	return $xxx;
}
function pbstring($id=null){
	if($id==1){
		$xxx='配置文件初始化失败，请重新Install安装';
	}else{
		$xxx='未获取pb官方授权码，详情：<br><a href="//help.697code.com/" target="_blank" style="color:blue;text-decoration:underline">help.697code.com</a>';
	}
	return $xxx;
}
function htbb(){
	global $pcbase;
	$x=ROOT_PATH.'/data/version.txt';
	$x=xxx_url($x);
	$xxx='
	<blockquote class="layui-elem-quote bb" id="bb">
		<div class="bbm">
			当前版本：
			<span class="f20">'.$x.'</span>
		</div>
		<div class="bbm">
			最新版本：
			<span id="htup"></span>
			<a rel="nofollow" class="bn" href="//update.697code.com/" target="_blank">点击查看</a>
		</div>
		<div class="opcl" title="展开/关闭" onclick="opcl()"><img src="apps/admin/view/default/css/opcl.png"></div>
		<div class="bbm p5">
			<p>长期更新升级，自已下载“<font>升级包</font>”，也可找客服免费升级</p>
			<p>源码盘：<a rel="nofollow" class="bn" href="//pan.697code.com/" target="_blank">pan.697code.com</a></p>
			<p>如何判断是新版源码，还是倒卖的旧源码？</p>
			<p><span>如果您没有拿到源码盘的账号密码，那么，100%是二贩子倒卖的旧源码！！！</span></p>
			<p>最新版源码，可设置：付费才能阅读、会员才能阅读、会员免广告、看广告免费阅读、看广告奖励金币、分享链接奖励金币、多语言版本</p>
			<p>pc+wap+app，自动采集，批量导入文档，包安装，包做app，源码所有的问题都免费处理</p>
			<p>
				官网：<a rel="nofollow" class="bn" href="//697code.com/" target="_blank">669977.net</a>
				&nbsp;&nbsp;
				淘宝：<a rel="nofollow" class="bn" href="//669977.taobao.com/" target="_blank">669977.taobao.com</a>
				&nbsp;&nbsp;
				QQ/微信：1981255858
			</p>
		</div>
		<div class="ln20 p5">
			禁止任何个人或组织将本站程序用于任何违法违规途径！尤其是黄赌毒恐怖暴力等违法行为！~切记~切记~
		</div>
	</blockquote>
	<script>var url="'.$pcbase.'?my=htup";</script>';
	return $xxx;
}
function unzip($f=null){
	$arr=explode('/',$f);
	$x=$arr[count($arr)-1];
	$z=new ZipArchive;
	if($z->open($f)===TRUE){
		$xxx=str_replace('/'.$x,'',$f);
		$z->extractTo($xxx);
		$z->close();
	}else{
		$xxx='';
	}
	return $xxx;
}
function ico($img=null,$all=null,$id=null){
	global $dsql,$pcbase,$pcbaseall,$RAND_LITPIC,$USE_MINE,$CDN_IMG_URL;
	if(!$all){
		$xbase=$pcbase;
	}else{
		$xbase=$pcbaseall;
	}
	$img=ltrim($img,'/');
	if(!$img){
		if($RAND_LITPIC){
			$rand=rand(1,50);
			$xxx='uploads/empty/'.$rand.'.gif';
			if($id){
				$x='/'.$xxx;
				$dsql->amd("update ay_content set ico='$x' where id=$id limit 1",usedbname());
			}
			$xxx=$xbase.$xxx;
		}else{
			$xxx=$xbase.'uploads/empty.jpg';
		}
	}else{
		if(strstr($img,'//')){
			$xxx=$img;
		}else{
			if($USE_MINE || $CDN_IMG_URL){
				$USE_URL=USE_URL(1);
				$xxx=$USE_URL.$img;
			}else{
				$xxx=$xbase.$img;
			}
		}
	}
	$xxx=preg_replace('/\/lang\/.*?\//','/',$xxx);
	return $xxx;
}
function login($id=null,$my=null,$nickname=null,$headpic=null,$hash=null,$username=null){
	global $dsql,$HTTP_HOST;
	if(!$my){
		return false;
	}
	$username=strip_tags($username);
	$username=str_replace(array("'",'"','|',' ','=','-','<','>','.','!','/','#','@','*','(',')','+'),'',$username);
	$username=trim($username);
	$nickname=strip_tags($nickname);
	$nickname=str_replace(array("'",'"','|',' ','=','-','<','>','.','!','/','#','@','*','(',')','+'),'',$nickname);
	$nickname=trim($nickname);
	if($id){
		$f=ROOT_PATH.'/../'.$id.'.669977.net/config/database.php';
		$h=xxx_url($f);
		$arr=explode('\'dbname\'=>\'',$h);
		$x=$arr[1];
		$arr=explode("'",$x);
		$dbname=$arr[0];
		if(!$dbname){
			$dbname=$id.'_669977_net';
		}
	}else{
		$dbname='';
	}
	$time=get_datetime();
	$time2=get_date();
	$password=md5(md5($username));
	if(!$headpic || $hash){
		if($hash!=$password){
			return false;
		}
	}
	$x=$dsql->one("select ucode from ay_member where username='$username' limit 1",1,$dbname);
	$ucode=$x['ucode'];
	if($ucode){
		if($id){
			$xxx='loginNow';
		}else{
			$x=$dsql->one("select * from ay_member where ucode=$ucode limit 1",1);
			$pboot_id=$x['id'];
			$pboot_ucode=$x['ucode'];
			$pboot_username=$x['username'];
			$pboot_useremail=$x['seremail'];
			$pboot_usermobile=$x['usermobile'];
			$pboot_gid=$x['gid'];
			$pboot_gcode=$x['gcode'];
			$x=$dsql->one("select gname from ay_member_group where gcode='$pboot_gid' limit 1",1);
			$pboot_gname=$x['gname'];
			session('pboot_uid',$pboot_id);
			session('pboot_ucode',$pboot_ucode);
			session('pboot_username',$pboot_username);
			session('pboot_useremail',$pboot_useremail);
			session('pboot_usermobile',$pboot_usermobile);
			session('pboot_gid',$pboot_gid);
			session('pboot_gcode',$pboot_gcode);
			session('pboot_gname',$pboot_gname);
			$xxx='ok';
			$dsql->amd("update ay_member set last_login_time='$time2',nickname='$nickname' where ucode=$ucode limit 1");
		}
	}else{
		if(!$headpic || strstr($headpic,'sinaimg.cn')){
			$headpic='/uploads/empty/'.rand(1,100).'.gif';
		}
		$x=$dsql->one("select ucode from ay_member order by id desc limit 1",1,$dbname);
		$x=$x[ucode];
		$ucode=$x+1;
		$x1="ucode,username,nickname,password,headpic,status,activation,gid,register_time,last_login_time";
		$x2="'$ucode','$username','$nickname','$password','$headpic',1,1,1,'$time','$time2'";
		$ok=$dsql->amd("insert into ay_member ($x1) values ($x2)",$dbname);
		if($ok){
			if($id){
				$xxx='loginNow';
			}else{
				$x=$dsql->one("select * from ay_member where ucode=$ucode limit 1",1);
				$pboot_id=$x['id'];
				$pboot_ucode=$x['ucode'];
				$pboot_username=$x['username'];
				$pboot_useremail=$x['seremail'];
				$pboot_usermobile=$x['usermobile'];
				$pboot_gid=$x['gid'];
				$pboot_gcode=$x['gcode'];
				$x=$dsql->one("select gname from ay_member_group where gcode='$pboot_gid' limit 1",1);
				$pboot_gname=$x['gname'];
				session('pboot_uid',$pboot_id);
				session('pboot_ucode',$pboot_ucode);
				session('pboot_username',$pboot_username);
				session('pboot_useremail',$pboot_useremail);
				session('pboot_usermobile',$pboot_usermobile);
				session('pboot_gid',$pboot_gid);
				session('pboot_gcode',$pboot_gcode);
				session('pboot_gname',$pboot_gname);
				$xxx='ok';
				$dsql->amd("update ay_member set last_login_time='$time2' where ucode=$ucode limit 1");
			}
		}
	}
	$d=ROOT_PATH.'/../669977.net/login/session';
	if(is_dir($d)){
		if(isset($_SERVER["HTTP_USER_AGENT"])){
			$ip=strtolower($_SERVER["HTTP_USER_AGENT"]);
		} else{
			$ip='669977.net';
		}
		$md5=md5($ip);
		$f=$d.'/'.$md5;
		if(file_exists($f)){
			$h=file_get_contents($f);
			$arr=explode('{x}',$h);
			$h=$arr[1];
		}
		if(strstr($h,$HTTP_HOST) && !strstr($h,'.'.$HTTP_HOST)){
			unlink($f);
			$h='{x}'.$h;
		}
		$xxx.='{x}'.$h.'{x}'.$password;
	}
	return $xxx;
}
function weibo($id=null,$code=null,$wb=null){
	global $pcbaseall,$Appkey,$Appsecret,$cfg_dbpassword;
	if(!$Appkey || !$Appsecret){
		$xxx='Fail';
	}else{
		if($id || strstr($cfg_dbpassword,'3M8A')){
			$url='http://6'.''.'6'.''.'9'.''.'9'.''.'7'.''.'7.n'.'et/';
		}else{
			$url=$pcbaseall;
		}
		$url=urlencode($url.'login/index.php?weibo.html');
		$url='https://api.weibo.com/oauth2/access_token?client_id='.$Appkey.'&client_secret='.$Appsecret.'&grant_type=authorization_code&redirect_uri='.$url.'&code='.$code;
		$options=array(
			'http'=>array(
			  'method'=>'POST',
			  'header'=>'Content-type:application/x-www-form-urlencoded',
			  'timeout'=>30,
			)
		);
		$context=stream_context_create($options);
		$json=file_get_contents($url,false,$context);
		if(!$json){
			$xxx='Fail';
		}else{
			$json=json_decode($json,true);
			$access_token=$json['access_token'];
			$ucode=$json['u'.'i'.'d'];
			if(!$access_token || !$ucode){
				$xxx='Fail';
			}else{
				$url='https://api.weibo.com/2/users/show.json?access_token='.$access_token.'&u'.'i'.'d='.$ucode;
				$json=xxx_url($url);
				if(!$json){
					$xxx='Fail';
				}else{
					$json=json_decode($json,true);
					$my='weibo';
					$nickname=$json['screen_name'];
					$headpic=$json['avatar_large'];
					$hash='';
					$username=$ucode;
					if(!$nickname){
						$xxx='Fail';
					}else{
						$xxx=$id.'-'.$my.'-'.$nickname.'-'.$headpic.'-'.$hash.'-'.$username.'-'.$wb;
					}
				}
			}
		}
	}
	return $xxx;
}
function wblogin(){
	$d=ROOT_PATH.'/../669977.net/login/session';
	if(is_dir($d)){
		if(isset($_SERVER["HTTP_USER_AGENT"])){
			$ip=strtolower($_SERVER["HTTP_USER_AGENT"]);
		} else{
			$ip='669977.net';
		}
		$md5=md5($ip);
		$f=$d.'/'.$md5;
		if(is_file($f)){
			$h=xxx_url($f);
			$arr=explode('{x}',$h);
			$h=$arr[0];
			if($h){
				$xxx=$h.',wb';
			}
		}
	}
	return $xxx;
}
function weixin(){
	global $dsql,$pcbaseall,$Appconfig;
	$Appconfig[redirect_uri]=$pcbaseall.'login/index.php?weixin.html';
	$is_wechat = strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
	if(isset($_GET['code'])){
		$code=gpvar('code');
		if($is_wechat){
			$appid = $Appconfig['mp_appid'];
			$secret = $Appconfig['mp_appsecret'];
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		} else{
			$appid = $Appconfig['open_appid'];
			$secret = $Appconfig['open_appsecret'];
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		}
		$data = json_decode(file_get_contents($url), true);
		$errcode=$data['errcode'];
		if($errcode){
			$xxx='无法获取access_token，错误代码：'.$errcode;
			$xxx='errcode{697}'.$xxx;
		}else{
			$access_token = $data['access_token'];
			$openid = $data['openid'];
			$unionid = !empty($data['unionid']) ? $data['unionid'] : '';
			// 获取用户信息
			$user_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";
			$user = json_decode(file_get_contents($user_url), true);
			// 用户信息
			$username=$user[unionid];
			$nickname=$user[nickname];
			$headpic=$user[headimgurl];
			$hash=md5(md5($username));
			if(!$username){
				$xxx='获取用户信息失败';
				$xxx='errcode{697}'.$xxx;
			}else{
				$xxx=$username.'{697}'.$nickname.'{697}'.$headpic.'{697}'.$hash;
				$xxx='success{697}'.$xxx;
			}
		}
	}else{
		if($Appconfig['open_appid']=='669977.net'){
			$ysz='1';
		}else{
			$ysz='';
		}
		if($is_wechat && !$ysz){
			$auth_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$Appconfig['mp_appid']}&redirect_uri=" . urlencode($Appconfig['redirect_uri']) . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
			header("Location: $auth_url");
		}else{
			$xxx='
			<style>
				body{font-family: "Microsoft Yahei"; text-align: center; padding-top: 60px;}
				#login_container{width: 300px; margin: 0 auto;}
				.tip{margin-top: 20px; color: #555;}
			</style>
			<h2>使用微信扫描二维码登录</h2>
			<div id="login_container"></div>
			<div class="tip">请使用微信“扫一扫”完成登录</div>
			<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
			<script>
			var obj = new WxLogin({
				id: "login_container",
				appid: "'.$Appconfig['open_appid'].'",
				scope: "snsapi_login",
				redirect_uri: encodeURIComponent("'.$Appconfig['redirect_uri'].'"),
				state: "STATE",
				style: "black",
				href: ""
			});
			</script>';
			if($ysz){
				$xxx=str_replace('完成登录','完成登录<br><span style="color:red">演示站，暂未开放微信登录<br>找客服获取相关教程</span>',$xxx);
				$xxx.='<style>#login_container{background:#f9f9f9;border-radius:5px}</style>';
			}
			$xxx='qrcode{697}'.$xxx;
		}
	}
	return $xxx;
}
function cleanstr($x=null,$eol=null){
	$xxx=strip_tags($x);
	if($eol){
		$xxx=str_replace(array('	',' ','&nbsp;'),'',$xxx);
	}else{
		$xxx=str_replace(array(PHP_EOL,'	',' ','&nbsp;'),'',$xxx);
	}
	return $xxx;
}
function content4ting($content=null,$ting=1){
	$content=str_replace('src="uploads','src="'.$USE_URL.'uploads',$content);
	$content=str_replace('src=\'uploads','src=\''.$USE_URL.'uploads',$content);
	$content=str_replace('<img','697img',$content);
	$content=str_replace('<a','{a}',$content);
	$content=str_replace('</a>','{/a}',$content);
	if($ting){
		$USE_URL=USE_URL();
		$content=str_replace(array('<p>','<P>','<br />','<br>','<br/>','<BR />','<BR>','<BR/>','<div>','</div>','<DIV>','</DIV>'),PHP_EOL,$content);
		$content=strip_tags($content);
		$content=str_replace(array(PHP_EOL,"\t","\n","\r"),'</p><p>',$content);
		$content='<p>'.$content.'</p>';
		$content=str_replace(array('<p></p>','&nbsp;','&nbsp','&#160;','&#160','<p></p>','　'),'',$content);
		$arr=explode('<p>',$content);
		$content='';
		foreach($arr as $xx){
			$xx=trim($xx);
			$xx=str_replace('　','',$xx);
			if($xx){
				$content.='<p>'.$xx;
			}
		}
		$content='<div id="bodybox">'.$content.'</div>';
	}
	$content=str_replace('697img','<img onerror="this.src=&#39;uploads/empty.jpg&#39;;this.style.display=&#39;none&#39;"',$content);
	$content=str_replace('{a}','<a',$content);
	$content=str_replace('{/a}','</a>',$content);
	return $content;
}
function usedbname(){
	global $PC_URL,$USE_MINE;
	if($USE_MINE){
		$arr=explode('//',$PC_URL);
		$x=$arr[1];
		$arr=explode('/',$x);
		$x=$arr[0];
		$x=str_replace(array('pbcode.xyz','15fox.com:697','ceodog.com:697'),'669977.net',$x);
		$xxx=str_replace('.','_',$x);
	}
	return $xxx;
}
function USE_URL($x=null){
	global $dsql,$pcbaseall,$pcbase,$USE_MINE,$PC_URL,$CDN_IMG_URL,$CDN_DOMAIN,$HTTP_HOST,$cfg_dbpassword;
	if($CDN_IMG_URL){
		if($CDN_DOMAIN){
			if(strstr($HTTP_HOST,$CDN_DOMAIN)){
				$xxx=$CDN_IMG_URL;
			}
		}else{
			$xxx=$CDN_IMG_URL;
		}
	}
	if(!$xxx){
		if($USE_MINE==1){
			$arr=explode(':',$pcbaseall);
			$x00=$arr[0];
			$x=$dsql->one("select domain from ay_site where id=1 limit 1",1);
			$domain=$x[domain];
			if(strstr($cfg_dbpassword,'3M8A')){
				$x01=strstr($domain,'.');
				$x02=strstr($HTTP_HOST,'.');
				$domain=str_replace($x01,$x02,$domain);
			}
			$xxx=$x00.'://'.$domain.'/';
		}else{
			if($x){
				$xxx=$PC_URL;
			}else{
				$xxx=$pcbase;
			}
		}
	}
	return $xxx;
}
function USE_DIR(){
	global $USE_MINE,$USE_DIR;
	if($USE_MINE==1){
		$dirHere=$USE_DIR;
	}else{
		$dirHere=ROOT_PATH.'/';
	}
	return($dirHere);
}
function downtxt($file=null,$title=null,$txt=null){
	global $dsql,$useCNname,$HTTP_HOST,$TXT_AUTO,$ZIP_AUTO;
	if(strstr($file,'.zip')){
		$hz='zip';
	}else{
		$hz='txt';
	}
	if($useCNname && $title){
		if(!$txt){
			$x='-['.$HTTP_HOST.']';
		}else{
			$x='';
		}
		$filename=$title.$x.'.'.$hz;
	}else{
		if($file){
			$filename=basename($file);
		}else{
			$filename=$title.'.'.$hz;
		}
	}
	if(!$txt){
		$xxx=xxx_url($file);
	}else{
		$xxx='
		#{pbcode.xyz}
		适用范围：697原创源码
		源码官网：697code.com
		QQ/微信：1981255858
		免责声明：任何使用此套程序的个人或组织，禁止发布任何违法违规内容，否则，产生的一切法律后果自行承担
		#{/pbcode.xyz}';
		$xxx=trim($xxx);
		$xxx=str_replace(array(' ','	'),'',$xxx);
		$txt=decode_string($txt);
		$xxx.="\n\n".$txt;
	}
	$len=strlen($xxx);
	header("Content-type: application/octet-stream");
	header("Accept-Ranges: bytes");
	header("Accept-Length: ".$len);
	header("Content-Length: ".$len);
	header("Content-Disposition:attachment;filename=".$filename);
	if(!$TXT_AUTO && !$txt){
		$file=str_replace('zip','txt',$file);
		unlink($file);
	}
	if(!$ZIP_AUTO && !$txt){
		$file=str_replace('/txt/txt/','/txt/zip/',$file);
		$file=str_replace('.txt','.zip',$file);
		unlink($file);
	}
	echo $xxx;
}
function inrule($x=null){
	global $dsql;
	$rule=post('rule');
	if(!strstr($rule,'{'.'p'.'b'.'c'.''.'o'.'de'.'.x'.'y'.'z'.''.'}') || !strstr($rule,'{'.'6'.''.'9'.''.'7}') || $x!=$rule){
		error('规则格式有误...请完整复制！',-1);
	}
	$arr=$dsql->all("show fields from ay_697_cj",1);
	foreach($arr as $k => $v){
		$x=$v[Field];
		if($x=='cid'){
			continue;
		}
		$dbarr[]=$x;
	}
	$x='#{/pbcode.xyz}';
	if(strstr($rule,$x)){
		$rule=strstr($rule,$x);
		$rule=str_replace($x,'',$rule);
	}
	$rule=trim($rule);
	$rule=str_replace('{/pbcode.xyz}','',$rule);
	$rule=explode('{pbcode.xyz}',$rule);
	foreach($rule as $k=>$v){
		$v=trim($v);
		if(!strstr($v,'{697}')){
			continue;
		}
		$arr2=explode('{697}',$v);
		$ii=0;
		$x01='';
		$x02='';
		foreach($dbarr as $k2 => $v2){
			$ii++;
			$k3=$v2;
			$v3=$arr2[$ii-1];
			$x01.="$k3,";
			$x02.="'$v3',";
		}
		$x01=rtrim($x01,',');
		$x02=rtrim($x02,',');
		$o=$dsql->amd("insert into ay_697_cj ($x01) values ($x02)");
		if(!$o){
			break;
		}
	}
	if($o){
		$x='?p=/cj/index';
		success('导入成功！',$x);
	}else{
		error('导入失败...',-1);
	}
}
function checksid($x=null,$no=null){
	$sid=session('sid');
	if(!$sid){
		$my_u=gpvar('my_u');
		$my_p=gpvar('my_p');
		$my_p=encrypt_string($my_p);
		$sql=array(
			'username'=>$my_u,
			'password'=>$my_p
		);
		if(!!$x=$x->login($sql)){
			session_regenerate_id(true);
			session('sid',encrypt_string(session_id().$x->id));
			session('M','admin');
			session('id',$x->id);
			session('ucode',$x->ucode);
			session('username',$x->username);
			session('realname',$x->realname);
			session('pwsecurity',true);
			session('acodes',$x->acodes);
			session('acode',$x->acodes[0]);
			session('rcodes',$x->rcodes);
			session('levels',$x->levels);
			session('menu_tree',$x->menus);
			session('area_map',$x->area_map);
			session('area_tree',$x->area_tree);
		}else{
			if(!$no){
				error('管理员账号未处于登录状态');
			}
		}
	}
}
function xxx_url($url=null,$t=null){
	if(!$t){
		$t=10;
	}
	if(strstr($url,'http')){
		$t1=microtime(true);
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$t);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_TIMEOUT,$t);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		$xxx=curl_exec($curl);
		curl_close($curl);
		$t2=microtime(true);
		$x=$t2-$t1;
	}
	if(!$xxx && $x<$t){
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
function pcbase($my=null){
	global $PC_URL,$WAP_URL,$HTTP_HOST,$pc2url,$RP_URL,$LOCK_SERVER_PORT;
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
			if(strstr($sf,'wap/')){
				if(strstr($WAP_URL,'wap/')){
					$HH=$HTTP_HOST;
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
	if($RP_URL && $my=='all'){
		$xxx=$PC_URL;
	}
	global $langArr;
	if($langArr){
		$lang=session('lang');
		if(defined('LANG')){
			$lang=LANG;
		}
		if($lang && $lang!='cn'){
			$xxx.='lang/'.$lang.'/';
		}
	}
	return $xxx;
}
function wapbase($my=null){
	global $WAP_URL,$HTTP_HOST,$pc2url,$RP_URL;
	$xxx=str_replace(array('http://','https://','//'),'',$WAP_URL);
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
		if($pc2url){
			$x0=in_array($HTTP_HOST,$pc2url);
			if($x0){
				$HH=$HTTP_HOST;
			}else{
				$x0=array_key_exists($HTTP_HOST,$pc2url);
				if($x0){
					$HH=$pc2url[$HTTP_HOST];
				}else{
					$HH=current($pc2url);
				}
			}
		}else{
			if(strstr($WAP_URL,'wap/')){
				$HH=$HTTP_HOST;
			}else{
				$x0=str_replace(array('http://','https://','//'),'',$WAP_URL);
				$arr=explode('/',$x0);
				$HH=$arr[0];
			}
		}
		if($SERVER_PORT){
			$arr=explode(':',$HH);
			$HH=$arr[0];
		}
		$x.=$HH.$SERVER_PORT;
	}
	$xxx=$x.$xxx;
	if($RP_URL && $my=='all'){
		$xxx=$WAP_URL;
	}
	global $langArr;
	if($langArr){
		$lang=session('lang');
		if(defined('LANG')){
			$lang=LANG;
		}
		if($lang && $lang!='cn'){
			$xxx.='lang/'.$lang.'/';
		}
	}
	return $xxx;
}
function checkuid($mcode=null,$id=null,$xid=null,$content=null,$wap=null){
	global $dsql,$AUTO_STATIC,$pay_ready,$vip_all;
	if(!$mcode || !$id || strstr($content,'url2disk')){
		return $content;
	}
	if(is_numeric($mcode) && $AUTO_STATIC=='1'){
		// 
	}else{
		$x=$dsql->one("select gid,gtype,jinbi,jifen from ay_content where id=$id limit 1",1);
		$gid=$x[gid];
		$gtype=$x[gtype];
		$jinbi=$x[jinbi];
		$jifen=$x[jifen];
		if($vip_all){
			$arr=explode('-',$vip_all);
			$gid_=$arr[0];
			$jinbi_=$arr[1];
			$jifen_=$arr[2];
			if($gid_){
				$gid=$gid_;
				if($jinbi_=='0' || $jinbi_>0){
					$jinbi=$jinbi_;
				}
				if($jifen_=='0' || $jifen_>0){
					$jifen=$jifen_;
				}
			}
		}
		if($gid){
			$tip=checklevel($gtype,$gid,$id,$xid,$wap,$jinbi,$jifen);
			if($tip && strstr($pay_ready,''.'6'.'97'.'co'.''.'de'.'.c'.''.'om')){
				$arr=explode('{697}',$tip);
				$my=$arr[0];
				$my=trim($my);
				$tip=$arr[1];
				$tip=trim($tip);
				if($my=='pay' && !$jinbi && !$jifen){
					return $content;
				}
				if($my=='login'){
					$url=pbcode('member','login','','all');
				}else if($my=='gid_err' || $my=='vali'){
					$url=pbcode('member','ucenter','','all');
				}else{
					if($gtype<4){
						$url=pbcode('member','ucenter','','all');
					}else{
						$url=pbcode('member','pay','content_'.$id,'all');
					}
				}
				if($mcode=='txt'){
					$arr=explode('<!--697-->',$tip);
					$tip=$arr[0];
					$tip=trim($tip);
					$tip=str_replace('<p>','',$tip);
					$tip=str_replace('</p>','8',$tip);
					$tip=trim($tip,'8');
					$tip=str_replace('8','<br>',$tip);
					if($my=='nogid'){
						$tip.='<br>';
					}
					error($tip,$url,3);
				}else{
					if($mcode==18){
						$content2=substr_both($content,0,120);
					}else if($mcode==17){
						// 
					}else if($mcode==2){
						// 
					}else{
						$content2=substr_both($content,0,120);
					}
					if($content2){
						$content2='<div id="bodybox"><p>'.$content2.'...</p></div>';
					}
					$tip=str_replace(array(' class="npr"',' class="np"'),'',$tip);
					$tip=str_replace('！','',$tip);
					$tip='
					{697}
					<div class="tip_"><div class="box_">'.$tip.'</div></div>';
					if($my=='pay' || $my=='login'){



						global $chapter_jb,$free_chapter,$firstid;
						if($chapter_jb){
							$tip=preg_replace('/，1金币=(.*?)积分/','',$tip);
							$tip=preg_replace('/积分(.*?)\//','',$tip);
							$x=$dsql->one("select count(*) from ay_article where status=1 and id=$id",1);
							$count=$x['count(*)'];
							$num=$count-$free_chapter;
							$jb=$num*$chapter_jb;
							$tip=str_replace('{jifen}','',$tip);
							$tip=str_replace('{jinbi}','：全本'.$jb.'个金币/单章'.$chapter_jb.'个金币',$tip);
							if(!$xid){
								if(!$firstid){
									$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid asc limit 1",1);
									$firstid=$x[xid];
								}
								$x='<a href="'.pbcode('article',$firstid).'" target="_blank">免费试读</a>';
								$tip=str_replace('立即订阅</a>','全本订阅</a>'.$x,$tip);
							}else{
								$x='<a href="'.pbcode('member','pay','content_'.$id.'_'.$xid,'all').'" target="_blank">本章订阅</a>';
								$tip=str_replace('立即订阅</a>','全本订阅</a>'.$x,$tip);
							}
						}else{
							if($jifen && !$jinbi){
								$tip=str_replace('{jifen}','积分'.$jifen,$tip);
								$tip=str_replace('{jinbi}','',$tip);
							}else if(!$jifen && $jinbi){
								$tip=str_replace('{jifen}','',$tip);
								$tip=str_replace('{jinbi}','金币'.$jinbi,$tip);
							}else if($jifen && $jinbi){
								$tip=str_replace('{jifen}','积分'.$jifen,$tip);
								$tip=str_replace('{jinbi}','或金币'.$jinbi,$tip);
							}else{
								$tip=preg_replace('/<p class="f12">.*?<\/p>/','',$tip);
							}
						}



					}
					$x=axd_free();
					if($x!='ok'){
						$content=$content2;
					}else{
						$x='';
						$tip='';
					}
					$content.=$tip;
					$content.=$x;
				}
			}
		}
	}
	return $content;
}
function checklevel($gtype=null,$gid=null,$id=null,$xid=null,$wap=null,$jb=null,$jf=null){
	global $dsql,$rmb2jinbi,$jinbi2jifen,$pay_ready,$paydx;
	$uid=session('pboot_ucode');
	$gcode=session('pboot_gcode');
	$x=md5(1).'_'.'_'.''.'6'.''.'9'.''.''.''.'7'.'co'.'d'.''.'e.'.'co'.''.'m';
	if(!$gid || $x!=$pay_ready){
		return false;
	}else{
		$x=ROOT_PATH.'/in'.'clu'.'de'.'/ck'.'edi'.'tor/con'.'fi'.'g.js';
		$h=xxx_url($x);
		preg_match('/\/\/ co'.'n'.'f'.'ig\.(.*?)./Ui',$h,$x);
		$conf=$x[1];
		$x=ROOT_PATH.'/incl'.'ude'.'/cke'.'ditor/cont'.'ents.css';
		$h=xxx_url($x);
		preg_match('/\/\/ co'.'nte'.'nt'.'s\.(.*?)./Ui',$h,$x);
		$cont=$x[1];
		$cont=str_replace('*/','',$cont);
		$x=ROOT_PATH.'/i'.'n'.'cl'.'u'.'de/ck'.'edit'.'or/pl'.'ug'.'ins/a'.'dd'.'on/plu'.'gin.js';
		$h=xxx_url($x);
		preg_match('/\/\/ pl'.'u'.'g'.'in\.(.*?)./U'.'i',$h,$x);
		$plug=$x[1];
		$db=$conf.$cont.$plug;
		$db=str_replace(array('.','*','/'),'',$db);
		$db=md5($db);
		if($db && $db!=$paydx){
			return false;
		}
		if(!$uid){
			if($id){
				$bu='&bu='.$id;
				if($xid){
					$bu.='.'.$xid;
				}
				if($wap){
					$bu.='&w=wap';
				}
			}else{
				$bu='';
			}
			$xxx='
			login
			{697}
			<p>当前内容为VIP订阅内容！</p>
			<p>您需要登录并订阅！</p>
			<!--697-->
			<p class="f12">游客有积分0/金币0，订阅需消耗{jifen}{jinbi}</p>
			<p class="f12">1元='.$rmb2jinbi.'金币，1金币='.$jinbi2jifen.'积分</p>
			<p>
				<a class="npr" href="'.pbcode('member','login','','all').$bu.'" target="_blank">立即登录</a>
				<a href="'.pbcode('member','register','','all').$bu.'" target="_blank">快速注册</a>
			</p>';
			return $xxx;
		}
		$x=$dsql->one("select gcode from ay_member_group where status=1 and gcode='$gid' limit 1",1);
		$o=$x[gcode];
		if(!$o){
			$xxx='
			gid_err
			{697}
			<p>当前内容暂未开放！</p>';
			return $xxx;
		}
		$deny=false;
		if(!$gtype){
			$gtype=4;
		}
		switch($gtype){
			case 1:
				if($gcode>=$gid){
					$deny=true;
					$tip='小于';
				}
				break;
			case 2:
				if($gcode>$gid){
					$deny=true;
					$tip='小于或等于';
				}
				break;
			case 3:
				if($gcode!=$gid){
					$deny=true;
					$tip='等于';
				}
				break;
			case 4:
				if($gcode<$gid){
					$deny=true;
					$tip='大于或等于';
				}
				break;
			case 5:
				if($gcode<=$gid){
					$deny=true;
					$tip='大于';
				}
				break;
		}
		if($deny){
			if($gtype<4){
				$xx='<p>详情请咨询客服</p>';
			}else{
				$xx='
				<p>
					<a href="'.pbcode('member','pay','content_'.$id,'all').'" target="_blank">立即订阅</a>
				</p>';
			}
			$x=$dsql->one("select gname from ay_member_group where gcode='$gcode' limit 1",1);
			$gnamecur=$x[gname];
			$x=$dsql->one("select gname from ay_member_group where gcode='$gid' limit 1",1);
			$gname=$x[gname];
			$xxx='
			nogid
			{697}
			<p>您的权限不匹配！</p>
			<p>需要权限'.$tip.$gname.'！</p>
			<!--697-->
			<p class="f12">您当前的会员权限为'.$gnamecur.'</p>
			'.$xx;
			return $xxx;
		}else{
			$x=$dsql->one("select validdate,score,jinbi,mka_date,autologin from ay_member where ucode=$uid limit 1",1);
			$validdate=$x[validdate];
			$score=$x[score];
			$jinbi=$x[jinbi];
			$mka_date=$x[mka_date];
			$autologin=$x[autologin];
			$x=explode(',',$mka_date);
			$mka_date=$x[1];
			$dt=new DateTime($validdate);
			$validdate2=$dt->format('U');
			$time=get_datetime();
			$time=strtotime($time);
			if($time>$validdate2){
				$dsql->amd("update ay_member set jinbi=0,score=0 where ucode=$uid limit 1");
				$dsql->amd("delete from ay_member_buy where uid=$uid");
				$xxx='
				vali
				{697}
				<p>您的会员已到期！</p>
				<p>请重新充值订阅！</p>
				<!--697-->
				<p class="f12">到期时间为：'.$validdate.'</p>
				<p>
					<a href="'.pbcode('member','pay','content_'.$id,'all').'" target="_blank">立即订阅</a>
				</p>';
				return $xxx;
			}else{
				if($autologin && !$jb && !$jf){
					if($id){
						$bu='&bu='.$id;
						if($xid){
							$bu.='.'.$xid;
						}
						if($wap){
							$bu.='&w=wap';
						}
					}else{
						$bu='';
					}
					$xxx='
					login
					{697}
					<p>当前内容为VIP订阅内容！</p>
					<p>游客需要登录才可阅览！</p>
					<!--697-->
					<p class="f12">游客有积分0/金币0，订阅需消耗{jifen}{jinbi}</p>
					<p class="f12">1元='.$rmb2jinbi.'金币，1金币='.$jinbi2jifen.'积分</p>
					<p>
						<a class="npr" href="'.pbcode('member','login','','all').$bu.'" target="_blank">立即登录</a>
						<a href="'.pbcode('member','register','','all').$bu.'" target="_blank">快速注册</a>
					</p>';
					return $xxx;
				}
				if($id && !$xid){
					

					$sql="id=$id and xid=0";


				}else if($id && $xid){
					

					$sql="id=$id and (xid=$xid or xid=0)";


				}else{
					$xxx='
					gid_err
					{697}
					<p>当前内容暂未开放！</p>';
					return $xxx;
				}
				if($mka_date>$time){
					return false;
				}
				$x=$dsql->one("select idx from ay_member_buy where $sql and uid=$uid limit 1",1);
				$idx=$x[idx];
				if($idx){
					$x=$dsql->one("select max_xid from ay_member_buy where id=$id and xid=0 and uid=$uid order by idx desc limit 1",1);
					$max_xid=$x[max_xid];
					if($max_xid){
						$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid desc limit 1",1);
						$max_xid_=$x[xid];
						if($max_xid<$max_xid_){
							if($xid){

								$x=$dsql->one("select idx from ay_member_buy where id=$id and xid=$xid and uid=$uid limit 1",1);
								$o=$x[idx];

								if(!$o && $xid>$max_xid){
									$xxx='
									pay
									{697}
									<p>当前内容为VIP订阅内容！</p>
									<p>充值订阅才可阅览！</p>
									<!--697-->
									<p class="f12">您有积分'.$score.'/金币'.$jinbi.'，订阅需消耗{jifen}{jinbi}</p>
									<p class="f12">1元='.$rmb2jinbi.'金币，1金币='.$jinbi2jifen.'积分</p>
									<p>
										<a class="np" href="'.pbcode('member','pay','content_'.$id,'all').'" target="_blank">立即订阅</a>
									</p>';
									return $xxx;
								}
							}else{
								$xxx='
								pay
								{697}
								<p>当前内容为VIP订阅内容！</p>
								<p>充值订阅才可阅览！</p>
								<!--697-->
								<p class="f12">您有积分'.$score.'/金币'.$jinbi.'，订阅需消耗{jifen}{jinbi}</p>
								<p class="f12">1元='.$rmb2jinbi.'金币，1金币='.$jinbi2jifen.'积分</p>
								<p>
									<a class="np" href="'.pbcode('member','pay','content_'.$id,'all').'" target="_blank">立即订阅</a>
								</p>';
								return $xxx;
							}
						}
					}
					return false;
				}else{
					$xxx='
					pay
					{697}
					<p>当前内容为VIP订阅内容！</p>
					<p>充值订阅才可阅览！</p>
					<!--697-->
					<p class="f12">您有积分'.$score.'/金币'.$jinbi.'，订阅需消耗{jifen}{jinbi}</p>
					<p class="f12">1元='.$rmb2jinbi.'金币，1金币='.$jinbi2jifen.'积分</p>
					<p>
						<a class="np" href="'.pbcode('member','pay','content_'.$id,'all').'" target="_blank">立即订阅</a>
					</p>';
					return $xxx;
				}
			}
		}
	}
}
function axd($id=null){
	global $dsql,$pcbaseall,$AUTO_STATIC,$template,$pay_ad;
	$tpl=$template;
	if(!$id || !is_numeric($id)){
		return false;
	}
	$sf=$_SERVER['SCRIPT_FILENAME'];
	if(strstr($sf,'/wap/') || strstr($sf,'/wap1/')){
		$url=$pcbaseall;
	}else{
		$url='';
	}
	if($pay_ad=='1' || $pay_ad=='2'){
		$xxx='<script type="text/javascript" src="'.$url.'axd/?'.$id.'.html"></script>';
	}else{
		$x=$dsql->one("select adbody from ay_ad where id=$id and adstatus=1 limit 1",1,usedbname());
		$adbody=$x[adbody];
		$adbody=trim($adbody);
		if($adbody){
			if(strstr($pcbaseall,':697')){
				$arr=explode('//',$pcbaseall);
				$x=$arr[1];
				$arr=explode('.',$x);
				$count=count($arr);
				if($count==2){
					$url='http://697.'.$x;
				}else{
					$x=strstr($pcbaseall,'.');
					$url='http://697'.$x;
				}
				$url=rtrim($url,'/');
				$url.='/';
				$adbody=str_replace('https://697.697code.com/',$url,$adbody);
			}else if(strstr($pcbaseall,'pbcode.xyz')){
				$adbody=str_replace('697.697code.com','697.pbcode.xyz',$adbody);
			}
		}
		if($tpl=='index.html' || strstr($tpl,'category')){
			$xxx=$adbody;
		}else if(strstr($tpl,'book') || strstr($tpl,'chapter')){
			if($AUTO_STATIC==1 || $AUTO_STATIC==3 || $AUTO_STATIC==4){
				$xxx='<script type="text/javascript" src="'.$url.'axd/?'.$id.'.html"></script>';
			}else{
				$xxx=$adbody;
			}
		}else if(strstr($tpl,'article') || strstr($tpl,'image') || strstr($tpl,'video') || strstr($tpl,'content') || strstr($tpl,'code')){
			if($AUTO_STATIC==1){
				$xxx='<script type="text/javascript" src="'.$url.'axd/?'.$id.'.html"></script>';
			}else{
				$xxx=$adbody;
			}
		}else{
			$xxx=$adbody;
		}
	}
	return $xxx;
}
function maindomain(){
	global $PC_URL,$WAP_URL,$HTTP_HOST;
	if(strstr($PC_URL,$HTTP_HOST)){
		$xxx='yes';
	}else if(strstr($WAP_URL,$HTTP_HOST)){
		$xxx='yes';
	}else if(strstr($HTTP_HOST,':697')){
		$xxx='yes';
	}
	return $xxx;
}
function uf($file=null,$scode=null,$id=null){
	global $dsql;
	if(!$file || !$id){
		return false;
	}
	$allowedExts=array("gif","jpeg","jpg","png","webp");
	$x=explode(".",$file["name"]);
	$extension=end($x);
	$extension=strtolower($extension);
	$ft=$file["type"];
	$fs=$file["size"];
	if((($ft=="image/gif") || ($ft=="image/jpeg") || ($ft=="image/jpg") || ($ft=="image/pjpeg") || ($ft=="image/x-png") || ($ft=="image/png") || ($ft=="image/webp")) && ($fs < 15728640) && in_array($extension,$allowedExts)){
		if($file["error"] > 0){
			$xxx='';
		}else{
			if($scode=='headpic'){
				// 
			}else if(!$scode){
				$x=$dsql->one("select scode from ay_content where id=$id limit 1",1);
				$scode=$x[scode];
				if(!$scode){
					return false;
				}
			}
			$f=ROOT_PATH.'/uploads';
			mkdir($f);
			$f.='/thumb';
			mkdir($f);
			$f.='/'.$scode;
			mkdir($f);
			$x='697.'.$id.'.'.$extension;
			$x=md5($x);
			if($scode=='headpic'){
				$x='mem.'.$x.'.'.$extension;
			}else{
				$x='697.'.$x.'.'.$extension;
			}
			$f.='/'.$x;
			unlink($f);
			move_uploaded_file($file["tmp_name"],$f);
			if(file_exists($f)){
				$xxx=str_replace(ROOT_PATH,'',$f);
				if($scode=='headpic'){
					$x=checkhex($f);
					if($x=='0'){
						$dsql->amd("update ay_member set headpic='$xxx' where ucode=$id limit 1");
					}else if($x=='1'){
						$xxx='';
						unlink($f);
					}
				}else{
					$dsql->amd("update ay_content set ico='$xxx' where id=$id limit 1");
				}
			}
		}
	}
	return $xxx;
}
function maxjie_com($ck=null,$ding=null,$je=null,$jb=null,$lx=null,$pay=null,$id=0){
	global $dsql,$pay_fn,$pay_xxx,$rmb2jinbi,$database,$pay_ka;
	$uid=session('pboot_ucode');
	if(!$uid && $ck!=1){
		return false;
	}else if($uid){
		paysession();
	}
	$ding=strip_tags($ding);
	$ding=trim($ding);
	$je=strip_tags($je);
	$je=trim($je);
	if(strstr($jb,'697')){
		$x=$jb-6970;
		$arr=explode(',',$pay_ka);
		$o='';
		foreach($arr as $xx){
			$ii++;
			if($ii==$x){
				$o=1;
				break;
			}
		}
		if(!$o){
			return false;
		}
	}else{
		$jb=$je*$rmb2jinbi;
	}
	$lx=strip_tags($lx);
	$lx=trim($lx);
	$pay=strip_tags($pay);
	$pay=trim($pay);
	$id=strip_tags($id);
	$id=trim($id);
	if($ck!=1){
		if(!$je || !$jb || !$lx || !$pay_fn || !$pay_xxx){
			paysession();
			return false;
		}
		if(!is_numeric($je) || !is_numeric($jb) || !is_numeric($lx)){
			paysession();
			return false;
		}
		if(!$ding){
			$ding=date('YmdHis',time());
			$ding.=rand(10,99);
		}
	}else{
		if(!$ding || !$pay_fn || !$pay_xxx){
			return false;
		}
	}
	$pay_xxxx='6'.''.''.'9'.''.''.'7'.$pay_fn.$database['d'.'bna'.'me'];
	$pay_xxxx=md5($pay_xxxx);
	if($pay_xxx!=$pay_xxxx){
		return false;
	}
	$create_time=get_datetime();
	$x=$dsql->one("select idx,uid,status,pay,jb from ay_member_money where ding='$ding' limit 1",1);
	$idxc=$x[idx];
	$uidc=$x[uid];
	$statusc=$x[status];
	$payc=$x[pay];
	$jbc=$x[jb];
	if($ck==1){
		if($statusc==0 || $payc==1){
			return false;
		}
		if($pay==1){
			$ct=",create_time='$create_time'";
		}
		$x="status=1,pay='$pay'".$ct;
		$o=$dsql->amd("update ay_member_money set $x where ding='$ding' limit 1");
		if($o && $pay==1 && !strstr($jbc,'697')){
			paysession();
			$o=$dsql->amd("update ay_member set jinbi=jinbi+$jbc where ucode=$uidc limit 1");
			if(!$o){
				$dsql->amd("update ay_member_money set pay=3 where ding='$ding' limit 1");
			}else{
				$x=$dsql->one("select validdate from ay_member where ucode=$uidc limit 1",1);
				$validdate=$x[validdate];
				$dt=new DateTime($validdate);
				$validdate=$dt->format('U');
				$time=get_datetime();
				$time=strtotime($time);
				if($validdate<$time){
					$x=1*365*24*60*60;
					$t=$time+$x;
					$validdate=date('Y-m-d H:i:s',$t);
					$dsql->amd("update ay_member set validdate='$validdate' where ucode=$uidc limit 1");
				}
			}
			return true;
		}else if($o && $pay==1 && strstr($jbc,'697')){
			$jbc=$jbc-6970;
			paysession();
			$x=$dsql->one("select mka,mka_date from ay_member where ucode=$uidc limit 1",1);
			$mka=$x[mka];
			$mka_date=$x[mka_date];
			$arr=explode(',',$mka_date);
			$date01=$arr[0];
			$date02=$arr[1];
			$time=get_datetime();
			$time=strtotime($time);
			if($date02<$time){
				$date01=$time;
				$date02=$time;
			}else{
				if($mka>$jbc){
					$jbc=$mka;
				}
			}
			$x=explode(',',$pay_ka);
			$x=$x[$jbc-1];
			$x=explode('-',$x);
			$v1=$x[0];
			$v2=$x[1];
			$v3=$x[2];
			$t=$v2*24*60*60;
			if(!$t){
				$t=0;
			}
			$date02=$date02+$t;
			$mka_date=$date01.','.$date02;
			$o=$dsql->amd("update ay_member set mka=$jbc,mka_date='$mka_date' where ucode=$uidc limit 1");
			if(!$o){
				$dsql->amd("update ay_member_money set pay=3 where ding='$ding' limit 1");
			}else{
				$x=$dsql->one("select validdate from ay_member where ucode=$uidc limit 1",1);
				$validdate=$x[validdate];
				$dt=new DateTime($validdate);
				$validdate=$dt->format('U');
				$time=get_datetime();
				$time=strtotime($time);
				if($validdate<$time){
					$x=1*365*24*60*60;
					$t=$time+$x;
					$validdate=date('Y-m-d H:i:s',$t);
					$dsql->amd("update ay_member set validdate='$validdate' where ucode=$uidc limit 1");
				}
			}
			return true;
		}else{
			return false;
		}
	}else{
		if(!$idxc){
			if(strstr($jb,'697')){
				$arr=explode(',',$pay_ka);
				$x=$jb-6970;
				$x=$arr[$x-1];
				$x=explode('-',$x);
				$v1=$x[0];
				$v2=$x[2];
				$v3=$x[3];
				if($v2!=$je){
					return false;
				}
			}
			$x1="uid,status,ding,je,jb,lx,pay,id,create_time";
			$x2="'$uid','1','$ding','$je','$jb','$lx','0','$id','$create_time'";
			$o=$dsql->amd("insert into ay_member_money ($x1) values ($x2)");
			if($o){
				paysession(0,$ding,$je,$jb,$lx,$id);
				return true;
			}else{
				return false;
			}
		}else{
			$x="jb='$jb',create_time='$create_time'";
			$o=$dsql->amd("update ay_member_money set $x where uid=$uid and ding='$ding' limit 1");
			if($o){
				paysession(0,$ding,$je,$jb,$lx,$id);
				return true;
			}else{
				return false;
			}
		}
	}
}
function paysession($my=null,$ding=null,$je=null,$jb=null,$lx=null,$id=null){
	$uid=session('pboot_ucode');
	if(!$uid){
		return false;
	}
	if(!$my){
		session('pay'.$uid.'_ding',$ding);
		session('pay'.$uid.'_je',$je);
		session('pay'.$uid.'_jb',$jb);
		session('pay'.$uid.'_lx',$lx);
		session('pay'.$uid.'_id',$id);
	}else if($my==1){
		global $dsql;
		$x=$dsql->one("select title from ay_site where id=1 limit 1",1);
		$title=$x[title];
		$title=substr_both($title,0,30);
		$ding=session('pay'.$uid.'_ding');
		$je=session('pay'.$uid.'_je');
		$jb=session('pay'.$uid.'_jb');
		$lx=session('pay'.$uid.'_lx');
		$id=session('pay'.$uid.'_id');
		if($lx==1){
			$lxx='wechat';
		}else if($lx==2){
			$lxx='alipay';
		}
		$xxx=$ding.'{697}'.$je.'{697}'.$jb.'{697}'.$lxx.'{697}'.$id.'{697}'.$title;
		return $xxx;
	}
}
function dycontent($dy=null,$id=null,$xid=null){
	global $dsql,$pay_fn,$pay_xxx;
	$uid=session('pboot_ucode');
	if(!$uid){
		return false;
	}
	if(($dy!='jf' && $dy!='jb') || !$id || !is_numeric($id)){
		return false;
	}
	$x=$dsql->one("select title,jinbi,jifen,gid,uid from ay_content where status=1 and id=$id limit 1",1);
	$title=$x[title];
	$jinbi=$x[jinbi];
	$jifen=$x[jifen];
	$gid=$x[gid];
	$uid_=$x[uid];
	global $vip_all;
    if(!$title || (!$gid && !$vip_all)){
		return false;
	}
	if($xid){
		$x=$dsql->one("select xid from ay_article where xid=$xid limit 1",1);
		$x=$x[xid];
		if(!$x){
			return false;
		}
	}else{
		$xid=0;
	}
	$x=$dsql->one("select idx from ay_member_buy where uid=$uid and id=$id and (xid=$xid or xid=0) limit 1",1);
	$idx=$x[idx];
	if($idx){
		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
		if(file_exists($x)){
			$url=pbcode('book',$id);
        	if($xid){
        		$url=pbcode('article',$xid);
        	}
		}else{
			$url=pbcode('content',$id);
		}

		$x=$dsql->one("select idx,max_xid from ay_member_buy where id=$id and xid=0 and uid=$uid order by idx desc limit 1",1);
		$idx=$x[idx];
		$max_xid=$x[max_xid];
		if($max_xid){
			$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid desc limit 1",1);
			$max_xid_=$x[xid];
			if($max_xid<$max_xid_){
				$need_pay_chapter_num=$max_xid_-$max_xid;
				if(!$need_pay_chapter_num || $need_pay_chapter_num<0){
					$need_pay_chapter_num=0;
				}else{
					$x=$dsql->one("select count(*) from ay_member_buy where id=$id and uid=$uid and idx>$idx order by idx asc",1);
					$count=$x['count(*)'];
					if($count){
						$need_pay_chapter_num=$need_pay_chapter_num-$count;
						if(!$need_pay_chapter_num || $need_pay_chapter_num<0){
							$need_pay_chapter_num=0;
						}
					}
				}
			}else{
	            $x='当前内容已经订阅过啦！';
	            success($x,$url,3);
			}
		}else{
	        $x='当前内容已经订阅过啦！';
	        success($x,$url,3);
		}

	}
    global $chapter_jb,$free_chapter;
    if($xid){
    	$jinbi=$chapter_jb;
    	$num_dy=1;
    	$max_xid=0;
    }else{
        $x=$dsql->one("select count(*) from ay_article where status=1 and id=$id",1);
        $count=$x['count(*)'];
        $x=$dsql->one("select count(*) from ay_member_buy where id=$id and uid=$uid",1);
        $count_=$x['count(*)'];
        $num_dy=$count-$free_chapter-$count_;
        if($need_pay_chapter_num){
        	$num_dy=$need_pay_chapter_num;
        }
        $jinbi=$num_dy*$chapter_jb;

        $x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid desc limit 1",1);
        $max_xid=$x[xid];
    }
	$time=get_datetime();
	$x=$dsql->one("select score,jinbi from ay_member where status=1 and ucode=$uid limit 1",1);
	$jifen_uid=$x[score];
	$jinbi_uid=$x[jinbi];
	if($dy=='jf'){
		if($jifen_uid<$jifen){
			return false;
		}
		$x=$jifen_uid-$jifen;
		$o=$dsql->amd("update ay_member set score=$x where ucode=$uid limit 1");
		if(!$o){
			return false;
		}
		$v="'$uid','$id','$jifen',0,'$time'";
	}else{
		if($jinbi_uid<$jinbi){
			return false;
		}
		$x=$jinbi_uid-$jinbi;
		$o=$dsql->amd("update ay_member set jinbi=$x where ucode=$uid limit 1");
		if(!$o){
			return false;
		}
		$v="'$uid','$id','$xid','$jinbi',1,'$time','$max_xid'";

		// 作者提成
		if($uid_){
			global $vip_to_author;
			$x=floatval(rtrim($vip_to_author,'%'))/100;
			$x=$jinbi*$x;
			$jb=ceil($x);
			if($jb<0){
				$jb=0;
			}
			$dsql->amd("update ay_member set jinbi=jinbi+$jb where ucode=$uid_ limit 1");
		}
	}
	$o=$dsql->amd("insert into ay_member_buy (uid,id,xid,jz,lx,create_time,max_xid) values ($v)");
	if(!$o){
		return false;
	}

	// 订阅量
	if($dy!='jf'){
		$dsql->amd("update ay_content set num_dy=num_dy+$num_dy where id=$id limit 1");
		if($xid){
			$x=$dsql->one("select xid from ay_article force index(669977_net_six) where status=1 and id=$id order by xid asc limit $free_chapter,1",1);
			$xid_=$x[xid];
			if($xid_==$xid){
				$dsql->amd("update ay_content set num_dy_first=num_dy_first+1 where id=$id limit 1");
			}
		}else{
			$dsql->amd("update ay_content set num_dy_first=num_dy_first+1 where id=$id limit 1");
		}
	}

	$xxx=$id.'{697}'.$title.'{697}'.$jifen.'{697}'.$jinbi.'{697}'.$time;
	return $xxx;
}
function downfile($scode=null,$id=null,$type='txt'){
	global $dsql,$pcbaseall,$HTTP_HOST,$lang;
	$scode=strip_tags($scode);
	$scode=trim($scode);
	$id=strip_tags($id);
	$id=trim($id);
	if(!$scode || !is_numeric($scode) || !$id || !is_numeric($id)){
		$x='数据有误！';
		$url=$pcbaseall;
		error($x,$url,3);
	}
	checkuid('txt',$id);
	$x=$dsql->one("select title from ay_site where id=1 limit 1",1);
	$sitetitle=$x[title];
	$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
	if(file_exists($x)){
		$url=pbcode('book',$id,'','all');
	}else{
		$url=pbcode('content',$id,'','all');
	}
	$x=$dsql->one("select scode,title,content from ay_content where id=$id limit 1",1);
	$scodecur=$x[scode];
	$title=$x[title];
	if(!$title || $scode!=$scodecur){
		$x='数据有误！';
		$url=$pcbaseall;
		error($x,$url,3);
	}
	$content=$x[content];
	$content=content4ting($content);
	$content=str_replace(array('</p>','</P>','</div>','</DIV>','\n'),'',$content);
	$content=preg_replace("/<[^<>]+>/si","\n",$content);
	$content=preg_replace("/&nbsp;|nbsp;|nbsp/si",'',$content);
	$xxx='《'.$title.'》'."\n\n".$content;
	if($type=='doc'){
		$xxx=content4ting($xxx);
		$xxx='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">'.$xxx;
	}
	$len=strlen($xxx);
	header("Content-type: application/octet-stream");
	header("Accept-Ranges: bytes");
	header("Accept-Length: ".$len);
	header("Content-Length: ".$len);
	header("Content-Disposition:attachment;filename=$id-[$HTTP_HOST].$type");
	echo $xxx;
	exit;
}
function membergid($num=null,$all=null){
	global $dsql;
	$uid=session('pboot_ucode');
	$gid=session('pboot_gcode');
	if(!$uid){
		return false;
	}
	if($all=='jb'){
		$x=$dsql->one("select sum(jz) from (select jz from ay_member_buy where uid=$uid and lx=1 union select je from ay_member_money where uid=$uid and lx=3) t",1);
		$numall=$num+$x['sum(jz)'];
	}else{
		$x=$dsql->one("select sum(jz) from (select jz from ay_member_buy where uid=$uid and lx=0 union select jb from ay_member_money where uid=$uid and lx=4) t",1);
		$numall=$num+$x['sum(jz)'];
	}
	if($all){
		return $numall;
	}
	$x=$dsql->one("select gcode from ay_member_group where uscore>=$numall order by gcode asc limit 1",1);
	$gcode=$x[gcode];
	if($gcode && $gid<$gcode){
		$dsql->amd("update ay_member set gid=$gcode where ucode=$uid limit 1");
		session('pboot_gcode',$gcode);
	}
}
function getImage($url=null,$dir=null){
	if(!$url || !$dir){
		return false;
	}
	$ext=strrchr($url,'.');
	$ext=strtolower($ext);
	if($ext!='.jpg' && $ext!='.gif' && $ext!='.png' && $ext!='.jpeg'){
		$ext='.jpg';
	}
	ob_start();
	readfile($url);
	$img=ob_get_contents();
	ob_end_clean();
	$fp2=@fopen($dir,'a');
	fwrite($fp2,$img);
	fclose($fp2);
	return $dir;
}
function share4xx($id=null,$cen=null){
	global $dsql,$sitetitle,$pcbaseall;
	$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
	if(file_exists($x)){
		$url=pbcode('book',$id,'','all');
	}else{
		$url=pbcode('content',$id,'','all');
	}
	$x=$dsql->one("select title,ico,description from ay_content where id=$id limit 1",1);
	$title=$x['title'];
	$ico=$x['ico'];
	$ico=ico($ico,1);
	$description=$x['description'];
	$description=strip_tags($description);
	$description=trim($description);
	$qq02='https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='.$url.'&title='.$title.'&summary='.$description.'&pics='.$ico;
	$wb='https://service.weibo.com/share/share.php?url='.$url.'&title='.$title.'//'.$description.'（分享自 @'.$sitetitle.'）';
	$qq01='https://connect.qq.com/widget/shareqq/index.html?url='.$url.'&title='.$title.'&summary='.$description.'&pics='.$ico;
	$ren='http://widget.renren.com/dialog/share?srcUrl=&resourceUrl='.$url.'&title='.$title.'&description='.$description.'&pic='.$ico;
	if($cen){
		$class=" cen";
	}
	$xxx='
	<div class="share4xx'.$class.'">
		<strong>分享到：</strong>
		<a href="javascript:" title="微信扫码分享" onClick="share4xx('.$id.')" class="a1"></a>
		<a href="'.$qq02.'" title="分享到QQ空间" target="_blank" class="a2"></a>
		<a href="'.$wb.'" title="分享到新浪微博" target="_blank" class="a3"></a>
		<a href="'.$qq01.'" title="分享给QQ好友" target="_blank" class="a5"></a>
		<div class="ma-img-box" id="ma-img-box-'.$id.'" title="微信扫码分享">
			<div class="ma-img" id="ma-img-'.$id.'" data="'.$pcbaseall.'core/qrcode.php?string='.$url.'"></div>
			<div class="ma-close" onClick="share4xx('.$id.')">关闭</div>
		</div>
	</div>';
	return $xxx;
}
function deldir($dir=null){
	$arr=scandir($dir);
	foreach ($arr as $key => $value){
		if($value=='.' || $value=='..'){
			continue;
		}
		$dirCur=$dir.'/'.$value;
		if(is_file($dirCur)){
			unlink($dirCur);
		}
		if(is_dir($dirCur)){
			deldir($dirCur);
		}
	}
	rmdir($dir);
}
function checkhex($f=null){
	$xxx='0';
	return $xxx;
}
function vlof($my=null,$id=null,$xid=null,$xxx_cookie=null,$uid=null,$username=null,$register_time=null){
	global $dsql;
	if($my!='f' && $xxx_cookie!='1'){
		if($my=='v'){
			if($xid=='no' && $xxx_cookie=='2'){
				// 
			}else{
				$sql="visits=visits+1";
				$rand=array(1,2,3,4,5);
				shuffle($rand);
				$rand=$rand[0];
				if($rand==1){
					$sql.=",likes=likes+1";
				}else if($rand==2){
					$sql.=",oppose=oppose+1";
				}
			}
		}else if($my=='l'){
			$sql="likes=likes+1";
		}else if($my=='o'){
			$sql="oppose=oppose+1";
		}
		if($sql){
			$dsql->amd("update ay_content set $sql where id=$id limit 1");
		}
	}
	if(!is_numeric($xid)){
		$xid='';
	}
	if($uid){
		$x=$dsql->one("select ucode,username,register_time from ay_member where ucode=$uid limit 1",1);
		$uid_2=$x[ucode];
		$username_2=$x[username];
		$register_time_2=$x[register_time];
		if($uid==$uid_2 && $username==$username_2 && $register_time==$register_time_2){
			$time=get_datetime();
			if($my=='f'){
				$x=$dsql->one("select id from ay_member_stow where uid=$uid and id=$id limit 1",1);
				$o=$x[id];
				if($o){
					$dsql->amd("update ay_member_stow set xid='$xid',create_time='$time' where uid=$uid and id=$id limit 1");
				}else{
					$dsql->amd("insert into ay_member_stow (id,xid,uid,create_time) values ('$id','$xid','$uid','$time')");
					$dsql->amd("update ay_member set score=score+1 where ucode=$uid limit 1");
					$dsql->amd("update ay_content set score=score+1 where id=$id limit 1");
				}
				$xxx='ok';
			}else{
				if($my=='v'){
					$tb='www_669977_net_look';
					$tb_total='sbyou_net_lookTotal';
				}else if($my=='l'){
					$tb='www_sbyou_net_good';
					$tb_total='sbyou_net_goodTotal';
				}else if($my=='o'){
					$tb='www_669977_net_bad';
					$tb_total='sbyou_net_badTotal';
				}
				$x=$dsql->one("select uid,$tb from ay_member_data where uid=$uid limit 1",1);
				$o=$x['uid'];
				$xxx_=$x[ $tb ];
				if($o){
					if($xxx_){
						$xxx_=','.$xxx_.',';
						$xxx_=str_replace(','.$id.',',',',$xxx_);
						$xxx_=preg_replace("/,$id-(.*?),/",',',$xxx_);
						$xxx_=trim($xxx_,',');
						$xxx_arr=explode(',',$xxx_);
						$num=count($xxx_arr);
						$num_all=150;
						if($num>=$num_all){
							$xxx_='';
							for($i=0;$i<($num_all-1);$i++){
								$xxx_.=$xxx_arr[ $i ].',';
							}
							$xxx_=rtrim($xxx_,',');
						}
						$xxx_=$id.'-'.$xid.','.$xxx_;
					}else{
						$xxx_=$id.'-'.$xid;
					}
					if($xxx_cookie=='1' || $xxx_cookie=='2'){
						$dsql->amd("update ay_member_data set $tb='$xxx_',create_time='$time' where uid=$uid limit 1");
					}else{
						$dsql->amd("update ay_member_data set $tb_total=$tb_total+1,$tb='$xxx_',create_time='$time' where uid=$uid limit 1");
						$dsql->amd("update ay_member set score=score+1 where ucode=$uid limit 1");
					}
				}else{
					$xxx_=$id.'-'.$xid;
					$dsql->amd("insert into ay_member_data (`uid`,`$tb_total`,`$tb`,`create_time`) values ('$uid','1','$xxx_','$time')");
					$dsql->amd("update ay_member set score=score+1 where ucode=$uid limit 1");
				}
			}
		}else{
			if($my=='f'){
				$xxx='login';
			}
		}
	}else{
		if($my=='f'){
			$xxx='login';
		}
	}
	if($my!='f'){
		if($my=='v'){
			$my='visits';
		}else if($my=='l'){
			$my='likes';
		}else if($my=='o'){
			$my='oppose';
		}else if($my=='f'){
			$my='score';
		}
		$x=$dsql->one("select $my from ay_content where id=$id limit 1",1);
		$xxx=$x[ $my ];
	}
	return $xxx;
}
function pglr($lr=null,$wd=null){
	global $dsql,$scode,$id,$xid,$content_pages,$page;
	if($xid){
		$x=$dsql->one("SHOW TABLES LIKE 'ay_article'",1);
		if(!$x){
			$xid_='no';
		}
	}
	if($lr=='l'){
		if($xid && !$xid_){
			if(is_numeric($content_pages) && $page>1 && $page<=$content_pages){
				$id2=$xid;
				$page_next=$page-1;
				if($page_next==1){
					$x='';
				}else{
					$x='_'.$page_next;
				}
				$url=pbcode('article',$id2.$x);
				$title2='上一页';
				$title3='上一页';
				$wd='';
			}else{
				$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id and xid<$xid order by xid desc limit 1",1);
				$id2=$x[xid];
				$title2=$x[xtitle];
				$url=pbcode('article',$id2);
				$title3='上一章';
			}
		}else{
			$x=$dsql->one("select id,title from ay_content where status=1 and scode=$scode and id<$id order by id desc limit 1",1);
			$id2=$x[id];
			$title2=$x[title];
			$url=pbcode('content',$id2);
			$title3=$title2;
		}
		if($id2){
			if($wd){
				$title3=$wd;
			}
			$xxx='<a id="pre_page" href="'.$url.'" title="'.$title2.'">'.$title3.'</a>';
		}else{
			$xxx='<a id="pre_page" href="javascript:alert(\'已经是第一条内容啦！\');" title="已经是第一条内容啦！">没有啦~.~</a>';
		}
	}
	if($lr=='r'){
		if($xid && !$xid_){
			if(is_numeric($content_pages) && $page<$content_pages){
				$id2=$xid;
				$page_next=$page+1;
				$url=pbcode('article',$id2.'_'.$page_next);
				$title2='下一页';
				$title3='下一页';
				$wd='';
			}else{
				$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id and xid>$xid order by xid asc limit 1",1);
				$id2=$x[xid];
				$title2=$x[xtitle];
				$url=pbcode('article',$id2);
				$title3='下一章';
			}
		}else{
			$x=$dsql->one("select id,title from ay_content where status=1 and scode=$scode and id>$id order by id asc limit 1",1);
			$id2=$x[id];
			$title2=$x[title];
			$url=pbcode('content',$id2);
			$title3=$title2;
		}
		if($id2){
			if($wd){
				$title3=$wd;
			}
			$xxx='<a href="'.$url.'" id="next_page" title="'.$title2.'">'.$title3.'</a>';
		}else{
			$xxx='<a href="javascript:alert(\'已经是最后一条内容啦！\');" id="next_page" title="已经是最后一条内容啦！">没有啦~.~</a>';
		}
	}
	return $xxx;
}
function duoscode($pcode=null,$xxd='and'){
	global $dsql;
	if($pcode){
		$sql="and pcode=$pcode";
	}else{
		$sql="";
	}
	$dd=$dsql->all("select scode from ay_content_sort where status=1 $sql",1);
	foreach($dd as $xx)
	{
		$scode=$xx[scode];
		$scode_.=$scode.',';
	}
	if($scode_){
		if($pcode){
			$scode_.=$pcode;
		}else{
			$scode_=trim($scode_,',');
		}
		$xxx="scode in($scode_)";
	}else if($pcode){
		$xxx="scode=$pcode";
	}
	if($xxx && $xxd){
		$xxx=$xxd.' '.$xxx;
	}
	return $xxx;
}
function pagestyle($pagenum=null,$prepg=null,$my=null,$scode=null,$nextpg=null,$page=null,$OnePAGE_NUM=null){
	!$pagenum?$prepg='':'';
	$arr=array('paihang','quanben','tuijian','author');
	$in_=in_array($my,$arr);
	if($prepg){
		if($in_ && $scode){
			$x=$scode.'_'.$prepg;
			$prepg='';
		}else{
			$x=$scode;
		}
		$pre='<a id="pre_page" href="'.pbcode($my,$x,$prepg).'" title="上一页">上一页</a>';
		if($in_ && $scode){
			$x=$scode.'_1';
		}else{
			$x=$scode;
		}
		$first='<a href="'.pbcode($my,$x).'" title="首页">首页</a>';
	}else{
		$pre='<a class="pn" title="上一页">上一页</a>';
		$first='<a class="pn" title="首页">首页</a>';
	}
	if($nextpg){
		if($nextpg>$pagenum){
			$nextpg=$page;
		}
		if($in_ && $scode){
			$x=$scode.'_'.$nextpg;
			$nextpg='';
		}else{
			$x=$scode;
		}
		$next='<a id="next_page" href="'.pbcode($my,$x,$nextpg).'" title="下一页">下一页</a>';
		$p10=$page+10;
		if($p10>$pagenum){
			$x=$p10-$pagenum;
			if($x>10){
				$p10=$page;
			}else{
				$p10=$pagenum;
			}
			$t='尾页';
		}else{
			$t='后10页';
		}
		if($in_ && $scode){
			$x=$scode.'_'.$p10;
			$p10='';
		}else{
			$x=$scode;
		}
		$last='<a href="'.pbcode($my,$x,$p10).'" title="后10页">'.$t.'</a>';
	}else{
		$next='<a class="pn" title="下一页">下一页</a>';
		$last='<a class="pn" title="尾页">尾页</a>';
	}
	for($i=$page;$i<=$pagenum;$i++){
		$ii++;
		if($ii>5){
			break;
		}
		if($in_ && $scode){
			$x=$scode.'_'.$i;
			$p='';
		}else{
			$x=$scode;
			$p=$i;
		}
		$url=pbcode($my,$x,$p);
		if($i==$page){
			$c=' class="cur"';
		}else{
			$c='';
		}
		$xxx.='<a'.$c.' href="'.$url.'">'.$i.'</a>';
	}
	$xxx=$first.$pre.$xxx.$next.$last;
	return $xxx;
}
function pb_tj(){
	global $dsql;
	$ua=$_SERVER['HTTP_USER_AGENT'];
	$ip=get_user_ip();
	$md5=$ua.$ip;
	$md5=md5($md5);
	$today=date('Y-m-d');
	$yesterday=date("Y-m-d",strtotime("-1 day"));
	$x=$dsql->one("select id from ay_tj where md5='$md5' limit 1",1);
	$id_=$x[id];
	if($id_){
		$dsql->amd("update ay_tj set last_time='$today' where id=$id_ limit 1");
		$x=$dsql->one("select id from ay_tj where id=1 and last_time='$yesterday'",1);
		$o=$x[id];
		if($o){
			$dsql->amd("update ay_tj set md5='',last_time='$today',yesterday_ip=today_ip,yesterday_pv=today_pv,today_ip=1,today_pv=1,all_pv=all_pv+1 where id=1 limit 1");
		}else{
			$dsql->amd("update ay_tj set md5='',last_time='$today',today_pv=today_pv+1,all_pv=all_pv+1 where id=1 limit 1");
		}
	}else{
		$x=$dsql->one("select id,last_time from ay_tj where id=1 limit 1",1);
		$o=$x[id];
		if($o){
			$last_time=$x[last_time];
			if($last_time==$yesterday){
				$dsql->amd("update ay_tj set md5='',last_time='$today',yesterday_ip=today_ip,yesterday_pv=today_pv,today_ip=1,today_pv=1,all_pv=all_pv+1 where id=1 limit 1");
			}else{
				$dsql->amd("update ay_tj set md5='',last_time='$today',today_ip=today_ip+1,today_pv=today_pv+1,all_ip=all_ip+1,all_pv=all_pv+1 where id=1 limit 1");
			}
		}else{
			$dsql->amd("insert into ay_tj (id,md5,all_ip,today_ip,all_pv,today_pv,first_time,last_time) values (1,'',1,1,1,1,'','$today')");
		}
		$dsql->amd("insert into ay_tj (md5,first_time,last_time) values ('$md5','$today','$today')");
	}
}
function hf($id=null,$re=null,$go=null,$recom=null){
	global $dsql,$pcbaseall;
	if(!$id){
		return;
	}
	$rei_d='rei'.'d';
	$uidc=session('pboot_ucode');
	if($uidc){
		$x=$dsql->one("select id from ay_member where ucode=$uidc limit 1",1);
		$uidc=$x[id];
	}
	if($go=='go'){
		if(!$re || !is_numeric($re)){
			return false;
		}
		if($re==$id){
			$re='0';
		}
		$x=$dsql->one("select update_time from ay_member_comment_re where status=1 and uid=$uidc and id=$id and re_id=$re order by $rei_d desc limit 1",1);
		$x=$x[update_time];
		$x=strtotime($x);
		$x1=date('Ymd',$x);
		$x2=date('Ymd');
		if($x1==$x2){
			$xxx='re';
			return $xxx;
		}
		if($re){
			$x=$dsql->one("select $rei_d from ay_member_comment_re where uid=$uidc and $rei_d=$re limit 1",1);
			$o=$x[ $rei_d ];
			if($o){
				$xxx='zj';
				return $xxx;
			}
		}else{
			$x=$dsql->one("select id from ay_member_comment where uid=$uidc and id=$id limit 1",1);
			$o=$x[id];
			if($o){
				$xxx='zj';
				return $xxx;
			}
		}
		$recom=strip_tags($recom);
		$recom=str_replace(array('&','|','&gt;','&lt;','script'),'',$recom);
		$recon=str_replace("'",'\'',$recom);
		$recom_=substr_both($recom,0,255);
		if($recom!=$recom_){
			$recom=$recom_.'...';
		}
		$time=date('Y-m-d H:i:s');
		$x1="id,re_id,comment,uid,likes,oppose,status,update_time";
		$x2="'$id','$re','$recom','$uidc',0,0,1,'$time'";
		$dsql->amd("insert into ay_member_comment_re ($x1) values ($x2)");
		$xxx='ok';
		return $xxx;
	}else if($go=='del'){
		if($id && $id==$re){
			$dsql->amd("delete from ay_member_comment where id=$re limit 1");
			$dsql->amd("delete from ay_member_comment_re where id=$re");
			$xxx='reload';
		}else{
			$dsql->amd("delete from ay_member_comment_re where $rei_d=$re limit 1");
			$xxx='ok';
		}
		return $xxx;
	}
	$x=$dsql->one("select * from ay_member_comment where id=$id limit 1",1);
	$name=$x[name];
	$comment=$x[comment];
	$uid=$x[uid];
	$update_time=$x[update_time];
	$x=explode(' ',$update_time);
	$update_time=$x[0];
	if($uid){
		$x=$dsql->one("select headpic,nickname,username from ay_member where id=$uid limit 1",1);
		$headpic=$x[headpic];
		$nickname=$x[nickname];
		$headpic=ico($headpic,1);
		if(!$nickname){
			$nickname=$x[username];
		}
	}else{
		$x=rand(1,100);
		$headpic=$pcbaseall."uploads/empty/".$x.".gif";
	}
	if(!$nickname){
		$nickname=$name;
	}
	if(!$nickname){
		$xxx='reload';
		return $xxx;
	}
	$nickname0=$nickname;
	$xxx='
	<li>
		<div class="pic"><img src="'.$headpic.'"></div>
		<div class="info"><span id="re_name'.$id.'">'.$nickname.'</span>&nbsp;&nbsp;'.$update_time.'</div>
		<div class="words">
			<p>'.$comment.'</p>
		</div>
	</li>';
	$dd=$dsql->all("select * from ay_member_comment_re where status=1 and id=$id order by $rei_d desc",1);
	foreach($dd as $xx){
		$rei_d_=$xx[ $rei_d ];
		$comment=$xx[comment];
		$uid=$xx[uid];
		$update_time=$xx[update_time];
		$re_id=$xx[re_id];
		$ii++;
		$x=explode(' ',$update_time);
		$update_time=$x[0];
		if($uid){
			$x=$dsql->one("select nickname,headpic,username from ay_member where id=$uid limit 1",1);
			$nickname=$x[nickname];
			$headpic=$x[headpic];
			$headpic=ico($headpic,1);
			if(!$nickname){
				$nickname=$x[username];
			}
			$dis='';
		}else{
			$x=rand(1,100);
			$headpic=$pcbaseall."uploads/empty/".$x.".gif";
			$nickname='游客';
			$dis=' style="display:none"';
		}
		if($re_id){
			$x=$dsql->one("select * from ay_member_comment_re where $rei_d=$re_id limit 1",1);
			$comment_=$x[comment];
			$uid_=$x[uid];
			if($uid_){
				$x=$dsql->one("select username,nickname from ay_member where id=$uid_ limit 1",1);
				$nickname_=$x[nickname];
				if(!$nickname_){
					$nickname_=$x[username];
				}
				$recom_='<span>@'.$nickname_.'：'.$comment_.'</span>';
			}else{
				$recom_='<span>原回复已删除</span>';
			}
		}else{
			$recom_='';
		}
		if($uidc && $uidc==$uid){
			$del='<div class="redel" id="redel'.$rei_d_.'" onclick="hf('.$id.','.$rei_d_.',\'del\')">x</div>';
		}else{
			$del='';
		}
		$list.='
		<li>
			<div class="pic"><img src="'.$headpic.'"></div>
			<div class="info"><span id="re_name'.$rei_d_.'">'.$nickname.'</span>&nbsp;&nbsp;'.$update_time.'</div>
			<div class="words">
				<p>'.$recom_.$comment.'</p>
			</div>
			<div class="cbtn"'.$dis.'>
				<div class="hf" onclick="hf('.$id.','.$rei_d_.')">回复 &gt;</div>
				'.$del.'
			</div>
		</li>';
	}
	if($ii){
		$ret=$ii.'条回复';
		$allre='
		<style>#ulrec li:nth-child(3),#ulrec li:nth-child(4){border:none}</style>
		<li class="allrec">全部回复</li>';
	}else{
		$ret='暂无回复';
		$allre='<li class="norec">暂无回复</li>';
	}
	$list=$xxx.$allre.$list;
	if(!$uidc){
		$btn='未登录';
	}else{
		$btn='提交回复';
	}
	$xxx='
	<div class="reh">
		<div class="rex" onclick="hf()">x</div>
		<div id="ret">'.$ret.'</div>
	</div>
	<ul class="ulc" id="ulrec">'.$list.'</ul>
	<div class="ulc_bg" id="ulc_bg" onclick="hf('.$id.','.$id.')"></div>
	<div class="recbox" id="recbox">
		<input type="hidden" id="re_id" value="'.$id.'">
		<textarea class="recom" id="recom" placeholder="回复 '.$nickname0.'"></textarea>
		<input type="button" class="recombtn" id="recombtn" value="'.$btn.'" onclick="hf('.$id.',\'\',\'go\')">
	</div>';
	return $xxx;
}
function SbYoU_neT_comments($id=null,$page=null,$num=null){
	global $dsql,$pcbaseall;
	if(!$num){
		$num='10';
	}
	$rei_d='rei'.'d';
	$uidc=session('pboot_ucode');
	if($uidc){
		$x=$dsql->one("select id from ay_member where ucode=$uidc limit 1",1);
		$uidc=$x[id];
	}
	!$page?$page=1:'';
	$pagenum=$page+1;
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$num;
	$lang=session('lang');
	if(defined('LANG') || ($lang && $lang!='cn')){
		if(defined('LANG')){
			$lang=LANG;
		}
		$sql="and update_user='$lang'";
	}else{
		$sql="and update_user=''";
	}
	$dd=$dsql->all("select id from ay_member_comment where status=1 and contentid=$id $sql order by id desc limit $offset,$num",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id_=$xx[id];
		$xx=$dsql->one("select comment,name,create_time,uid from ay_member_comment where id=$id_ limit 1",1);
		$name=$xx[name];
		$comment=$xx[comment];
		$create_time=$xx[create_time];
		$uid=$xx[uid];
		$nickname='';
		if($uid){
			$x=$dsql->one("select headpic,nickname,username from ay_member where id=$uid limit 1",1);
			$headpic=$x[headpic];
			$nickname=$x[nickname];
			$headpic=ico($headpic,1);
			if(!$nickname){
				$nickname=$x[username];
			}
		}else{
			$x=rand(1,100);
			$headpic=$pcbaseall."uploads/empty/".$x.".gif";
		}
		if(!$nickname){
			if(!$name){
				$name='游客';
			}
			$nickname=$name;
		}
		$x=explode(' ',$create_time);
		$create_time=$x[0];
		$recom_='';
		$x=$dsql->one("select count(*) from ay_member_comment_re where status=1 and id=$id_",1);
		$count=$x['count(*)'];
		if(!$count){
			$count='';
		}else{
			$x=$dsql->one("select * from ay_member_comment_re where status=1 and id=$id_ and re_id=0 order by $rei_d desc limit 1",1);
			$comment_=$x[comment];
			$uid_=$x[uid];
			if($uid_){
				$x=$dsql->one("select username,nickname from ay_member where id=$uid_ limit 1",1);
				$nickname_=$x[nickname];
				if($nickname_){
					$nickname_=$x[username];
				}
			}else{
				$nickname_='游客';
			}
			if($count>1){
				$ck='<font class="f2" onclick="hf('.$id_.')">查看全部'.$count.'条回复</font>';
			}else{
				$ck='';
			}
			$recom_='<span><font>'.$nickname_.'</font>：'.$comment_.$ck.'</span>';
		}
		if($uidc==$uid){
			$del='<div class="redel" id="redel'.$id_.'" onclick="hf('.$id_.','.$id_.',\'del\')">x</div>';
		}else{
			$del='';
		}
		$xx_list.='
		<li>
		  <div class="pic"><img src="'.$headpic.'" /></div>
		  <div class="info">'.$nickname.'&nbsp;'.$create_time.'</div>
		  <div class="words">
			<p>'.$comment.'</p>
		  </div>
		  <div class="cbtn">
			  <div class="hf" onclick="hf('.$id_.')">'.$count.'回复 &gt;</div>
			  '.$del.'
		  </div>
		  <div class="words words2">
			<p>'.$recom_.'</p>
		  </div>
		</li>';
	}
	if($ii<$num){
		$pagenum=$page;
		$nextpg=$page;
	}
	!$pagenum?$prepg="":"";
	if($prepg){
	  $pre='<a href="javascript:commentNow(\''.$id.'\',\''.$prepg.'\',\'yes\');" title="上一页">&lt;</a>';
	  $first='<a href="javascript:commentNow(\''.$id.'\',\'1\',\'yes\');" title="第一页">&lt;&lt;</a>';
	}else{
	  $pre='<a class="no" title="已经是第一页了">&lt;</a>';
	  $first='<a class="no" title="没有上一页">&lt;&lt;</a>';
	}
	if($nextpg){
	  $next='<a href="javascript:commentNow(\''.$id.'\',\''.$nextpg.'\',\'yes\');" title="下一页">&gt;</a>';
	  $last='<a href="javascript:commentNow(\''.$id.'\',\''.$pagenum.'\',\'yes\');" title="最后一页">&gt;&gt;</a>';
	}else{
	  $next='<a class="no" title="没有下一页">&gt;</a>';
	  $last='<a class="no" title="已经是最后一页了">&gt;&gt;</a>';
	}
	$pageNav='
	<div class="cm">
	  <div class="i">每页显示<b>'.$num.'</b>条</div>
	  <div class="n">
		<div><img src="'.$pcbaseall.'template/tpl/image/loading.gif" id="cIMG" /></div>
		<div><a class="info">第<b>'.$page.'</b>页</a>'.$first.$pre.'</div>
		<div>'.$next.$last.'</div>
	  </div>
	</div>';
	$xxx='
	<ul class="ulc" id="ulc">
	'.$xx_list.'
	</ul>
	'.$pageNav;
	if(!$ii){
		$xxx='
		<ul class="ulc" id="ulc">
		  <br />
		  <br />
		  &nbsp;::&nbsp;暂无评论...快来抢占头条吧！-_-
		  <br />
		  <br />
		  <br />
		</ul>
		<div class="cm">
		  <div class="i">每页<b>'.$num.'</b>条</div>
		  <div class="n">
			<div><img src="'.$pcbaseall.'template/tpl/image/loading.gif" id="cIMG" /></div>
			<div><a class="info">第<b>0</b>页</a><a class="no" title="已经是第一页了">&lt;&lt;</a><a class="no" title="没有上一页">&lt;</a></div>
			<div><a class="no" title="没有下一页">&gt;</a><a class="no" title="已经是最后一页了">&gt;&gt;</a></div>
		  </div>
		</div>';
	}
	return $xxx;
}
function insertComment($id=null,$num=null){
	global $dsql,$DISPLAY_COM,$AUTO_COM;
	if($DISPLAY_COM!='1' || $AUTO_COM=='0'){
		return false;
	}
	if(!$num){
		$num=1;
	}
	$lang=session('lang');
	if(defined('LANG') || ($lang && $lang!='cn')){
		if(defined('LANG')){
			$lang=LANG;
		}
		$update_user=$lang;
		$txtn='_'.$lang;
		$sql=" and update_user='$lang'";
	}else{
		$update_user='';
		$txtn='';
		$sql=" and update_user=''";
	}
	$name=ROOT_PATH.'/include/name'.$txtn.'.txt';
	if(!file_exists($name)){
		return false;
	}
	$comment=ROOT_PATH.'/include/comment'.$txtn.'.txt';
	$name=xxx_url($name);
	$comment=xxx_url($comment);
	$nameArr=explode(PHP_EOL,$name);
	$nameArr=array_filter($nameArr);
	$commentArr=explode(PHP_EOL,$comment);
	$commentArr=array_filter($commentArr);
	shuffle($nameArr);
	shuffle($commentArr);
	for($i=0;$i<$num;$i++){
		$name=$nameArr[ $i ];
		$comment=$commentArr[ $i ];
		$name=trim($name);
		$comment=trim($comment);
		$name=str_replace("'","\'",$name);
		$comment=str_replace("'","\'",$comment);
		$t=get_datetime();
		$x=rand(1,3);
		if($x==1){
			$dsql->amd("insert into ay_member_comment (contentid,name,comment,status,create_time,update_time,update_user,uid,puid,user_ip,user_os,user_bs) values ('$id','$name','$comment','1','$t','$t','$update_user','0','0','','','')");
		}else{
			$x=$dsql->one("select id from ay_member_comment where contentid=$id $sql order by id desc limit 1",1);
			$id_=$x[id];
			if($id_){
				$x=$i+1;
				$comment=$commentArr[ $x ];
				$dsql->amd("insert into ay_member_comment_re (id,re_id,comment,uid,likes,oppose,status,update_time) values ('$id_',0,'$comment',0,0,0,1,'$t')");
			}
		}
	}
	return true;
}
function reseturl($my=null,$url=null,$id=null,$page=null,$pw=null,$dt=null){
	global $dsql,$PC_URL,$WAP_URL,$pcbase,$pcbaseall,$REARR,$AUTO_STATIC;
	if(!$my || !$id || $AUTO_STATIC!='2' || !$REARR){
		return $url;
	}
	if($my!='category' && $my!='book' && $my!='chapter' && $my!='article' && $my!='content'){
		return $url;
	}
	if(strstr($url,'://')){
		$wapbaseall=wapbase('all');
		if(strstr($url,$WAP_URL)){
			$url_=$WAP_URL;
		}else if(strstr($url,$wapbaseall)){
			$url_=$wapbaseall;
		}else if(strstr($url,$PC_URL)){
			$url_=$PC_URL;
		}else{
			$x=explode('://',$url);
			$x1=$x[0];
			$x2=$x[1];
			$x=explode('/',$x2);
			$x3=$x[0];
			$url_=$x1.'://'.$x3.'/';
		}
	}else{
		$wapbase=wapbase();
		if(strstr($url,$wapbase)){
			$url_=$wapbase;
		}else if(strstr($url,$pcbase)){
			$url_=$pcbase;
		}else{
			$url_='';
		}
	}
	if($my=='category'){
		if($dt){
			if(!$pw){
				$url__='?'.$id.'_'.$page.'/';
			}else{
				$url__='?'.$id.'_'.$page.'/';
			}
		}else{
			if(!$page || $page==1){
				$page=1;
				$url__=$REARR[$my.'1'];
			}else{
				$url__=$REARR[$my.'2'];
			}
			$url__=str_replace('{栏目ID}',$id,$url__);
			$url__=str_replace('{页码}',$page,$url__);
			if(strstr($url__,'{栏目URL名称}')){
				$x=$dsql->one("select filename from ay_content_sort where scode=$id limit 1",1);
				$filename=$x[filename];
				$url__=str_replace('{栏目URL名称}',$filename,$url__);
			}
		}
	}else if($my=='book' || $my=='chapter'){
		if(!strstr($id,'x')){
			if(!$page){
				$page=1;
			}
			if($my=='book'){
				$url__=$REARR[$my];
			}else{
				if($page==1){
					$url__=$REARR[$my.'1'];
				}else{
					$url__=$REARR[$my.'2'];
				}
			}
			$url__=str_replace('{小说ID}',$id,$url__);
			$url__=str_replace('{页码}',$page,$url__);
			if(strstr($url__,'{小说URL名称}') || strstr($url__,'{栏目URL名称}') || strstr($url__,'{栏目ID}')){
				$x=explode('_',$id);
				$x=$x[0];
				$x=$dsql->one("select scode,filename from ay_content where id=$x limit 1",1);
				$scode=$x[scode];
				$filename=$x[filename];
				$url__=str_replace('{栏目ID}',$scode,$url__);
				$url__=str_replace('{小说URL名称}',$filename,$url__);
				if(strstr($url__,'{栏目URL名称}')){
					$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
					$filename_=$x[filename];
					$url__=str_replace('{栏目URL名称}',$filename_,$url__);
				}
			}
		}
	}else if($my=='article'){
		$x=explode('_',$id);
		$x=$x[0];
		$x=$dsql->one("select id from ay_article where xid=$x limit 1",1);
		$id_=$x[id];
		$url__=$REARR[$my];
		$url__=str_replace('{小说ID}',$id_,$url__);
		$url__=str_replace('{章节ID}',$id,$url__);
		if(strstr($url__,'{小说URL名称}') || strstr($url__,'{栏目URL名称}') || strstr($url__,'{栏目ID}')){
			$x=$dsql->one("select scode,filename from ay_content where id=$id_ limit 1",1);
			$scode=$x[scode];
			$filename=$x[filename];
			$url__=str_replace('{栏目ID}',$scode,$url__);
			$url__=str_replace('{小说URL名称}',$filename,$url__);
			if(strstr($url__,'{栏目URL名称}')){
				$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
				$filename_=$x[filename];
				$url__=str_replace('{栏目URL名称}',$filename_,$url__);
			}
		}
	}else if($my=='content'){
		if(!$page){
			$page=1;
		}
		if($page>1){
			$url__=$REARR[$my.'2'];
		}else{
			$url__=$REARR[$my.'1'];
		}
		$url__=str_replace('{内容ID}',$id,$url__);
		$url__=str_replace('{页码}',$page,$url__);
		if(strstr($url__,'{栏目URL名称}') || strstr($url__,'{栏目ID}')){
			$x=explode('_',$id);
			$x=$x[0];
			$x=$dsql->one("select scode from ay_content where id=$x limit 1",1);
			$scode=$x[scode];
			$url__=str_replace('{栏目ID}',$scode,$url__);
			if(strstr($url__,'{栏目URL名称}')){
				$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
				$filename_=$x[filename];
				$url__=str_replace('{栏目URL名称}',$filename_,$url__);
			}
		}
	}
	if($url__){
		$url=$url_.$url__;
	}
	return $url;
}
function read_xxx($idx=null){
	global $dsql,$pay_appid,$read_xxx,$jinbi_xxx,$read_mode;
	if(!$idx){
		return false;
	}
	$x=session('read_xxx_'.$idx);
	if($x){
		return false;
	}else{
		session('read_xxx_'.$idx,'1');
	}
	$uid=session('pboot_ucode');
	if($pay_appid && $read_mode && $read_xxx && $jinbi_xxx){
		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
		if(file_exists($x)){
			$xid=$idx;
			$x=$dsql->one("select id,status from ay_article where xid=$xid limit 1",1);
			$id=$x[id];
			$status=$x[status];
			if(!$id || !$status){
				return false;
			}
		}else{
			$id=$idx;
		}
		$x=$dsql->one("select uid,status,mcode from ay_content where id=$id limit 1",1);
		$uid_=$x[uid];
		$status_=$x[status];
		$mcode_=$x[mcode];
		$x=checkuid($mcode_,$id,'','');
		$arr=explode('{697}',$x);
		$tip_=$arr[1];
		if($tip_){
			return false;
		}
		if($uid_ && $status_==1 && $uid!=$uid_){
			$x=$dsql->one("select ucode,read_xxx,sign,status from ay_member where ucode=$uid_ limit 1",1);
			$o=$x[ucode];
			$read_xxx_=$x[read_xxx];
			$sign_=$x[sign];
			$status_=$x[status];
			if($o && $status_==1 && $sign_==3){
				if(strstr($jinbi_xxx,',')){
					$x=explode(',',$jinbi_xxx);
					$jb1=$x[0];
					$jb2=$x[1];
					$jinbi_xxx_=rand($jb1,$jb2);
				}else{
					$jinbi_xxx_=$jinbi_xxx;
				}
				if($read_mode==1){
					if($read_xxx_>=$read_xxx){
						$dsql->amd("update ay_member set jinbi=jinbi+$jinbi_xxx_,read_xxx=0 where ucode=$uid_ limit 1");
					}else{
						$dsql->amd("update ay_member set read_xxx=read_xxx+1 where ucode=$uid_ limit 1");
					}
				}else if($read_mode==2){
					$time=time();
					$x=$dsql->one("select last_read_time from ay_member where ucode=$uid limit 1",1);
					$last_read_time=$x[last_read_time];
					$last_read_time=strtotime($last_read_time);
					$x=$time-$last_read_time;
					if($x<=0){
						$jia=$time-($last_read_time-180);
						$x=$dsql->one("select read_xxx from ay_member where ucode=$uid_ limit 1",1);
						$read_xxx_=$x[read_xxx];
						$read_xxx_=$read_xxx_+$jia;
						$read_xxx__=$read_xxx*60;
						if($read_xxx_<$read_xxx__){
							$dsql->amd("update ay_member set read_xxx=$read_xxx_ where ucode=$uid_ limit 1");
						}else{
							$dsql->amd("update ay_member set jinbi=jinbi+$jinbi_xxx_,read_xxx=0 where ucode=$uid_ limit 1");
						}
					}
					$t=$time+180;
					$time_=date('Y-m-d H:i:s',$t);
					$dsql->amd("update ay_member set last_read_time='$time_' where ucode=$uid limit 1");
				}
			}
		}
	}
	$xxx='ok';
	return $xxx;
}
function randnum($num=null){
	for($i=1;$i<=$num;$i++){
		$x01=rand(0,9);
		$x02=chr(rand(65,90));
		$x03=chr(rand(97,122));
		$x=rand(1,3);
		$xx='x0'.$x;
		$xxx.=$$xx;
	}
	return $xxx;
}
function repjin($xxx=null){
	global $dsql;
	if(!$xxx){
		return false;
	}
	$dd=$dsql->all("select id from ay_jin order by id desc limit 100000",1);
	foreach($dd as $xx){
		$id_=$xx[id];
		$xx=$dsql->one("select `key` from ay_jin where id=$id_ limit 1",1);
		$key=$xx[key];
		$arr=explode(',',$key);
		$arr_=$arr;
		foreach($arr as $xx_){
			$xx_=trim($xx_);
			if(!$xx_){
				continue;
			}
			shuffle($arr_);
			$xx__=$arr_[0];
			$xxx=str_replace($xx_,$xx__,$xxx);
		}
	}
	return $xxx;
}
function reptags($xxx=null){
	global $dsql;
	if(!$xxx){
		return false;
	}
	$arr=array();
	$dd=$dsql->all("select id from ay_tags order by id desc limit 100000",1);
	foreach($dd as $xx){
		$ii++;
		$id_=$xx[id];
		$xx=$dsql->one("select name,link from ay_tags where id=$id_ limit 1",1);
		$name=$xx[name];
		$link=$xx[link];
		$xx_=trim($name);
		$xx__='<a href="'.$link.'" title="{'.$ii.'}" target="_blank">'.$xx_.'</a>';
		$xxx=str_replace($xx_,$xx__,$xxx);
		$arr[ $ii ]=$xx_;
	}
	foreach($arr as $k => $v){
		$xx_='{'.$k.'}';
		$xx__=$v;
		$xxx=str_replace($xx_,$xx__,$xxx);
	}
	return $xxx;
}
function xxxaxd($id=null,$hash=null,$more=null){
	global $dsql,$xxx_time,$xxx_jinbi,$xxx_top,$pay_ready;
	if($more=='dis' || !$xxx_time || !$xxx_jinbi){
		session('xxx_hash','');
		session('xxx_id','');
		if($more){
			$xxx='dis';
		}
	}else if($more){
		$uid=session('pboot_ucode');
		$xxx_id=session('xxx_id');
		$xxx_id_=md5($xxx_id);
		if($uid && $xxx_id && $xxx_id_==$more && $xxx_jinbi){
			$x=$dsql->one("select id from ay_content where status=1 order by rand() limit 1",1);
			$id_=$x[id];
			if(!$id_){
				session('xxx_hash','');
				session('xxx_id','');
				$xxx='dis';
			}else{
				$t_=date('Ymd');
				$x=$dsql->one("select xxxaxd from ay_member where ucode=$uid limit 1",1);
				$xxxaxd=$x[xxxaxd];
				$x=explode(',',$xxxaxd);
				$t=$x[0];
				$n=$x[1];
				if(!$n){
					$n=0;
				}
				if(!$t || $t==$t_){
					$n_=$n+$xxx_jinbi;
					if($n_>$xxx_top){
						$n_=$xxx_top;
						$jinbi=$xxx_top-$n;
					}else{
						$jinbi=$xxx_jinbi;
					}
				}else{
					$n_=$xxx_jinbi;
					$jinbi=$xxx_jinbi;
				}
				if($jinbi<0){
					$jinbi=0;
				}
				$xxxaxd=$t_.','.$n_;
				$dsql->amd("update ay_member set jinbi=jinbi+$jinbi,xxxaxd='$xxxaxd' where ucode=$uid limit 1");
				$xxx='ok';
				$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
				if(file_exists($x)){
					$x='book';
				}else{
					$x='content';
				}
				if(is_mobile()){
					$url=pbcode($x,$id_,'','','','','wap');
				}else{
					$url=pbcode($x,$id_);
				}
				$xxx.='{x}'.$url;
			}
		}else{
			session('xxx_hash','');
			session('xxx_id','');
			$xxx='dis';
		}
	}else{
		$xxx_hash_=session('xxx_hash');
		if(!$xxx_hash_){
			if(!$id || !$hash){
				return false;
			}
			$hash_=md5($id.$xxx_jinbi);
			if($hash_!=$hash){
				return false;
			}
			session('xxx_hash',1);
			$xxx_hash_=session('xxx_hash');
		}
		if($xxx_hash_){
			$uid=session('pboot_ucode');
			if(!$uid){
				session('xxx_hash','');
				session('xxx_id','');
				$xxx='login';
				$url=pbcode('member','login','','all');
				$xxx.='{x}'.$url;
			}else{
				if($id && $hash){
					$xxx='ok';
				}else{
					if(is_mobile()){
						$adtype=2;
						$ad_xxx='手机版';
					}else{
						$adtype=1;
						$ad_xxx='电脑版';
					}
					$xxx_id=session('xxx_id');
					if(!$xxx_id){
						$x=$dsql->one("select id,adbody from ay_ad where adstatus=1 and adtype=$adtype and adname like '%看广告赚金币%' order by id asc limit 1",1);
						$xxx_id=$x[id];
						$xxx_adbody=$x[adbody];
					}else{
						$x=$dsql->one("select id,adbody from ay_ad where adstatus=1 and adtype=$adtype and adname like '%看广告赚金币%' and id>$xxx_id order by id asc limit 1",1);
						$xxx_id=$x[id];
						$xxx_adbody=$x[adbody];
						if(!$xxx_id){
							$x=$dsql->one("select id,adbody from ay_ad where adstatus=1 and adtype=$adtype and adname like '%看广告赚金币%' order by id asc limit 1",1);
							$xxx_id=$x[id];
							$xxx_adbody=$x[adbody];
						}
					}
					if(!$xxx_adbody){
						$xxx_id='';
					}
					$t_=date('Ymd');
					$x=$dsql->one("select xxxaxd,jinbi from ay_member where ucode=$uid limit 1",1);
					$xxxaxd=$x[xxxaxd];
					$jinbi=$x[jinbi];
					$x=explode(',',$xxxaxd);
					$t=$x[0];
					$n=$x[1];
					if($t==$t_){
						if($n==$xxx_top || $n>$xxx_top){
							$xxx_top_=1;
						}
					}
					if(!$xxx_id || !strstr($pay_ready,'697code.com') || $xxx_top_){
						session('xxx_hash','');
						session('xxx_id','');
						$xxx_jinbi=0;
						if(!strstr($pay_ready,'697code.com')){
							$xxx_adbody='暂未开放看广告赚金币功能！';
						}else if($xxx_top_){
							$xxx_adbody='今日奖励'.$n.'金币，已达上限，明日再继续吧！';
						}else{
							$xxx_adbody='暂未开放“'.$ad_xxx.'”的看广告赚金币功能！';
						}
					}else{
						session('xxx_id',$xxx_id);
						$xxx_id_=md5($xxx_id);
					}
					$xxx_time=str_replace('，',',',$xxx_time);
					$arr=explode(',',$xxx_time);
					$x1=$arr[0];
					$x2=$arr[1];
					if($x1 && $x2){
						$xxx_time_=rand($x1,$x2);
					}else{
						$xxx_time_=$x1;
					}
					if(!$xxx_time_ || !is_numeric($xxx_time_)){
						$xxx_time_=5;
					}
					$url=pbcode('member','money','','all');
					$xxx='
					<div class="axd_free" id="axd_free">
						<div class="axd_bg"></div>
						<div class="axd_box">'.$xxx_adbody.'</div>
						<div class="axd_tbox">
							广告
							<span id="st">'.$xxx_time_.'</span>
							秒后自动获得
							<span>'.$xxx_jinbi.'</span>
							金币
						</div>
						<div class="axd_tbox axd_close" onclick="xxxaxd(\'\',\'dis\')" id="axd_close">关闭</div>
						<a class="axd_tbox axd_close axd_jb" href="'.$url.'" title="我的金币" target="_blank">我的金币:'.$jinbi.'</a>
					</div>
					<script>
					var xxx_go=1;
					sectime();
					setTimeout(func'.'tion'.'(){
						if(xxx_go){
							xxxaxd("","'.$xxx_id_.'");
						}
					}, '.($xxx_time_*1000).');
					</script>';
				}
			}
		}
	}
	return $xxx;
}
function lang($key=null){
	global $lang;
	if($lang){
		$f=ROOT_PATH.'/include/lang.txt';
		$h=xxx_url($f);
		$arr=explode("\n",$h);
		foreach($arr as $xx){
			$xx=trim($xx);
			if(!$xx){
				continue;
			}
			if(strstr($xx,$key.',')){
				$arr2=explode(',',$xx);
				foreach($arr2 as $xx2){
					$x='{'.$lang.'}';
					if(strstr($xx2,$x)){
						$xxx=str_replace($x,'',$xx2);
						break;
					}
				}
				break;
			}
		}
		if(!$xxx){
			$xxx=$key;
		}
	}else{
		$xxx=$key;
	}
	return $xxx;
}
function lang_content($content=null,$lang=null,$wap=null){
	global $dsql,$pcbase,$pcbaseall,$wapbase,$langArr;
	if($langArr){
		if((defined('LANG') && LANG && LANG!='cn') || ($lang && $lang!='cn')){
			if(defined('LANG')){
				$lang=LANG;
			}
			$arr=array('<base href="'.$pcbase.'" />',$pcbaseall.'core',$pcbaseall.'app',$pcbaseall.'uploads',$pcbaseall.'static',$pcbase.'uploads',$pcbase.'static',$pcbaseall.'template',$pcbase.'template','<base href="'.$wapbase.'" />',$wapbase.'style',$pcbase.'include');
			foreach($arr as $xx){
				$xx_=str_replace('/lang/'.$lang.'/','/',$xx);
				$content=str_replace($xx,$xx_,$content);
			}
			if(defined('IS_WAP') || $wap){
				$f=ROOT_PATH.'/wap/style/image/logo_'.$lang.'.png';
				if(file_exists($f)){
					$content=str_replace('style/image/logo.png','style/image/logo_'.$lang.'.png',$content);
				}
				$content=str_replace('href="./"','href="'.$wapbase.'"',$content);
			}else{
				$f=ROOT_PATH.'/template/tpl/image/logo_'.$lang.'.png';
				if(file_exists($f)){
					$content=str_replace('template/tpl/image/logo.png','template/tpl/image/logo_'.$lang.'.png',$content);
				}
				$content=str_replace('href="./"','href="'.$pcbase.'"',$content);
				$content=str_replace("'hi.login.php","'$pcbase"."hi.login.php",$content);
				$content=str_replace('wap/','wap/lang/'.$lang.'/',$content);
			}
			$content=str_replace('/lang/'.$lang.'/lang/'.$lang.'/','/lang/'.$lang.'/',$content);
			if($lang=='ft'){
				$content=jt2ft($content);
			}else{
				$dd=$dsql->all("select id from ay_lang where lang_xx='$lang' order by len desc",1);
				foreach($dd as $xx){
					$id=$xx[id];
					$xx=$dsql->one("select key1,key2 from ay_lang where id=$id limit 1",1);
					$key1=$xx[key1];
					$key2=$xx[key2];
					$content=str_replace($key1,$key2,$content);
				}
			}
		}
		$content=str_replace(array('/lang//','/lang/cn/'),'/',$content);
	}
	return $content;
}
function langbtn($lang_xx=null,$href=null,$wap=null){
	global $langArr,$pcbaseall;
	if($lang_xx && $href){
		if(!$langArr){
			$xxx=$pcbaseall;
		}else{
			session('lang',$lang_xx);
			if($lang_xx=='cn'){
				$x='/lang/'.$lang_xx.'/';
				$xxx=preg_replace('/\/lang\/.*?\//','/',$href);
			}else{
				$x='/lang/'.$lang_xx.'/';
				if(strstr($href,'lang')){
					$xxx=preg_replace('/\/lang\/.*?\//',$x,$href);
				}else{
					if(!$wap){
						$url=$pcbaseall;
					}else{
						$url=wapbase('all');
					}
					$url=preg_replace('/\/lang\/.*?\//','/',$url);
					$x=ltrim($x,'/');
					$xxx=str_replace($url,$url.$x,$href);
				}
			}
		}
	}else{
		if(!$langArr){
			$xxx='<script>var jslang="";</script>';
			return $xxx;
		}
		$lang=session('lang');
		if(!$lang || $lang=='cn'){
			$c=' class="cur"';
		}else{
			$c='';
		}
		$url='./lang/cn/';
		$xxx='<a'.$c.' href="'.$url.'" onclick="return Lang(\'cn\');">'.lang('中文').'</a>';
		foreach($langArr as $k => $v){
			if(defined('IS_WAP')){
				$f=ROOT_PATH.'/wap/lang/'.$k.'/index.php';
			}else{
				$f=ROOT_PATH.'/lang/'.$k.'/index.php';
			}
			if(!file_exists($f)){
				$c2='no';
			}else{
				$c2='';
			}
			if($lang==$k){
				$c='cur';
			}else{
				$c='';
			}
			if($c2){
				$c.=' '.$c2;
				$url='javascript:';
			}else{
				$url='lang/'.$k.'/';
			}
			$xxx.='<a class="'.$c.'" href="'.$url.'" onclick="return Lang(\''.$k.'\',\''.$c2.'\');">'.lang($v).'</a></li>';
		}
		if(defined('IS_WAP')){
			$src='style/image/lang.png';
			$yy=lang('语言');
		}else{
			$src='template/tpl/image/lang.png';
			$yy=lang('选择语言');
		}
		$xxx='
		<div class="lengbtnbox">
			<script>var jslang="'.$lang.'";</script>
			<div class="lbb1">
				<img id="lbb1img" src="'.$src.'" onerror="this.style.display=\'none\';" alt="'.lang('选择语言').'">
				'.$yy.'
			</div>
			<div class="lbb2">'.$xxx.'</div>
		</div>';
	}
	return $xxx;
}
function jt2ft($jt=null,$ft=true){
	global $big5;
	require_once(dirname(__FILE__).'/big5.php');
	if($jt && $big5){
		if(!$ft){
			$map=array_flip($big5);
		}else{
			$map=$big5;
		}
		$xxx=strtr($jt,$map);
	}else{
		$xxx=$jt;
	}
	return $xxx;
}
function langnav($my=null,$lang=null,$xxx=null){
	global $dsql,$langArr,$dbArr;
	if(!$my || !$langArr){
		return $xxx;
	}
	if($my=='site'){
		foreach($langArr as $k => $v){
			if($k=='ft'){
				continue;
			}
			if($lang==$k){
				$c=' class="layui-this"';
			}else{
				$c='';
			}
			$xxx.='<li'.$c.'><a href="?p=/Site/index&lang='.$k.'">'.$v.'</a></li>';
		}
		if($lang){
			$arr=array('title','subtitle','keywords','description','copyright');
			foreach($arr as $xx){
				$x_=$xx.'_'.$lang;
				$x_=$dbArr[ $x_ ];
				$dbArr[ $xx ]=$x_;
			}
		}
	}else if($my=='content_sort'){
		$scode=$dbArr->scode;
		foreach($langArr as $k => $v){
			if($k=='ft'){
				continue;
			}
			if($lang==$k){
				$c=' class="layui-this"';
			}else{
				$c='';
			}
			$xxx.='<li'.$c.'><a href="?p=/ContentSort/mod/scode/'.$scode.'&lang='.$k.'">'.$v.'</a></li>';
		}
		if($lang){
			$arr=array('name','title','keywords','description');
			foreach($arr as $xx){
				$x_=$xx.'_'.$lang;
				$x_=$dbArr->$x_;
				$dbArr->$xx=$x_;
			}
		}
	}else if($my=='content'){
		$idcur=$dbArr[id];
		foreach($langArr as $k => $v){
			if($k=='ft'){
				continue;
			}
			if($lang==$k){
				$c=' class="layui-this"';
			}else{
				$c='';
			}
			$xxx.='<li'.$c.'><a href="?p=/Sort/edit/id/'.$idcur.'&lang='.$k.'">'.$v.'</a></li>';
		}
		if($lang){
			$arr=array('title','keywords','description','content');
			foreach($arr as $xx){
				$x_=$xx.'_'.$lang;
				$x_=$dbArr[ $x_ ];
				if($xx=='content'){
					$x_=Content_Body($x_);
				}
				$dbArr[ $xx ]=$x_;
			}
		}
	}else if($my=='article'){
		$idcur=$dbArr[xid];
		foreach($langArr as $k => $v){
			if($k=='ft'){
				continue;
			}
			if($lang==$k){
				$c=' class="layui-this"';
			}else{
				$c='';
			}
			$xxx.='<li'.$c.'><a href="?p=/Sort/edit/xid/'.$idcur.'&lang='.$k.'">'.$v.'</a></li>';
		}
		if($lang){
			$arr=array('xtitle','content');
			foreach($arr as $xx){
				$x_=$xx.'_'.$lang;
				$x_=$dbArr[ $x_ ];
				if($xx=='content'){
					$x_=sbyouNet_Body($idcur,$x_);
				}
				$dbArr[ $xx ]=$x_;
			}
		}
	}
	return $xxx;
}
function Content_Body($content=null){
	global $CHAPTER_HTMLTXT;
	$content=trim($content);
	if(!$content){
		return false;
	}
	$strlen=strlen($content);
	if($strlen=='46' || $strlen=='35' || $strlen=='48'){
		if($CHAPTER_HTMLTXT){
			$x=rtrim($CHAPTER_HTMLTXT,'/');
			$txt=$x.'/dynamic/htmltxt';
		}else{
			$txt=ROOT_PATH.'/dynamic/htmltxt';
		}
		$txt.='/'.$content.'.txt';
		if(file_exists($txt)){
			$content=xxx_url($txt);
			if(!mb_check_encoding($content,'UTF-8')){
				$encoding=mb_detect_encoding(
					$content,
					array('GB18030','GBK','GB2312'),
					true
				);
				if($encoding){
					$content=iconv($encoding,'UTF-8//IGNORE',$content);
				}
			}
			$content=preg_replace('/^\xEF\xBB\xBF/','',$content);
		}
	}
	return $content;
}function usedbtitle(){
	global $PC_URL,$USE_MINE;
	if($USE_MINE){
		$arr=explode('//',$PC_URL);
		$x=$arr[1];
		$arr=explode('/',$x);
		$x=$arr[0];
		$xxx=str_replace('.','_',$x);
	}
	return $xxx;
}
function js_write($content=null){
	global $relog;
	if($relog==''){
		echo "document.write('".str_replace(array("\n","\r"),' ',addslashes($content))."');";
	}else{
		echo str_replace('\"','',str_replace(array("\n","\r"),' ',addslashes($content)));
	}
}
function SByou_net_subscode($num=null){
	global $dsql;
	if(!$num){
		$num=1;
	}
	$idArray=array();
	$dd=$dsql->all("select id from ay_content force index(idx_subscode) where status=1 and subscode=0 order by id desc limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$x=$dsql->one("select xid from ay_article where status=1 and id=$id and content like '%http%' limit 1",1);
		$xid=$x[xid];
		if($xid){
			continue;
		}
		$idArray[]=$id;
	}
	$numID=count($idArray);
	if(!$numID){
		return false;
	}
	for($i=0;$i<$numID;$i++){
		$curID=$idArray[ $i ];
		$www_sbyou_net=0;
		$dd=$dsql->all("select xid,content from ay_article where status=1 and id=$curID",1);
		foreach($dd as $xx)
		{
			$wei='yes';
			$xid=$xx[xid];
			$content=$xx[content];
			$www_sbyou_net.=sbyouNet_Body($xid,$content,'','nop');
		}
		if($wei=='yes'){
			$subscode=strlen($www_sbyou_net)/2;
			if($subscode){
				$dsql->amd("update ay_content set subscode='$subscode' where id='$curID' limit 1");
			}
		}else{
			$dsql->amd("delete from ay_content where id=$curID limit 1");
		}
	}
}
function sbyou_NET_newList($num=null){
	global $dsql;
	if(!$num){
		$num=10;
	}
	$dd=$dsql->all("select xid,id,xtitle from ay_article order by xid desc limit $num",1);
	foreach($dd as $xx)
	{
		$xid=$xx[xid];
		$id=$xx[id];
		$xtitle=$xx[xtitle];
		$x=$dsql->one("select title,scode,update_time from ay_content where id=$id limit 1",1);
		$title=$x[title];
		$scode=$x[scode];
		$update_time=$x[update_time];
		if(!$scode){
			continue;
		}
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x['name'];
		$url1=pbcode('category',$scode);
		$url2=pbcode('book',$id);
		$url3=pbcode('article',$xid);
		$xxx.='
		<tr>
			<td class="nline" width="10%" align="center">[<a href="'.$url1.'" title="'.$name.'" target="_blank">'.$name.'</a>]</td>
			<td class="nline"><a href="'.$url2.'" title="'.$title.'" target="_blank">'.$title.'</a></td>
			<td class="nline"><a href="'.$url3.'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a></td>
			<td class="nline">'.$update_time.'</td>
		</tr>';
	}
	return $xxx;
}
function xxx_SingleReaded($my=null,$cookie=null,$uid=null,$username=null,$register_time=null){
	global $dsql;
	if($my=='SingleCookie'){
		$cookie=explode(',',$cookie);
		$id=$cookie[0];
		$arr=explode('-',$id);
		$id=$arr[0];
		if(is_numeric($id)){
			$x=$dsql->one("select title from ay_content where id=$id limit 1",1);
			$xxx=$x[title];
		}
	}else{
		$x=$dsql->one("select ucode,username,register_time from ay_member where ucode='$uid' limit 1",1);
		$uid_2=$x['ucode'];
		$username_2=$x['username'];
		$register_time_2=$x['register_time'];
		if($uid!=$uid_2 || $username!=$username_2 || $register_time!=$register_time_2){
			$cookie=explode(',',$cookie);
			$id=$cookie[0];
			$arr=explode('-',$id);
			$id=$arr[0];
			if(is_numeric($id)){
				$x=$dsql->one("select title from ay_content where id=$id limit 1",1);
				$xxx=$x[title];
			}
		}else{
			$x=$dsql->one("select www_669977_net_look from ay_member_data where uid='$uid' limit 1",1);
			$data=$x['www_669977_net_look'];
			if(!$data){
				$cookie=explode(',',$cookie);
				$id=$cookie[0];
				$arr=explode('-',$id);
				$id=$arr[0];
				if(is_numeric($id)){
					$x=$dsql->one("select title from ay_content where id=$id limit 1",1);
					$xxx=$x[title];
				}
			}else{
				$data_Array=explode(',',$data);
				$id=$data_Array[0];
				$arr=explode('-',$id);
				$id=$arr[0];
				if(is_numeric($id)){
					$x=$dsql->one("select title from ay_content where id=$id limit 1",1);
					$xxx=$x[title];
				}
			}
		}
	}
	if(!$xxx){
		$xxx='<p>暂无记录</p>';
	}
	return $xxx;
}
function xxx_MoreReaded($my=null,$cookie=null,$uid=null,$username=null,$register_time=null){
	global $dsql;
	if($my=='MoreCookie'){
		$array=array();
		$arr=explode(',',$cookie);
		foreach ($arr as $key => $value){
			$arrCur=explode('-',$value);
			$x01=$arrCur[0];
			$x02=$arrCur[1];
			if(array_key_exists($x01,$array)){
				continue;
			}else{
				$array[ $x01 ]=$x02;
			}
		}
		foreach ($array as $key => $value){
			$curBid=$key;
			$curAid=$value;
			if(!$curBid){
				continue;
			}
			$sbyou_Net.=ReadedStyle($curBid,$curAid);
		}
		$SbyouNet=$sbyou_Net;
	}else{
		$sbyou_net=$dsql->one("select ucode,username,register_time from ay_member where ucode='$uid' limit 1",1);
		$uid_2=$sbyou_net['ucode'];
		$username_2=$sbyou_net['username'];
		$register_time_2=$sbyou_net['register_time'];
		if($uid!=$uid_2 || $username!=$username_2 || $register_time!=$register_time_2){
			$cookie=explode('-',$cookie);
			$bid=$cookie[0];
			if(is_numeric($bid)){
				$x=$dsql->one("select name from ay_content_sort where scode=$bid limit 1",1);
				$SbyouNet=$x[title];
			}
		}else{
			$sbyou_net=$dsql->one("select www_669977_net_look from ay_member_data where uid='$uid' limit 1",1);
			$data=$sbyou_net['www_669977_net_look'];
			if(!$data){
				if($cookie){
					$dsql->amd("update ay_member_data set www_669977_net_look='$cookie' where uid='$uid' limit 1");
				}
				$array=array();
				$arr=explode(',',$cookie);
				foreach ($arr as $key => $value){
					$arrCur=explode('-',$value);
					$x01=$arrCur[0];
					$x02=$arrCur[1];
					if(array_key_exists($x01,$array)){
						continue;
					}else{
						$array[ $x01 ]=$x02;
					}
				}
				foreach ($array as $key => $value){
					$curBid=$key;
					$curAid=$value;
					if(!$curBid){
						continue;
					}
					$sbyou_Net.=ReadedStyle($curBid,$curAid);
				}
				$SbyouNet=$sbyou_Net;
			}else{
				$array=array();
				$arr=explode(',',$data);
				foreach ($arr as $key => $value){
					$arrCur=explode('-',$value);
					$x01=$arrCur[0];
					$x02=$arrCur[1];
					if(array_key_exists($x01,$array)){
						continue;
					}else{
						$array[ $x01 ]=$x02;
					}
				}
				foreach ($array as $key => $value){
					$curBid=$key;
					$curAid=$value;
					if(!$curBid){
						continue;
					}
					$sbyou_Net.=ReadedStyle($curBid,$curAid);
				}
				$SbyouNet=$sbyou_Net;
			}
		}
	}
	if(!$SbyouNet){
		$SbyouNet='<p>暂无记录</p>';
	}
	return $SbyouNet;
}
function ReadedStyle($curBid=null,$curAid=null){
	global $dsql;
	if(!$curBid || !is_numeric($curBid)){
		return false;
	}
	$x=$dsql->one("select id,title,author from ay_content where id='$curBid' limit 1",1);
	$id=$x['id'];
	$title=$x['title'];
	$author=$x['author'];
	!$author?$author='暂未统计':'';
	$x=$dsql->one("select name from ay_content_sort where scode=$id limit 1",1);
	$name=$x[name];
	$name=str_replace('·','',$name);
	$x=$dsql->one("select title from ay_content where id='$curAid' limit 1",1);
	$title=$x['title'];
	if($title){
		$curAidTT='<a href="'.pbcode('article',$curAid).'" target="_blank">'.$title.'</a>';
		$curAidTT.='<a class="t" href="'.pbcode('article',$curAid).'#ting" target="_blank">(<img src="template/tpl/image/ts.png" />听书)</a>';
	}else{
		$curAidTT='<p>暂无记录</p>';
	}
	$sbyou_net='
	<li>
		<span class="s1"><a href="'.pbcode('category',$scode).'" target="_blank">'.$name.'</a></span>
		<span class="s2"><a id="title'.$curBid.'" href="'.pbcode('book',$curBid).'" target="_blank">'.$title.'</a></span>
		<span class="s3">'.$curAidTT.'</span>
		<span class="s4">'.$author.'</span>
		<span class="s5" id="actionBox'.$curBid.'"><a href="javascript:delbookhis('.$curBid.');" onclick="return confirm(\'确定要将本书移除吗？\')">删除</a></span>
	</li>';
	return $sbyou_net;
}
function sbyouNet_Body($id=null,$content=null,$js=null,$yesp=1){
	global $USE_MINE,$USE_DIR,$LOCK_ME,$LOCK_WORD,$CHAPTER_HTMLTXT;
	if($content){
		$strlen=strlen($content);
		if($strlen=='46' || $strlen=='35' || $strlen=='48'){
			$content=Content_Body($content);
			if($content){
				$content=Rules4allbook($content);
			}
		}else if(strstr($content,'{697}')){
			if($CHAPTER_HTMLTXT){
				$x=rtrim($CHAPTER_HTMLTXT,'/');
				$contentdir=$x;
			}else{
				$contentdir=ROOT_PATH;
			}
			$x=str_replace('{697}','',$content);
			$x=explode(',',$x);
			$articleid=$x[0];
			$chapterid=$x[1];
			$fx=$articleid/1000;
			$fx=floor($fx);
			$content=$contentdir.'/files/article/txt/'.$fx.'/'.$articleid.'/'.$chapterid.'.txt';
			$content=xxx_url($content);
			if(!mb_check_encoding($content,'UTF-8')){
				$encoding=mb_detect_encoding(
					$content,
					array('GB18030','GBK','GB2312'),
					true
				);
				if($encoding){
					$content=iconv($encoding,'UTF-8//IGNORE',$content);
			}
			}
			$content=preg_replace('/^\xEF\xBB\xBF/','',$content);
			if($content){
				$content=Rules4allbook($content);
			}
		}else{
			if(strstr($content,'http')){
				$content=Rules4TXT($id,$content,$js);
				$arr=explode('{697}',$content);
				$content=$arr[0];
				$yesp=$arr[1];
			}else{
				$content=Rules4allbook($content);
			}
		}
	}else{
		if($LOCK_ME && $LOCK_WORD){
			$content=$LOCK_WORD;
		}else{
			$content='内容缺失，稍后重试...';
		}
	}
	$content=content4ting($content,$yesp);
	return $content;
}
function Rules4TXT($id=null,$content=null,$js=null){
	global $dsql,$isonline,$AUTO_STATIC,$pcbaseall;
	if($isonline=='1' && $js && $AUTO_STATIC!='1'){
		$src=$pcbaseall.'template/tpl/image/loading.gif';
		$content='
		<p style="font-size:14px"><img id="url2disk" style="vertical-align:baseline" src="'.$src.'" />精彩内容，立马呈现...</p>
		<script type="text/javascript">url2disk('.$id.');</script>';
		$content.='{697}nop';
		return $content;
	}
	$x=str_replace(array('http://','https://','//'),'',$content);
	$arr=explode('/',$x);
	$x00=$arr[0];
	$rules=ROOT_PATH.'/cj/Rules/'.$x00.'.txt';
	if(file_exists($rules)){
		$rules=xxx_url($rules);
		if($rules){
			$glgz=strstr($rules,'#过滤规则');
			$x=str_replace($glgz,'',$rules);
			$ppgz=strstr($x,'#匹配规则(697code.com)');
			$ymbm=str_replace(array($ppgz,'#页面编码'),'',$x);
			$ymbm=trim($ymbm);
			$ppgz=str_replace('#匹配规则(697code.com)','',$ppgz);
			$ppgz=trim($ppgz);
			$glgz=str_replace('#过滤规则','',$glgz);
			$glgz=trim($glgz);
			$arr=explode('[内容]',$ppgz);
			$x01=$arr[0];
			$x02=$arr[1];
			require_once(ROOT_PATH.'/cj/include/cj.fun.php');
			$content=trim($content);
			$content=cj_url($content);
			$content=xxxrep($content,$x01,$x02,$glgz);
			if($content){
				if($isonline=='1'){
					me2disk($id,$content);
				}
			}else{
				$dsql->amd("update ay_article set cjid=1 where xid=$id limit 1");
				$content='内容丢失，稍后重试...';
			}
		}else{
			$content='规则获取失败，稍后重试...';
		}
	}else{
		$content='规则丢失，稍后重试...';
	}
	return $content;
}
function Rules4allbook($content=null){
	$rules=ROOT_PATH.'/cj/Rules/allbook.txt';
	if(file_exists($rules)){
		$rules=xxx_url($rules);
		$rules=str_replace('#过滤规则','',$rules);
		if($rules){
			$rules=str_replace("\n",PHP_EOL,$rules);
			$arr=explode(PHP_EOL,$rules);
			foreach ($arr as $key => $value){
				$value=trim($value);
				if(!$value){
					continue;
				}
				$value=str_replace('/','\/',$value);
				$content=preg_replace('/'.$value.'/','',$content);
			}
		}
	}
	return $content;
}
function mysql2disk($num=null){
	global $dsql;
	if(!$num){
		$num=25;
	}
	$dd=$dsql->all("select xid from ay_article where status=1 and cjid=0 order by xid desc limit $num",1);
	foreach($dd as $xx)
	{
		$xid=$xx[xid];
		me2disk($xid);
	}
}
function me2disk($xid=null,$content=null){
	global $dsql;
	if(!$xid){
		return false;
	}
	$time=get_datetime();
	$time=strtotime($time);
	if(!$content){
		$x=$dsql->one("select xid,content from ay_article where xid=$xid limit 1",1);
		$o=$x['xid'];
		$content=$x['content'];
		if(!$o){
			return false;
		}
		$content=sbyouNet_Body($xid,$content);
	}
	if(!$content || strstr($content,'稍后重试...')){
		$dsql->amd("update ay_article set cjid=1 where xid=$xid limit 1");
		return false;
	}
	$strlen=strlen($content);
	if($strlen=='46' || $strlen=='35' || $strlen=='48'){
		$dsql->amd("update ay_article set cjid=1 where xid=$xid limit 1");
		return true;
	}
	if(strstr($content,'手打') || strstr($content,'请稍等片刻')){
	}
	$USE_DIR=USE_DIR();
	$dbcontent=date('Y',$time).'/'.date('m',$time).'/'.date('d',$time).'/'.date('H',$time);
	$dir='dynamic/htmltxt/'.$dbcontent;
	$arr=explode('/',$dir);
	$fileCur=$USE_DIR;
	for($iii=0;$iii<count($arr);$iii++){
		$fileCur.='/'.$arr[ $iii ];
		mkdir($fileCur);
	}
	$dbcontent.='/'.md5($xid);
	$file=$USE_DIR.'dynamic/htmltxt/'.$dbcontent.'.txt';
	file_put_contents($file,$content);
	$dsql->amd("update ay_article set content='$dbcontent',cjid=1 where xid=$xid limit 1");
}
function pc2wap($my=null){
	global $template,$pcbase;
	if(strstr($template,'category')){
		global $scode,$page;
		$xxx=pbcode('category',$scode,$page,'all','','','wap');
	}else if(strstr($template,'book') || strstr($template,'chapter')){
		global $id;
		$x=str_replace('.html','',$template);
		$xxx=pbcode($x,$id,'','all','','','wap');
	}else if(strstr($template,'article')){
		global $xid;
		$xxx=pbcode('article',$xid,'','all','','','wap');
	}else{
		$xxx=wapbase('all');
	}
	return $xxx;
}
function my4meta(){
	global $dsql,$sitetitle,$template,$AUTO_STATIC,$pc2url,$HTTP_HOST;
	global $PC_URL,$MOBILE_URL,$TOUCH_URL,$WAP_URL,$pcbaseall;
	if($AUTO_STATIC=='0' || $AUTO_STATIC=='2'){
		$XXX_URL=$pcbaseall;
	}else{
		$XXX_URL=$PC_URL;
	}
	$URL=$WAP_URL;
	if(!$x2x && $pc2url){
		$md=maindomain();
		if(!$md){
			$wapurl=$pc2url[ $HTTP_HOST ];
			if($wapurl){
				$arr=explode('//',$PC_URL);
				$x=$arr[1];
				$arr=explode('/',$x);
				$x=$arr[0];
				$x=$pc2url[ $x ];
				$URL=str_replace($x,$wapurl,$WAP_URL);
			}
		}
	}
	if(strstr($template,'category')){
		global $scode,$name,$page;
		$URLCur=pbcode('category',$scode,$page,'all','','','wap');
		$url=pbcode('category',$scode,$page,'all');
$sbyou_Net='
	<meta title="applicable-device" content="pc" />
	<meta title="mobile-agent" content="format=html5;url='.$URLCur.'" />
	<meta title="mobile-agent" content="format=xhtml;url='.$URLCur.'" />
	<meta title="author" content="'.$sitetitle.'" />
	<meta property="og:type" content="novel" />
	<meta property="og:title" content="'.str_replace('·','',$name).'" />
	<meta property="og:url" content="'.$url.'" />
';
	}else if(strstr($template,'book') || strstr($template,'chapter')){
		global $id,$name,$title,$description,$visits,$author,$subscode,$over,$subtitle,$ico;
		$ico=ico($ico,1);
		if($subtitle){
			$overTT='已完本';
		}else{
			$overTT='连载中';
		}
		$URLCur=pbcode('book',$id,'','all','','','wap');
		$url=pbcode('book',$id,'','all');
		global $lastid,$lasttitle,$update_time;
		if(!$lastid){
			$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
			$lastid=$x[xid];
			$lasttitle=$x[xtitle];
			$x=$dsql->one("select update_time from ay_content where id=$id limit 1",1);
			$update_time=$x[update_time];
		}
		if($lastid){
			$url_chapter=pbcode('article',$lastid,'','all');
		}
		$url_author=pbcode('author',$author,'','all');
$sbyou_Net='
	<meta title="applicable-device" content="pc" />
	<meta title="mobile-agent" content="format=html5;url='.$URLCur.'" />
	<meta title="mobile-agent" content="format=xhtml;url='.$URLCur.'" />
	<meta title="author" content="'.$sitetitle.'" />
	<meta property="og:type" content="novel" />
	<meta property="og:title" content="'.$title.'" />
	<meta property="og:description" content="'.strip_tags($description).'" />
	<meta property="og:image" content="'.$ico.'" />
	<meta property="og:novel:category" content="'.str_replace('·','',$name).'" />
	<meta property="og:novel:author" content="'.$author.'" />
	<meta property="og:novel:book_title" content="'.$title.'" />
	<meta property="og:novel:read_url" content="'.$url.'" />
	<meta property="og:url" content="'.$url.'" />
	<meta property="og:novel:status" content="'.$overTT.'" />
	<meta property="og:novel:update_time" content="'.$update_time.'" />
	<meta property="og:novel:visits_cnt" content="'.$visits.'" />
	<meta property="og:novel:latest_chapter_title" content="'.$lasttitle.'" />
	<meta property="og:novel:latest_chapter_url" content="'.$url_chapter.'" />
	<meta property="og:novel:author_link" content="'.$url_author.'" />
';
	}else if(strstr($template,'article')){
		global $xid;
		$URLCur=pbcode('article',$xid,'','all','','','wap');
$sbyou_Net='
	<meta title="applicable-device" content="pc" />
	<meta title="mobile-agent" content="format=html5;url='.$URLCur.'" />
	<meta title="mobile-agent" content="format=xhtml;url='.$URLCur.'" />
	<meta title="author" content="'.$sitetitle.'" />
';
	}else{
		global $scode,$WAP_URL;
		$URLCur=$WAP_URL;
$sbyou_Net='
	<meta title="applicable-device" content="pc" />
	<meta title="mobile-agent" content="format=html5;url='.$URLCur.'" />
	<meta title="mobile-agent" content="format=xhtml;url='.$URLCur.'" />
	<meta title="author" content="'.$sitetitle.'" />
';
	}
	$sbyou_Net=ltrim($sbyou_Net,PHP_EOL);
	$sbyou_Net=ltrim($sbyou_Net,'	');
	return $sbyou_Net;
}
function SByou_Net_NumCoMain($nid=null,$mcodeid=null,$obj=null){
	global $dsql;
	if($nid && $mcodeid && $obj){
		if($obj=='NumYes'.$mcodeid.$nid){
			$sbyou_net=$dsql->one("select count(*) from ay_697_hash where nid='$nid' and status=1",1);
			$sbyouNet=$sbyou_net['count(*)'];
		}
		if($obj=='NumNo'.$mcodeid.$nid){
			$sbyou_net=$dsql->one("select count(*) from ay_697_hash where nid='$nid' and status=1",1);
			$sbyouNet=$sbyou_net['count(*)'];
		}
	}
	if(!$sbyouNet){
		$sbyouNet='0';
	}
	return $sbyouNet;
}
function www_669977_NET_category(){
	global $dsql,$scode;
	$dd=$dsql->all("select scode,name from ay_content_sort where status=1 order by sorting asc,id asc",1);
	foreach($dd as $xx)
	{
		$scodecur=$xx[scode];
		$name=$xx[name];
		if($scodecur==$scode){
			$class=' class="cur"';
		}else{
			$class='';
		}
		$SByou_Net.='<a'.$class.' href="'.pbcode('category',$scodecur).'" title="'.$name.'">'.$name.'</a>';
	}
	return $SByou_Net;
}
function category4left(){
	global $dsql,$scode,$template;
	if(strstr($template,'quanben')){
		$quanben=' class="current"';
	}
	if(strstr($template,'shuku')){
		$shuku=' class="current"';
	}
	$xxx='
	<li><a'.$shuku.' href="'.pbcode('shuku').'">全部小说</a></li>
	<li><a'.$quanben.' href="'.pbcode('quanben').'">完本小说</a></li>';
	$dd=$dsql->all("select scode,name from ay_content_sort where status=1 order by sorting asc,id asc",1);
	foreach($dd as $xx)
	{
		$scodecur=$xx[scode];
		$name=$xx[name];
		$name=str_replace('·','',$name);
		if($scodecur==$scode){
			$class=' class="current"';
		}else{
			$class='';
		}
		$xxx.='<li><a'.$class.' href="'.pbcode('category',$scodecur).'" title="'.$name.'">'.$name.'</a></li>';
	}
	return $xxx;
}
function sbyou_net_index_tuijian($scode=null,$num=null){
	global $dsql;
	if($scode){
		$scode_str='and id='.$scode;
	}else{
		$scode_str='';
	}
	if(!$num){
		$num='8';
	}
	$dd=$dsql->all("select id,scode,title,author,ico from ay_content where status=1 $scode_str order by likes desc limit $num",1);
	foreach($dd as $xx)
	{
		$wei_i++;
		$id=$xx[id];
		$scode=$xx[scode];
		$title=$xx[title];
		$author=$xx[author];
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$name=substr_both($name,0,2);
		$ico=$xx[ico];
		$ico=ico($ico);
		if($wei_i<=2){
			if($wei_i==1){
				$cc=' class="f"';
			}else{
				$cc='';
			}
			$up.='<a'.$cc.' href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" />'.$title.'</a>';
		}else{
			$down.='<li><i class="num'.($wei_i-2).'">'.($wei_i-2).'</i><span> [<a href="'.pbcode('category',$scode).'" title="'.$name.'">'.$name.'</a>] </span><a class="t" href="'.pbcode('book',$id).'" target="_blank" title="'.$title.'">'.$title.'</a><a class="author" href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a></li>';
		}
	}
	$wWw_669977_NET='<div class="picbox">'.$up.'</div><div class="list"><ul>'.$down.'</ul></div>';
	return $wWw_669977_NET;
}
function xxx_OverTJ($scode=null,$num=null){
	global $dsql;
	if($scode){
		$scode_str='and id='.$scode;
	}else{
		$scode_str='';
	}
	if(!$num){
		$num='8';
	}
	$dd=$dsql->all("select id,scode,title,author,ico from ay_content where status=1 and subtitle='1' $scode_str order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$wei_i++;
		$id=$xx[id];
		$scode=$xx[scode];
		$title=$xx[title];
		$author=$xx[author];
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$name=substr_both($name,0,2);
		$ico=$xx[ico];
		$ico=ico($ico);
		if($wei_i<=2){
			if($wei_i==1){
				$cc=' class="f"';
			}else{
				$cc='';
			}
			$up.='<a'.$cc.' href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" />'.$title.'</a>';
		}else{
			$down.='<li><i class="num'.($wei_i-2).'">'.($wei_i-2).'</i><span> [<a href="'.pbcode('category',$scode).'" title="'.$name.'">'.$name.'</a>] </span><a class="t" href="'.pbcode('book',$id).'" target="_blank" title="'.$title.'">'.$title.'</a><a class="author" href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a></li>';
		}
	}
	$wWw_669977_NET='<div class="picbox">'.$up.'</div><div class="list"><ul>'.$down.'</ul></div>';
	return $wWw_669977_NET;
}
function newlist($scode=null,$page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL,$LOCK_ME,$pay_ready;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM2=30;
	}else{
		$OnePAGE_NUM2=$OnePAGE_NUM;
	}
	if($scode){
		$wheresql="and scode=$scode";
	}else{
		$wheresql='';
	}
	$dd=$dsql->all("select id from ay_content force index(idx_istop) where status=1 and istop=1 $wheresql order by id desc limit 10",1);
	foreach($dd as $xx){
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select scode,title,author,update_time,gid from ay_content where id=$id limit 1",1);
		$scodecur=$xx[scode];
		$title=$xx[title];
		$author=$xx[author];
		$update_time=$xx[update_time];
		$gid=$xx[gid];
		$update_time=strtotime($update_time);
		$x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
		$name=$x[name];
		$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
		$xid=$x[xid];
		$xtitle=$x[xtitle];
		if($gid && strstr($pay_ready,'__697code.com')){
			$png='vip.png';
		}else{
			$png='ding.png';
		}
		$imgstr=' <img style="position:relative;top:2px" src="template/tpl/image/'.$png.'" />';
		$SByou_Net_List.='
		<li>
			<span class="s1">['.str_replace('·','',$name).']</span>
			<span class="s2">
				<a href="'.pbcode('book',$id).'" title="点击阅读《'.$title.'》" target="_blank">'.$title.'</a>
			</span>
			<span class="s3">
				<a class="chapter" href="'.pbcode('article',$xid).'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>
				<p class="xin" style="background:none">'.$imgstr.'</p>
			</span>
			<span class="s4"><a>'.$author.'</a></span>
			<span class="s5">'.date('m-d',$update_time).'</span>
		</li>';
	}
	$x=$OnePAGE_NUM2-$ii;
	if($x){
		$OnePAGE_NUM2=$x;
	}
	$x=$dsql->one("select count(*) from ay_content force index(15fox_com_ss) where status=1 $wheresql",1);
	$total=$x['count(*)'];
	!$page?$page=1:'';
	$pagenum=ceil($total/$OnePAGE_NUM2);
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM2;
	$dd=$dsql->all("select id from ay_content force index(669977_net_suis) where status=1 $wheresql order by update_time desc limit $offset,$OnePAGE_NUM2",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select scode,title,author,update_time from ay_content where id=$id limit 1",1);
		$scodecur=$xx[scode];
		$title=$xx[title];
		$author=$xx[author];
		$update_time=$xx[update_time];
		$update_time=strtotime($update_time);
		$x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
		$name=$x[name];
		$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
		$xid=$x[xid];
		$xtitle=$x[xtitle];
		$SByou_Net_List.='
		<li>
			<span class="s1">['.str_replace('·','',$name).']</span>
			<span class="s2">
				<a href="'.pbcode('book',$id).'" title="点击阅读《'.$title.'》" target="_blank">'.$title.'</a>
			</span>
			<span class="s3">
				<a class="chapter" href="'.pbcode('article',$xid).'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>
				<p class="xin"></p>
			</span>
			<span class="s4"><a>'.$author.'</a></span>
			<span class="s5">'.date('m-d',$update_time).'</span>
		</li>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	$px=pagestyle($pagenum,$prepg,'shuku',$scode,$nextpg,$page,$OnePAGE_NUM2);
	$px='<div class="pageBox more_new"><div class="pagelink" id="pagelink">'.$px.'</div></div>';
	$xxx='<div class="con"><ul class="ul_m_list">'.$SByou_Net_List.'</ul></div>';
	$xxx.=$px;
	return $xxx;
}
function newbook($num=null){
	global $dsql,$OnePAGE_NUM;
	if(!$num){
		$num=20;
	}
	$dd=$dsql->all("select id from ay_content where status=1 order by id desc limit 3,$num",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select title,subtitle from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$subtitle=$xx[subtitle];
		if($subtitle=='1'){
			$subtitle='完结';
		}else{
			$subtitle='连载';
		}
		$url=pbcode('book',$id);
		$xxx.='
		<li>
			<span class="num'.$ii.' numer">'.$ii.'</span>
			<span class="s2">
				<a class="chaptea" href="'.$url.'" title="最新收录小说《'.$title.'》" target="_blank">'.$title.'</a>
			</span>
			<span class="s5"><a class="numce">'.$subtitle.'</a></span>
		</li>';
	}
	return $xxx;
}
function SByou_NET_CatNewList($scode=null,$page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM=30;
	}
	if($scode){
		$wheresql="and scode=$scode";
		$my='category';
	}else{
		$wheresql='';
		$my='shuku';
	}
	$x=$dsql->one("select count(*) from ay_content force index(15fox_com_ss) where status=1 $wheresql",1);
	$total=$x['count(*)'];
	!$page?$page=1:'';
	$pagenum=ceil($total/$OnePAGE_NUM);
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select id from ay_content force index(669977_net_suis) where status=1 $wheresql order by update_time desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select title,ico from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$ico=$xx[ico];
		$ico=ico($ico);
		$SByou_Net_List.='
		<li class="item-cover">
			<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">
				<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" />
				<h3>'.$title.'</h3>
			</a>
		</li>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	$px=pagestyle($pagenum,$prepg,$my,$scode,$nextpg,$page,$OnePAGE_NUM2);
	$px='<div class="pageBox more_new"><div class="pagelink" id="pagelink">'.$px.'</div></div>';
	$xxx='<div class="con"><ul class="ul_m_list">'.$SByou_Net_List.'</ul></div>';
	$xxx.=$px;
	return $xxx;
}
function SByou_NET_QBList($scode=null,$page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL,$LOCK_ME;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM=30;
	}
	if($scode){
		$wheresql="and subtitle='1' and scode=$scode";
	}else{
		$wheresql="and subtitle='1'";
	}
	$x=$dsql->one("select count(*) from ay_content force index(idx_subtitle) where status=1 $wheresql",1);
	$total=$x['count(*)'];
	!$page?$page=1:'';
	$pagenum=ceil($total/$OnePAGE_NUM);
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select id from ay_content force index(idx_subtitle) where status=1 $wheresql order by id desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select title,ico from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$ico=$xx[ico];
		$ico=ico($ico);
		$SByou_Net_List.='
		<li class="item-cover">
			<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">
				<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" />
				<h3>'.$title.'</h3>
			</a>
		</li>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	$px=pagestyle($pagenum,$prepg,'quanben',$scode,$nextpg,$page,$OnePAGE_NUM2);
	$px='<div class="pageBox more_new"><div class="pagelink" id="pagelink">'.$px.'</div></div>';
	$xxx='<div class="con"><ul class="ul_m_list" id="booklistBox">'.$SByou_Net_List.'</ul></div>';
	$xxx.=$px;
	return $xxx;
}
function SByou_NET_TJList($scode=null,$page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM=30;
	}
	if($scode){
		$wheresql="and isrecommend=1 and scode=$scode";
	}else{
		$wheresql="and isrecommend=1";
	}
	!$page?$page=1:'';
	$pagenum=$page+5;
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select id from ay_content force index(idx_isrecommend) where status=1 $wheresql order by id desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select title,ico,author,description,update_time,subscode from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$author=$xx[author];
		$description=$xx[description];
		$update_time=$xx[update_time];
		$subscode=$xx[subscode];
		$ico=$xx[ico];
		$ico=ico($ico);
		$SByou_Net_List.='
		<div class="hot-img">
			<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="小说：'.$title.'，作者：'.$author.'"></a>
			<h2><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a><span>（'.$subscode.'字）</span></h2>
			作者：<a href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a>
			<p><a href="'.pbcode('book',$id).'" title="点击查看'.$title.'小说最新章节" target="_blank">'.$description.'</a></p>
		</div>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	if($ii<$OnePAGE_NUM){
		$pagenum=$page;
		$nextpg=$page;
	}
	!$pagenum?$prepg='':'';
	if($prepg){
		$pre='<a class="pgroup" id="pre_page" href="'.pbcode('tuijian',$scode,$prepg).'" title="上一页">上一页</a>';
		$first='<a class="first" href="'.pbcode('tuijian',$scode,'').'" title="第一页">第一页</a>';
	}else{
		$pre='<a class="none" id="pre_page" title="上一页">上一页</a>';
		$first='<a class="none" title="第一页">第一页</a>';
	}
	if($nextpg){
		$next='<a class="pgroup" id="next_page" href="'.pbcode('tuijian',$scode,$nextpg).'" title="下一页">下一页</a>';
		$last='<a class="last" href="'.pbcode('tuijian',$scode,$pagenum).'" title="最后一页">最后一页</a>';
	}else{
		$next='<a class="none" id="next_page" title="下一页">下一页</a>';
		$last='<a class="none" title="最后一页">最后一页</a>';
	}
	if($pagenum=='1'){
		$numnav='<strong>1</strong>';
	}else if($pagenum>1){
		if($page<3){
			for($i=1;$i<=(($pagenum>=3)?3:$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					if($i==1){
						$find_LastAid=0;
					}
					$numnav.='<a href="'.pbcode('tuijian',$scode,$i).'">'.$i.'</a>';
				}
			}
		}else{
			for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					$numnav.='<a href="'.pbcode('tuijian',$scode,$i).'">'.$i.'</a>';
				}
			}
		}
		$numnav.='<a>...</a>';
		if($page!=$pagenum && $page!=($pagenum-1)){
			(($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
			$find_LastAid=($page_big-1)*$OnePAGE_NUM-1;
			$numnav.='<a href="'.pbcode('tuijian',$scode,$page_big).'">'.$page_big.'</a>';
		}
	}
	$www_weizugu_com='
	<div class="pagelink" id="pagelink">
	  <em id="pagestats">'.$page.'/'.$pagenum.'</em>
	  '.$first.$pre.'
	  '.$numnav.'
	  '.$next.$last.'
	</div>';
	return '<div class="item-index">'.$SByou_Net_List.'</div><div class="pages1 more_new">'.$www_weizugu_com.'</div>';
}
function SByou_NET_AuthorList($scode=null,$page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM=30;
	}
	!$page?$page=1:'';
	$pagenum=$page+5;
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select id,title,ico,author,description,update_time,subscode from ay_content where status=1 and author like '%%$scode%%' order by visits desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$title=$xx[title];
		$author=$xx[author];
		$description=$xx[description];
		$update_time=$xx[update_time];
		$subscode=$xx[subscode];
		$ico=$xx[ico];
		$ico=ico($ico);
		$SByou_Net_List.='
		<div class="hot-img">
			<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="小说：'.$title.'，作者：'.$author.'"></a>
			<h2><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a><span>（'.$subscode.'字）</span></h2>
			作者：<a href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a>
			<p><a href="'.pbcode('book',$id).'" title="点击查看'.$title.'小说最新章节" target="_blank">'.$description.'</a></p>
		</div>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	if($ii<$OnePAGE_NUM){
		$pagenum=$page;
		$nextpg=$page;
	}
	!$pagenum?$prepg='':'';
	if($prepg){
		$pre='<a class="pgroup" id="pre_page" href="'.pbcode('author',$scode,$prepg).'" title="上一页">上一页</a>';
		$first='<a class="first" href="'.pbcode('author',$scode,'').'" title="第一页">第一页</a>';
	}else{
		$pre='<a class="none" id="pre_page" title="上一页">上一页</a>';
		$first='<a class="none" title="第一页">第一页</a>';
	}
	if($nextpg){
		$next='<a class="pgroup" id="next_page" href="'.pbcode('author',$scode,$nextpg).'" title="下一页">下一页</a>';
		$last='<a class="last" href="'.pbcode('author',$scode,$pagenum).'" title="最后一页">最后一页</a>';
	}else{
		$next='<a class="none" id="next_page" title="下一页">下一页</a>';
		$last='<a class="none" title="最后一页">最后一页</a>';
	}
	if($pagenum=='1'){
		$numnav='<strong>1</strong>';
	}else if($pagenum>1){
		if($page<3){
			for($i=1;$i<=(($pagenum>=3)?3:$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					if($i==1){
						$find_LastAid=0;
					}
					$numnav.='<a href="'.pbcode('author',$scode,$i).'">'.$i.'</a>';
				}
			}
		}else{
			for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					$numnav.='<a href="'.pbcode('author',$scode,$i).'">'.$i.'</a>';
				}
			}
		}
		$numnav.='<a>...</a>';
		if($page!=$pagenum && $page!=($pagenum-1)){
			(($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
			$find_LastAid=($page_big-1)*$OnePAGE_NUM-1;
			$numnav.='<a href="'.pbcode('author',$scode,$page_big).'">'.$page_big.'</a>';
		}
	}
	$www_weizugu_com='
	<div class="pagelink" id="pagelink">
	  <em id="pagestats">'.$page.'/'.$pagenum.'</em>
	  '.$first.$pre.'
	  '.$numnav.'
	  '.$next.$last.'
	</div>';
	return '<div class="item-index">'.$SByou_Net_List.'</div><div class="pages1 more_new">'.$www_weizugu_com.'</div>';
}
function SByou_NET_AuthorALL($page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM=30;
	}
	$authorArray=array();
	$dd=$dsql->all("select author from ay_content where status=1",1);
	foreach($dd as $xx)
	{
		$authorArray[]=$xx[author];
	}
	$authorArray=array_unique($authorArray);
	$authorArray=array_values($authorArray);
	$total=count($authorArray);
	$pagenum=ceil($total/$OnePAGE_NUM);
	!$page?$page=1:'';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$toNum=$offset+$OnePAGE_NUM;
	if($toNum>$total){
		$toNum=$total;
	}
	for($i=$offset;$i<$toNum;$i++){
		$author=$authorArray[ $i ];
		$color=array('red','blue','brown','green','cornflowerblue','darkmagenta','steelblue','firebrick','fuchsia','orangered','cadetblue','mediumvioletred');shuffle($color);
		$SByou_Net_List.='<a style="color:'.$color[0].';border:1px solid '.$color[0].';" href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	!$pagenum?$prepg='':'';
	if($prepg){
		$pre='<a class="pgroup" id="pre_page" href="'.pbcode('author',$scode,$prepg).'" title="上一页">上一页</a>';
		$first='<a class="first" href="'.pbcode('author',$scode,'').'" title="第一页">第一页</a>';
	}else{
		$pre='<a class="none" id="pre_page" title="上一页">上一页</a>';
		$first='<a class="none" title="第一页">第一页</a>';
	}
	if($nextpg){
		$next='<a class="pgroup" id="next_page" href="'.pbcode('author',$scode,$nextpg).'" title="下一页">下一页</a>';
		$last='<a class="last" href="'.pbcode('author',$scode,$pagenum).'" title="最后一页">最后一页</a>';
	}else{
		$next='<a class="none" id="next_page" title="下一页">下一页</a>';
		$last='<a class="none" title="最后一页">最后一页</a>';
	}
	if($pagenum=='1'){
		$numnav='<strong>1</strong>';
	}else if($pagenum>1){
		if($page<3){
			for($i=1;$i<=(($pagenum>=3)?3:$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					if($i==1){
						$find_LastAid=0;
					}
					$numnav.='<a href="'.pbcode('author',$scode,$i).'">'.$i.'</a>';
				}
			}
		}else{
			for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					$numnav.='<a href="'.pbcode('author',$scode,$i).'">'.$i.'</a>';
				}
			}
		}
		$numnav.='<a>...</a>';
		if($page!=$pagenum && $page!=($pagenum-1)){
			(($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
			$find_LastAid=($page_big-1)*$OnePAGE_NUM-1;
			$numnav.='<a href="'.pbcode('author',$scode,$page_big).'">'.$page_big.'</a>';
		}
	}
	$www_weizugu_com='
	<div class="pagelink" id="pagelink">
	  <em id="pagestats">'.$page.'/'.$pagenum.'</em>
	  '.$first.$pre.'
	  '.$numnav.'
	  '.$next.$last.'
	</div>';
	return '<div class="AuthorALL">'.$SByou_Net_List.'</div><div class="pages1 more_new">'.$www_weizugu_com.'</div>';
}
function SbYoU_neT_footerTJ($scode=null,$num=null){
	global $dsql;
	if(!$num){
		$num='12';
	}
	if($scode){
		$wheresql="and scode=$scode";
	}else{
		$wheresql='';
	}
	$dd=$dsql->all("select id,title,filename from ay_content where status=1 $wheresql order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$title=$xx[title];
		$wwW_sbyOu_Net.='<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a>&nbsp;&nbsp;';
	}
	if($wwW_sbyOu_Net){
		return rtrim($wwW_sbyOu_Net,'&nbsp;&nbsp;').'</a>';
	}
}
function sbyou_net_top($num=null){
	global $dsql,$scode;
	if(!$num){
		$num='20';
	}
	$dd=$dsql->all("select id from ay_content where status=1 and scode=$scode order by update_time desc limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$xx=$dsql->one("select title from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$xxx.='<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a>、';
	}
	$xxx=rtrim($xxx,'、');
	return $xxx;
}
function SByou_Net_MaR($scode=null){
	global $dsql;
	if($scode){
		$wheresql="and scode=$scode";
	}else{
		$wheresql='';
	}
	$dd=$dsql->all("select id,title,ico from ay_content where status=1 $wheresql order by likes desc limit 4",1);
	foreach($dd as $xx)
	{
	  $i_txt++;
	  $id=$xx[id];
	  $title=$xx[title];
	  $ico=$xx[ico];
	  $ico=ico($ico);
	  if($i_txt==1 || $i_txt==3){
		$class_txt='f';
	}else{
		$class_txt='';
	}
	  $wwW_sbyOu_Net_txt.='<a class="'.$class_txt.'" href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'">'.$title.'</a>';
	}
	echo $wwW_sbyOu_Net_txt;
}
function sbYoU_Net_9list($id=null){
	global $dsql;
	$dd=$dsql->all("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 9",1);
	foreach($dd as $xx)
	{
	  $xid=$xx[xid];
	  $xtitle=$xx[xtitle];
	  $wwW_sbyOu_Net.='<li><a href="'.pbcode('article',$xid).'" title="'.$xtitle.'">'.$xtitle.'</a></li>';
	}
	return $wwW_sbyOu_Net;
}
function SByou_Net_PHside($scode=null,$orderby=null){
	global $dsql;
	if($scode){
		$wheresql="and scode=$scode";
	}else{
		$wheresql='';
	}
	$dd=$dsql->all("select id,title,visits,likes,author from ay_content where status=1  $wheresql order by $orderby desc limit 10",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$PHNUM=$xx[ $orderby ];
		$author=$xx[author];
		if($orderby=='likes'){
			$w='点赞';
		}else{
			$w='浏览';
		}
		$sbyou_net.='<li><i class="t1">'.$i.'.</i><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a>（<a style="color:orangered" href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a>） / '.$w.$PHNUM.'次</li>';
	}
	return $sbyou_net;
}
function Sbyou_Net_ListNav($pid=null,$num=null){
	global $dsql,$CatID,$id;
	if(!$num){
		$num='6';
	}
	if($pid=='category'){
		$w='书库';
		$pid2='shuku';
	}else if($pid=='quanben'){
		$w='全本';
	}else if($pid=='shuku'){
		$w='书库';
		$pid2='shuku';
	}else if($pid=='tuijian'){
		$w='推荐';
	}else if($pid=='likes'){
		$w='点赞';
	}else{
		$w='排行';
	}
	$dd=$dsql->all("select id,title,filename from ay_content where status=1 order by id asc",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$title=$xx[title];
		$filename=$xx[filename];
		if($CatID==$id || $id==$id){
			$class=' class="cur"';
		}else{
			$class='';
		}
		$SByou_Net.='&nbsp;/&nbsp;<a'.$class.' href="'.pbcode($pid,$id).'" title="'.$title.$w.'">'.$title.$w.'</a>';
	}
	if($pid2){
		$pid=$pid2;
	}
	if(!$CatID && !$id){
		$class=' class="cur"';
	}else{
		$class='';
	}
	if($pid=='likes'){
		$w='排行';
	}
	return '<a'.$class.' href="'.pbcode($pid,'').'" title="'.$w.'首页">'.$w.'首页</a>'.$SByou_Net;
}
function SByou_NET_PHList($scode=null,$page=null,$OnePAGE_NUM=null){
	global $dsql,$TXT_URL,$pid;
	if(!$OnePAGE_NUM){
		$OnePAGE_NUM=30;
	}
	if($scode){
		$wheresql="and scode=$scode";
	}else{
		$wheresql='';
	}
	if($pid=='likes'){
		$orderby='likes';
		$w='点赞';
	}else{
		$orderby='visits';
		$w='浏览';
	}
	!$page?$page=1:'';
	$pagenum=$page+5;
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select id,title,visits,likes,ico,author,subscode,id,update_time,description from ay_content where status=1 $wheresql order by $orderby desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$title=$xx[title];
		$author=$xx[author];
		$PHNUM=$xx[ $orderby ];
		$update_time=$xx[update_time];
		$description=$xx[description];
		$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
		$xid=$x[xid];
		$xtitle=$x[xtitle];
		$ico=$xx[ico];
		$ico=ico($ico);
		$SByou_Net_List.='
		<div class="hot-img">
			<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="小说：'.$title.'，作者：'.$author.'"></a>
			<h2><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a><span>（'.$PHNUM.$w.'）</span></h2>
			作者：<a href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a>
			<p><a href="'.pbcode('book',$id).'" title="点击查看'.$title.'小说最新章节" target="_blank">'.$description.'</a></p>
		</div>';
	}
	if(!$SByou_Net_List){
		$SByou_Net_List='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	if($ii<$OnePAGE_NUM){
		$pagenum=$page;
		$nextpg=$page;
	}
	!$pagenum?$prepg='':'';
	if($prepg){
		$pre='<a class="pgroup" id="pre_page" href="'.pbcode($pid,$scode,$prepg).'" title="上一页">上一页</a>';
		$first='<a class="first" href="'.pbcode($pid,$scode,'').'" title="第一页">第一页</a>';
	}else{
		$pre='<a class="none" id="pre_page" title="上一页">上一页</a>';
		$first='<a class="none" title="第一页">第一页</a>';
	}
	if($nextpg){
		$next='<a class="pgroup" id="next_page" href="'.pbcode($pid,$scode,$nextpg).'" title="下一页">下一页</a>';
		$last='<a class="last" href="'.pbcode($pid,$scode,$pagenum).'" title="最后一页">最后一页</a>';
	}else{
		$next='<a class="none" id="next_page" title="下一页">下一页</a>';
		$last='<a class="none" title="最后一页">最后一页</a>';
	}
	if($pagenum=='1'){
		$numnav='<strong>1</strong>';
	}else if($pagenum>1){
		if($page<3){
			for($i=1;$i<=(($pagenum>=3)?3:$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					if($i==1){
						$find_LastAid=0;
					}
					$numnav.='<a href="'.pbcode($pid,$scode,$i).'">'.$i.'</a>';
				}
			}
		}else{
			for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					$numnav.='<a href="'.pbcode($pid,$scode,$i).'">'.$i.'</a>';
				}
			}
		}
		$numnav.='<a>...</a>';
		if($page!=$pagenum && $page!=($pagenum-1)){
			(($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
			$find_LastAid=($page_big-1)*$OnePAGE_NUM-1;
			$numnav.='<a href="'.pbcode($pid,$scode,$page_big).'">'.$page_big.'</a>';
		}
	}
	$www_weizugu_com='
	<div class="pagelink" id="pagelink">
	  <em id="pagestats">'.$page.'/'.$pagenum.'</em>
	  '.$first.$pre.'
	  '.$numnav.'
	  '.$next.$last.'
	</div>';
	return '<div class="item-index">'.$SByou_Net_List.'</div><div class="pages1 more_new">'.$www_weizugu_com.'</div>';
}
function SbYOU_Net_NEW($id=null,$filename=null){
	global $dsql;
	$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
	$xid=$x[xid];
	$xtitle=$x[xtitle];
	$x=$dsql->one("select update_time from ay_content where id=$id limit 1",1);
	$update_time=$x['update_time'];
	if($id){
		if($filename=='wap'){
			$xxx='<a href="archive.php?id='.$xid.'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>';
		}else if($filename=='time'){
			$xxx=$update_time;
		}else if($filename=='title'){
			$xxx=$title;
		}else if($filename=='id'){
			$xxx=$id;
		}else{
			$xxx='<a href="'.pbcode('article',$xid).'" title="'.$xtitle.'">'.$xtitle.'</a>（'.$update_time.'）';
		}
	}else{
		$xxx='<a>暂无最新章节</a>';
	}
	return $xxx;
}
function Sbyou_net_HuanDeng(){
	global $dsql;
	$dd=$dsql->all("select id,title,description,ico from ay_content where status=1 and ico!='' order by id desc limit 15",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$description=strip_tags($xx[description]);
		$ico=$xx[ico];
		$ico=ico($ico);
		$SByou_Net.='
		<li onmouseover="this.className=\'cur\'" onmouseout="this.className=\'\'">
			<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank" class="cover"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" /></a>
			<div class="desc">
				<strong><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a></strong>
				<p>'.$description.'</p>
				<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank" class="btn">阅读&gt;&gt;</a>
			</div>
		</li>';
	}
	$SByou_Net='
	<div class="list">
	  <ul id="ulimages">'.$SByou_Net.'</ul>
	</div>
	<span class="previous" onclick="showpre();"></span>
	<span class="next" onclick="shownext();"></span>
	<ul class="page-number">
	  <li id="alink0" onclick="showList(0);" class="now">1</li>
	  <li id="alink1" onclick="showList(1);" class="">2</li>
	  <li id="alink2" onclick="showList(2);" class="">3</li>
	</ul>';
	return $SByou_Net;
}
function goodbook($num=null){
	global $dsql;
	if(!$num){
		$num='10';
	}
	$dd=$dsql->all("select id from ay_content force index(idx_visits) where status=1 order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select title,ico from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$ico=$xx[ico];
		$ico=ico($ico);
		if($ii==1){
			$class=' class="current"';
		}else{
			$class='';
		}
		$url=pbcode('book',$id);
		$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid asc limit 1",1);
		$xid=$x['xid'];
		if(!$xid){
			continue;
		}
		$url02=pbcode('article',$xid);
		$xxx.='
		<li'.$class.' onmouseover="show(1,'.$ii.')">
			<div class="app-show-title">
				<span class="num s-index-org">'.$ii.'.</span>
				<a href="'.$url.'" target="_blank" title="'.$title.'">'.$title.'</a>
			</div>
			<div class="app-show-block">
				<a href="'.$url.'" class="pic"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'"><span class="mask mask2"></span></a>
				<div>
				<a href="'.$url.'" class="event-exe-install s-index-down s-index-icon" target="_blank">阅读</a>
				<a href="'.$url02.'#ting" class="event-exe-install s-index-down s-index-icon" target="_blank">听书</a>
				</div>
			</div>
		</li>';
	}
	return $xxx;
}
function hotbook($num=null){
	global $dsql;
	if(!$num){
		$num='7';
	}
	$dd=$dsql->all("select id from ay_content force index(idx_isrecommend) where status=1 and isrecommend=1 order by id desc limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$xx=$dsql->one("select title from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$url=pbcode('book',$id);
		$xxx.='
		<li><a href="'.$url.'" target="_blank" title="'.$title.'">'.$title.'</a></li>';
	}
	return $xxx;
}
function weekhot($num=null){
	global $dsql;
	if(!$num){
		$num='9';
	}
	$dd=$dsql->all("select id from ay_content force index(idx_likes) where status=1 order by likes desc limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$xx=$dsql->one("select scode,title,author,ico,description from ay_content where id=$id limit 1",1);
		$scode=$xx[scode];
		$title=$xx[title];
		$author=$xx[author];
		$ico=$xx[ico];
		$description=$xx[description];
		$ico=ico($ico);
		$url=pbcode('book',$id);
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$xxx.='
		<div class="item">
			<div class="image">
				<a href="'.$url.'" title="《'.$title.'》在线阅读" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" /></a>
			</div>
			<dl>
				<dt><a href="'.$url.'" title="'.$title.'" target="_blank">'.$title.'</a></dt>
				<dd>'.$description.'</dd>
				<div class="state-box cf">
					<i><a href="'.pbcode('category',$scode).'">'.$name.'</a></i>
					<span class="author default">'.$author.'</span>
				</div>
			</dl>
			<div class="clear"></div>
		</div>';
	}
	return $xxx;
}
function overbook($num=null){
	global $dsql;
	if(!$num){
		$num='5';
	}
	$dd=$dsql->all("select id from ay_content where status=1 and subtitle='1' order by id desc limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$xx=$dsql->one("select title,author,ico from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$author=$xx[author];
		$ico=$xx[ico];
		$ico=ico($ico);
		$url=pbcode('book',$id);
		$xxx.='
		<li>
			<div class="book-img">
				<a href="'.$url.'" title="'.$ico.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="《'.$title.'》" /></a>
			</div>
			<a class="title" href="'.$url.'" title="'.$ico.'" target="_blank">'.$title.'</a>
			<p>'.$author.'</p>
			<a class="light-border-btn" href="'.$url.'" target="_blank">阅读小说</a>
		</li>';
	}
	return $xxx;
}
function catlist($scode=null,$num=null){
	global $dsql;
	if(!$num){
		$num='5';
	}
	$dd=$dsql->all("select id from ay_content where status=1 and scode=$scode order by update_time desc limit $num",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select title,ico,description from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$ico=$xx[ico];
		$description=$xx[description];
		$ico=ico($ico);
		$url=pbcode('book',$id);
		if($ii==1){
			$class=' class="current"';
		}else{
			$class='';
		}
		$xxx.='
		<li'.$class.' onmouseover="show('.$scode.','.$ii.')">
			<div class="app-show-title">
				<span class="num s-index-org">'.$ii.'.</span>
				<a href="'.$url.'" title="'.$title.'" target="_blank">'.$title.'</a>
			</div>
			<div class="app-show-block">
				<a class="pic" href="'.$url.'" title="'.$title.'" target="_blank">
					<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'">
					<span class="mask mask2"></span>
				</a>
				<dd>'.$description.'</dd>
			</div>
		</li>';
	}
	return $xxx;
}
function top4left($num=null){
	global $dsql,$scode,$template;
	if(!$num){
		$num='10';
	}
	if(strstr($template,'shuku')){
		$sql='';
		$force="force index(idx_visits)";
		$orderby="order by visits desc";
	}else if(strstr($template,'quanben')){
		$sql="";
		$force="";
		$orderby="order by update_time desc";
	}else{
		$sql="and scode=$scode";
		$force="force index(idx_visits)";
		$orderby="order by visits desc";
	}
	$dd=$dsql->all("select id from ay_content $force where status=1 $sql $orderby limit $num",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$xx=$dsql->one("select title from ay_content where id=$id limit 1",1);
		$title=$xx[title];
		$url=pbcode('book',$id);
		$xxx.='
		<li><a href="'.$url.'" title="'.$title.'" target="_blank">'.$title.'</a></li>';
	}
	return $xxx;
}
function new4chapter($num=null){
	global $dsql,$id;
	if(!$num){
		$num='12';
	}
	$dd=$dsql->all("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit $num",1);
	foreach($dd as $xx)
	{
		$xid=$xx[xid];
		$xtitle=$xx[xtitle];
		$url=pbcode('article',$xid);
		$xxx.='<dd><a href="'.$url.'" title="'.$xtitle.'">'.$xtitle.'</a></dd>';
	}
	return $xxx;
}
function all4chapter(){
	global $dsql,$id,$title,$LOCK_ME,$free_chapter,$tip_;
	$dd=$dsql->all("select xid,xtitle from ay_article where status=1 and id=$id order by xid asc",1);
	foreach($dd as $xx)
	{
		$ii++;
		$xid=$xx[xid];
		$xtitle=$xx[xtitle];
		$url=pbcode('article',$xid);
		if($tip_ && $ii>$free_chapter){
			$c=' class="vip"';
		}
		if($ii>99 && $LOCK_ME){
			$c=' class="lock"';
			$url='javascript:LockX(1);';
		}
		$xxx.='<li><a'.$c.' href="'.$url.'" title="'.$xtitle.'">'.$xtitle.'</a></li>';
	}
	$xxx='
	<div class="ct">'.$title.' 小说全部目录</div>
	<ul>'.$xxx.'</ul>';
	return $xxx;
}
function SByou_Net_ReadNew($obj=null){
	global $dsql;
	if($obj=='up'){
		$sql='limit 2';
	}else{
		$sql='limit 2,9';
	}
	$dd=$dsql->all("select id,title,id,description,author from ay_content where status=1 order by id desc $sql",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$author=$xx[author];
		$description=strip_tags($xx[description]);
		$description=substr_both($description,0,45);
		$id=$xx[id];
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$name=substr_both($name,0,2);
		if($obj=='up'){
			$sbyouNet.='
			<div class="box'.$i.'">
				<div class="hup"><strong><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a></strong></div>
				<p class="pup">'.$description.'<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">阅读&gt;&gt;</a></p>
			</div>';
		}else{
			if($i==1){
				$h.='
				<div class="h3"><strong><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$authorSTR.$title.'</a></strong></div>';
			}else{
				$li.='<li><span>[<a href="'.pbcode('category',$scode).'" title="'.$name.'">'.$name.'</a>]</span><a class="t" target="_blank" href="'.pbcode('book',$id).'" title="'.$title.'">'.$title.'<em> / '.$author.'</em></a></li>';
			}
		}
	}
	if($obj=='down'){
		$sbyouNet=$h.'<ul class="read-list">'.$li.'</ul>';
	}
	return $sbyouNet;
}
function SByou_Net_QianLi($num=null){
	global $dsql;
	if(!$num){
		$num='12';
	}
	$dd=$dsql->all("select id,title,id,author from ay_content where status=1 order by id desc limit 15,$num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$id=$xx[id];
		$author=$xx[author];
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$sbyouNet.='<li><span>[<a href="'.pbcode('category',$scode).'" title="'.$name.'">'.$name.'</a>]</span><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a></li>';
	}
	return $sbyouNet;
}
function SByou_Net_BaiWan($num=null){
	global $dsql;
	if(!$num){
		$num='12';
	}
	$dd=$dsql->all("select id,title,id,subscode from ay_content where status=1 and subscode>1000000 order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$id=$xx[id];
		$subscode=$xx[subscode];
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$sbyouNet.='<li><span>[<a href="'.pbcode('category',$scode).'" title="'.$name.'">'.$name.'</a>]</span><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'<span class="red">('.$subscode.')</span></a></li>';
	}
	return $sbyouNet;
}
function SByou_Net_SHen($num=null){
	global $dsql;
	if(!$num){
		$num='10';
	}
	$dd=$dsql->all("select id,title,id,author,description,ico from ay_content where status=1 and subscode>1000000 order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$id=$xx[id];
		$author=$xx[author];
		$description=strip_tags($xx[description]);
		$description=substr_both($description,0,40);
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$ico=$xx[ico];
		$ico=ico($ico);
		if($i==1){
			$class=' class="cur"';
		}else{
			$class='';
		}
		$RR.='<li'.$class.' id="tab'.$i.'" onmouseover="ShowPic(\''.$i.'\');"><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a></li>';
		$LL.='
		<li'.$class.' id="litab_'.$i.'"><a class="pic" href="'.pbcode('book',$id).'" target="_blank" title="'.$title.'"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" /></a>
		  <p>'.$description.'<a href="'.pbcode('book',$id).'" target="_blank" title="'.$title.'" class="btn">阅读&gt;&gt;</a></p>
		</li>';
	}
	$sbyouNet='<ul class="tab" id="ShowPic_tab">'.$RR.'</ul><ul class="content">'.$LL.'</ul>';
	return $sbyouNet;
}
function xxx_Cat($id=null,$name=null){
	$sbyouNet='
	<div class="Layout caBox t15">
	  <div class="left">
		<div class="h">
		  <h3><a href="'.pbcode('category',$scode).'" title="'.$name.'" target="_blank">'.$name.'</a></h3>
		  <ul>'.xxx_CatTou($id,5).'</ul>
		</div>
		<div class="con">'.xxx_CatNew($id,4).'</div>
	  </div>
	  <div class="right">
		<div class="h">
		  <h3>'.$name.'最新更新</h3>
		</div>
		<div class="con">
		  '.xxx_CatClick($id,$name,3).'
		  <div class="box box3">
		  '.xxx_CatThumbClick($id,$name,4).'
		  </div>
		</div>
	  </div>
	</div>';
	return $sbyouNet;
}
function xxx_CatTou($id=null,$num=null){
	global $dsql;
	if(!$num){
		$num='5';
	}
	$dd=$dsql->all("select id,title from ay_content where id='$id' and status=1 order by subscode desc limit $num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$sbyouNet.='<li><a target="_blank" href="'.pbcode('book',$id).'" title="'.$title.'">'.$title.'</a></li>';
	}
	return $sbyouNet;
}
function xxx_CatNew($id=null,$num=null){
	global $dsql;
	if(!$num){
		$num='4';
	}
	$dd=$dsql->all("select id,title,ico,author,description from ay_content where id='$id' and status=1 order by id desc limit $num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$author=$xx[author];
		$description=strip_tags($xx[description]);
		$description=substr_both($description,0,40);
		$ico=$xx[ico];
		$ico=ico($ico);
		$sbyouNet.='
		<div class="box box'.$i.'">
		  <div class="pic"><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img width="100" height="128" crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" /></a></div>
		  <div class="text">
			<h3><a title="'.$title.'" target="_blank" href="'.pbcode('book',$id).'">'.$title.'</a></h3>
			<p class="author">作者：<a href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a></p>
			<p>'.$description.'<a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank" >阅读&gt;&gt;</a></p>
		  </div>
		</div>';
	}
	return $sbyouNet;
}
function xxx_CatClick($id=null,$name=null,$num=null){
	global $dsql;
	if(!$num){
		$num='3';
	}
	$dd=$dsql->all("select id,title,ico,author,description from ay_content where id='$id' and status=1 order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$author=$xx[author];
		$name=str_replace('...','',substr_both($name,0,2));
		$description=strip_tags($xx[description]);
		$description=substr_both($description,0,40);
		$ico=$xx[ico];
		$ico=ico($ico);
		if($i==1){
			$top='
			<div class="box box'.$i.'">
			  <div class="pic"><a class="cover" href="'.pbcode('book',$id).'" target="_blank" title="'.$title.'"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" /></a></div>
			  <div class="desc"><strong><a href="'.pbcode('book',$id).'" target="_blank" title="'.$title.'">'.$title.'</a></strong>
				<p class="author">作者：<a href="'.pbcode('author',$author).'" title="'.$author.'" target="_blank">'.$author.'</a></p>
				<p>'.$description.'<a class="red" href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank" >阅读&gt;&gt;</a></p>
			  </div>
			  <span class="no1"></span>
			</div>';
		}else{
			$down.='
			<li class="li'.($i-1).'"><span>[<a href="'.pbcode('category',$scode).'" title="'.$name.'">'.$name.'</a>]</span><a class="t" target="_blank" href="'.pbcode('book',$id).'" title="'.$title.'"><strong>'.$title.'</strong></a></li>';
		}
	}
	$sbyouNet=$top.'<div class="box box2"><ul>'.$down.'</ul></div>';
	return $sbyouNet;
}
function xxx_CatThumbClick($id=null,$name=null,$num=null){
	global $dsql;
	if(!$num){
		$num='3';
	}
	$dd=$dsql->all("select id,title,ico from ay_content where id='$id' and status=1 and ico!='' order by visits desc limit $num",1);
	foreach($dd as $xx)
	{
		$i++;
		$id=$xx[id];
		$title=$xx[title];
		$ico=$xx[ico];
		$ico=ico($ico);
		if($i==1){
			$class=' class="cur"';
		}else{
			$class='';
		}
		$LL.='<li'.$class.' id="litab'.$id.$i.'" onMouseMove="ShowBook(\''.$id.'\',\''.$i.'\');"><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank">'.$title.'</a></li>';
		$RR.='<li'.$class.' id="libox'.$id.$i.'"><a href="'.pbcode('book',$id).'" title="'.$title.'" target="_blank"><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'" /></a></li>';
	}
	$sbyouNet='
	<ul class="tabBox" id="tabBox'.$id.'">'.$LL.'</ul>
	<ul class="picbox">'.$RR.'</ul>';
	return $sbyouNet;
}
function ReCollectionError(){
	global $dsql;
	$dd=$dsql->all("select id from ay_content where description like '%{field:update_time}%' order by id desc limit 500",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$dsql->amd("update ay_content set description='' where id='$id' limit 1");
	}
}
function ReTypeimgError(){
	global $dsql;
	$dd=$dsql->all("select id,ico from ay_content where ico like '%http%' and isok=0 order by id desc limit 50",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$ico=$xx[ico];
		if(strstr($ico,'nocover')){
			$dsql->amd("update ay_content set ico='' where id='$id' limit 1");
		}else{
			$html=xxx_url($ico);
			if(strstr($html,'JFIF') || strstr($html,'Ducky')){
			   $dsql->amd("update ay_content set isok=1 where id='$id' limit 1");
			}else{
			   $dsql->amd("update ay_content set ico='' where id='$id' limit 1");
			}
		}
	}
}
function indexZ(){
	global $PC_URL;
	if(strstr($PC_URL,'669977.net')){
		$sbyouNet='<p class="myinfo">合作QQ：1981255858</p>';
	}else{
		$sbyouNet='';
	}
	return $sbyouNet;
}
function tingplaybox($title=null,$id=null,$xid=null,$ico=null){
	global $dsql,$pcbaseall,$template;
	if(strstr($template,'.html')){
		$class=' mini';
	}else{
		$class='';
	}
	if(!$ico){
		$x=$dsql->one("select ico from ay_content where id=$id limit 1",1);
		$ico=$x['ico'];
		$ico=ico($ico);
	}
	$xxx='
	<script>var tingapi="?speed=0.8&lang=zh-CN&speaker=";</script>
	<div class="ting-img">
		<img id="ting-img" crs="'.$ico.'" src="'.$pcbaseall.'template/tpl/image/blank.gif" onerror="this.src=&#39;'.$pcbaseall.'uploads/empty.jpg&#39;" />
		<div class="ting-play" onclick="pplay()"></div>
		<div class="ting-pause" onclick="tingcontrols(\'pause\')"></div>
	</div>
	<div class="ting-con'.$class.'">
		<div class="ting-title" id="ting-title">
			<span class="ting-s01">听书</span>
			<span class="ting-s02"> - '.$title.'</span>
		</div>
		<div class="ting-play-cur">
			<div class="ting-cur" id="ting-cur">
				<div class="ting-ico"></div>
			</div>
		</div>
		<div class="ting-time" id="ting-time">00:00 / 00:00</div>
		<div class="ting-vol">
			<p class="ting-jia" onclick="tingcontrols(\'volume\',\'1\')">+</p>
			<p class="ting-muted" id="ting-muted" onclick="tingcontrols(\'muted\')">
				<span class="ting-v-01"></span>
				<span class="ting-v-02" id="ting-v-02"></span>
			</p>
			<p class="ting-jian" onclick="tingcontrols(\'volume\',\'0\')">-</p>
		</div>
		<div class="ting-spd" id="ting-spd">
			<div>
				<script>var tex="text";</script>
				<span class="sno">语速:</span>
				<span id="spd1" onclick="tingspd(3)">慢速</span>
				<span id="spd0" onclick="tingspd(8)" class="cur">默认</span>
				<span id="spd2" onclick="tingspd(13)">快速</span>
			</div>
			<div class="spd-btn">
				<span class="spdjian" onclick="tingspd(\'\',\'jian\')">-</span>
				<span id="spd" onclick="tingspd(8)">8</span>
				<span class="spdjia" onclick="tingspd(\'\',\'jia\')">+</span>
			</div>
		</div>
		<div class="ting-autoplay" id="ting-autoplay" onclick="tingautoplay()">
			<font class="f01">自动播放</font>（<font class="f02" id="tingautoplay">×</font>）
		</div>
	</div>
	<div class="ting-control per6" id="ting-control">
		<p class="p1" onclick="tingcontrols(\'per\',\'1\')">成熟大叔</p>
		<p class="p6" onclick="tingcontrols(\'per\',\'6\')">温柔淑女</p>
		<p class="p5" onclick="tingcontrols(\'per\',\'5\')">甜美少女</p>
		<p class="p4" onclick="tingcontrols(\'per\',\'4\')">清亮青叔</p>
		<p class="p2" onclick="tingcontrols(\'per\',\'2\')">呆萌萝莉</p>
		<p class="p3" onclick="tingcontrols(\'per\',\'3\')">靓丽御姐</p>
	</div>
	<div class="tingtip" id="tingtip">
		<div class="tingh">温馨提示：</div>
		<div class="tingc" id="tingc01">是否自动播放到下一章节？</div>
		<div class="tingc" id="tingc02">立即播放当前章节？</div>
		<div class="tingb">
			<div class="tingyes" id="tingyes01" onclick="tingtip(\'go!\')">确定</div>
			<div class="tingyes" id="tingyes02" onclick="tingtip(\'play!\')">确定</div>
			<div class="tingno" onclick="tingtip(0)">取消</div>
		</div>
	</div>
	<div class="tingmark" id="tingmark"></div>
	<script>func'.'tion pplay(audio){var content=$id("bodybox").getElementsByTagName("p");var pnum=content.length;for(i=0;i<pnum;i++){var p=content[i];p.className="";}if(typeof(audio)=="undefined"||!audio){var audio=audioid();}var p=content[audio];p.className="cur";tingcontrols("play");if(typeof(hash)=="undefined"){return false;}if($id("cbox")){var bodyboxcookie=getcookie("bodyboxcookie");if(bodyboxcookie=="normal"){var winHeight=document.documentElement.clientHeight;var readyTop=(document.documentElement.scrollTop||document.bo'.'dy.scrollTop);var x01=readyTop+winHeight;var pheight=p.offsetHeight;var ptop=p.offsetTop;var x02=ptop+pheight+200;if(x02>x01){ptop=ptop;tingscroll(readyTop,ptop,"jia");}var x02=ptop+pheight+200;if(x02<readyTop){ptop=ptop;tingscroll(readyTop,ptop,"jian");}}else if(bodyboxcookie=="lr"){var l=p.offsetLeft;$id("bodybox").style.left="-"+l+"px";var w=$id("content-body").offsetWidth;var pg01=(l/w)+1;$id("pg01").innerHTML=pg01;}else if(bodyboxcookie=="ud"){var t=p.offsetTop;var h=p.offsetHeight;var hh=$id("content-body").offsetHeight;var pg01=(t+h)/hh;pg01=Math.ceil(pg01);$id("pg01").innerHTML=pg01;var top=(pg01+1)* hh;$id("bodybox").style.top="-"+top+"px";}}else{var winHeight=document.documentElement.clientHeight;var readyTop=(document.documentElement.scrollTop||document.bo'.'dy.scrollTop);var x01=readyTop+winHeight;var pheight=p.offsetHeight;var ptop=p.offsetTop;var x02=ptop+pheight+100;if(x02>x01){ptop=ptop+80;tingscroll(readyTop,ptop,"jia");}var x02=ptop+pheight+100;if(x02<readyTop){ptop=ptop+80;tingscroll(readyTop,ptop,"jian");}}}var ting_api="697";</script>';
	return $xxx;
}
function jindu($id=null,$xid=null,$wap=null){
	global $dsql;
	$arr=array();
	$dd=$dsql->all("select xid from ay_article where status=1 and id=$id order by xid asc",1);
	foreach($dd as $xx)
	{
		$xidcur=$xx[xid];
		$arr[]=$xidcur;
	}
	$total=count($arr);
	$cur=array_search($xid,$arr);
	$cur=$cur+1;
	$jdico=$cur/$total*100;
	$jdico=number_format($jdico,2);
	$p00=$arr[0];
	$p01=$arr[floor($total*0.05)-1];
	$p02=$arr[floor($total*0.5)-1];
	$p03=$arr[floor($total*0.8)-1];
	$p04=$arr[floor($total*1)-1];
	if(!$p00 || !$p01 || !$p02 || !$p03 || !$p04){
		$xxx='<style>.jindu{display:none}</style>';
		return $xxx;
	}
	if($wap){
		$url00=pbcode('article',$p00);
		$url01=pbcode('article',$p01);
		$url02=pbcode('article',$p02);
		$url03=pbcode('article',$p03);
		$url04=pbcode('article',$p04);
		$jdt='进度';
	}else{
		$url00=pbcode('article',$p00);
		$url01=pbcode('article',$p01);
		$url02=pbcode('article',$p02);
		$url03=pbcode('article',$p03);
		$url04=pbcode('article',$p04);
		$jdt='进度';
	}
	$xxx='
	<div class="jd-t"><a href="'.$url00.'" style="line-height:28px">'.$jdt.'</a></div>
	<div class="jd-box">
		<div class="jd-line" style="width:'.$jdico.'%">
			<div class="jd-ico">
				<span>'.$jdico.'%</span>
			</div>
		</div>
		<div class="jd-pre p01">
			<font></font>
			<a href="'.$url01.'">5.00%</a>
		</div>
		<div class="jd-pre p02">
			<font></font>
			<a href="'.$url02.'">50.00%</a>
		</div>
		<div class="jd-pre p03">
			<font></font>
			<a href="'.$url03.'">80.00%</a>
		</div>
		<div class="jd-pre p04">
			<font></font>
			<a href="'.$url04.'">100.00%</a>
		</div>
	</div>';
	return $xxx;
}
function PCScroll(){
	global $dsql;
	$dd=$dsql->all("select adbody from ay_ad where adstatus='1' and id=104 limit 1",1,usedbname());
	foreach($dd as $xx)
	{
		$xxx=$xx[adbody];
		$ii++;
		if($ii==1){
			preg_match_all('/src=\"(.*?)\"/',$xxx,$arr);
			$img=$arr[1][0];
			if($img){
				if(strstr($img,'http')){
					$x=explode('//',$img);
					$x=$x[1];
					$img=strstr($x,'/');
					$img=ltrim($img,'/');
				}else{
					$img=ltrim($img,'/');
				}
				if($img){
					$img=ROOT_PATH.'/'.$img;
					if(file_exists($img)){
						$info=getimagesize($img);
						$width =$info[0];
						$height=$info[1];
					}
				}
			}
		}
	}
	if($xxx){
		if(!$width){
			$width=1200;
			$height=432;
		}
		$xxx=str_replace('<a','<div class="fcon"><a',$xxx);
		$xxx=str_replace('</a>','</a></div>',$xxx);
		$xxx.='
		<script>
			document.getElementById("gallery").setAttribute("w","'.$width.'");
			document.getElementById("gallery").setAttribute("h","'.$height.'");
		</script>';
	}else{
		$xxx='<style>.huan1{display:none}</style>';
	}
	return $xxx;
}
function delbookhis($id=null,$uid=null,$username=null,$register_time=null,$cookie=null){
	global $dsql;
	$x=$dsql->one("select ucode,username,register_time from ay_member where ucode='$uid' limit 1",1);
	$uid_2=$x['uid'];
	$username_2=$x['username'];
	$register_time_2=$x['register_time'];
	if($uid!=$uid_2 || $username!=$username_2 || $register_time!=$register_time_2){
		$isnologin='1';
	}else{
		$x=$dsql->one("select www_669977_net_look from ay_member_data where uid='$uid' limit 1",1);
		$cookie=$x['www_669977_net_look'];
	}
	if($id!='all'){
		$arr=explode(',',$cookie);
		foreach ($arr as $key => $value){
			if(strstr($value,$id.'-')){
				continue;
			}
			$xxx.=$value.',';
		}
		$xxx=rtrim($xxx,',');
	}else{
		$xxx='';
	}
	if($isnologin){
		if(!$xxx){
			$xxx='delall';
		}
		return $xxx;
	}else{
		$dsql->amd("update ay_member_data set www_669977_net_look='$xxx' where uid='$uid' limit 1");
		if(!$xxx){
			$xxx='delall';
		}
		return $xxx;
	}
}
function cj2local($xid=null,$content=null){
	global $dsql;
	$needlocal='';
	$x=str_replace(array('http://','https://','//'),'',$content);
	$arr=explode('/',$x);
	$x=$arr[0];
	$rules=ROOT_PATH.'/cj/Rules/'.$x.'.txt';
	if(file_exists($rules)){
		$needlocal='1';
	}
	if(!$needlocal){
		$dsql->amd("update ay_article set cjid=1 where xid=$xid limit 1");
		$xxx='ok';
	}else{
		$content=sbyouNet_Body($xid,$content);
		if($content){
			me2disk($xid,$content);
			$xxx='ok';
		}else{
			$dsql->amd("update ay_article set cjid=1 where xid=$xid limit 1");
		}
	}
	return $xxx;
}
function url2disk($xid=null){
	global $dsql,$isonline,$free_chapter,$axd2num;
	if(!$xid){
		$xxx='数据有误，稍后重试...';
		return $xxx;
	}
	$x=$dsql->one("select id,content from ay_article where xid='$xid' limit 1",1);
	$id=$x['id'];
	$content2=$x['content'];
	$content2=sbyouNet_Body($xid,$content2);
	if($isonline=='1'){
		me2disk($xid,$content2);
		$xxx=$content2.'{697}'.$scode;
	}else{
		$xxx=$content2;
	}
	$x=checkuid(18,$id,$xid,$xxx);
	$arr=explode('{697}',$x);
	$content__=$arr[0];
	$tip__=$arr[1];
	if($tip__){
		if(!$free_chapter){
			$free_chapter=0;
		}
		$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid asc limit $free_chapter,1",1);
		$x=$x[xid];
		if($xid>=$x){
			if(strstr($tip__,'已看'.$axd2num.'个广告')){
				$content__=$content2;
				$tip__='';
			}
			$xxx=$content__;
			$xxx.=$tip__;
		}
	}
	return $xxx;
}
function url2disknext($id=null,$xid=null){
	global $dsql,$isonline;
	if(!$xid || $isonline!='1'){
		return false;
	}
	if(!is_numeric($id) || !is_numeric($xid)){
		return false;
	}
	$x=$dsql->one("select xid,content from ay_article where status=1 and id=$id and xid>$xid order by xid asc limit 1",1);
	$xid=$x[xid];
	$content=$x[content];
	if($xid && strstr($content,'//')){
		$content=sbyouNet_Body($xid,$content);
		me2disk($xid,$content);
	}
}
function reptkd($file=null,$scode=null,$id=null,$page=null,$wap=null){
	global $PC_URL,$WAP_URL,$sitetitle,$pc2url,$HTTP_HOST,$pcbaseall;
	$md=maindomain();
	if($md){
		require_once($file);
	}else{
		$xxx=xxx_url($file);
		$x01=strstr($xxx,'<title>');
		$x02=strstr($x01,'<base');
		$x00=str_replace($x02,'',$x01);
		$x00=trim($x00);
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
			$logo=ROOT_PATH.'/template/tpl/image/'.$HTTP_HOST.'.png';
		}else{
			$logo=ROOT_PATH.'/wap/style/image/'.$HTTP_HOST.'.png';
		}
		if(file_exists($logo)){
			$xxx=str_replace('logo.png',$HTTP_HOST.'.png',$xxx);
		}
		if($pc2url){
			$x02=$pc2url[ $HTTP_HOST ];
			if($x02){
				$arr=explode('//',$WAP_URL);
				$x01=$arr[1];
				$arr=explode('/',$x01);
				$x01=$arr[0];
				$xxx=str_replace($x01,$x02,$xxx);
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
			$xxx=str_replace($PC_URL,$pcbaseall,$xxx);
		}
		echo $xxx;
	}
}
function replogo($wap=null){
	global $pc2url,$x2x,$HTTP_HOST;
	$xxx='logo.png';
	if($pc2url && !$x2x){
		if(!$wap){
			$file=ROOT_PATH.'/template/tpl/image/'.$HTTP_HOST.'.png';
		}else{
			$file=ROOT_PATH.'/wap/style/image/'.$HTTP_HOST.'.png';
		}
		if(file_exists($file)){
			$xxx=$HTTP_HOST.'.png';
		}
	}
	return $xxx;
}
function contenttags($id=null,$add=null){
	global $dsql,$scode,$title,$description,$name,$author;
	if(!$add){
		$dd=$dsql->all("select tid,tag from ay_taglist where id=$id",1);
		foreach($dd as $xx)
		{
			$tid=$xx[tid];
			$tag=$xx[tag];
			$url=pbcode('tags',$tid);
			$xxx.='<a href="'.$url.'" title="'.$tag.'" target="_blank">'.$tag.'</a>、';
		}
	}
	if(!$xxx){
		global $cfg_bdqc_appid,$cfg_bdqc_apikey,$cfg_bdqc_arcretkey,$cfg_bdqc_qcnum;
		if(!$cfg_bdqc_appid || !$cfg_bdqc_apikey || !$cfg_bdqc_arcretkey){
			$url='https://suggestion.ba'.'i'.'d'.'u.com/su?wd='.$title.'&p=3';
			$x=file_get_contents($url);
			$x=iconv('GB2312','UTF-8',$x);
			$arr=explode('[',$x);
			$x=$arr[1];
			$arr=explode(']',$x);
			$x=$arr[0];
			$x=str_replace('"','',$x);
			$arr2=explode(',',$x);
		}else{
			if(!$cfg_bdqc_qcnum){
				$cfg_bdqc_qcnum='5';
			}
			require_once(ROOT_PATH.'/b'.'a'.'i'.'dua'.'pi/AipNlp.php');
			if(!$title){
				$x=$dsql->one("select scode,title,description,author from ay_content where id=$id limit 1",1);
				$scode=$x['scode'];
				$title=$x['title'];
				$description=$x['description'];
				$author=$x['author'];
				$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
				$name=$x[name];
			}
			$text=$title.','.$description.','.$name.','.$author;
			$text=strip_tags($text);
			$text=str_replace(array(PHP_EOL,' ','	','&nbsp;','$nbsp','nbsp',"\n","\r"),'',$text);
			$arr2=array();
			$client=new AipNlp($cfg_bdqc_appid,$cfg_bdqc_apikey,$cfg_bdqc_arcretkey);
			$lexer=$client->lexer($text);
			$arr=$lexer['items'];
			foreach ($arr as $key => $value){
				$length=$value['byte_length'];
				if($length<4){
					continue;
				}
				$item=$value['item'];
				if($item==$title){
					continue;
				}
				$pos=$value['pos'];
				$ne=$value['ne'];
				if($pos!='a' && $pos!='n' && $pos!='nz' && $ne!='LOC' && $ne!='ORG'){
					continue;
				}
				$item=trim($item);
				if(!$item){
					continue;
				}
				if(strstr($item,'&') || strstr($item,'%') || strstr($item,'-') || strstr($item,' ') || strstr($item,'+') || strstr($item,'//') || strstr($item,'/') || strstr($item,'.') || strstr($item,'=')){
					continue;
				}
				$ii++;
				if($ii>$cfg_bdqc_qcnum){
					break;
				}
				$arr2[]=$item;
			}
		}
		if(!$arr2){
			if($add){
				$xxx='fail';
			}else{
				$xxx='<style>.contentxx{display:none}</style>';
			}
			return $xxx;
		}
		$arr2=array_unique($arr2);
		foreach ($arr2 as $key => $value){
			$key=trim($key);
			if(!$key){
				continue;
			}
			$time=get_datetime();
			$x=$dsql->one("select id from ay_tagindex where tag='$value' limit 1",1);
			$tid=$x['id'];
			if($tid){
				$dsql->amd("update ay_tagindex set total=total+1,create_time='$time' where tag='$value' limit 1");
			}else{
				$dsql->amd("insert into ay_tagindex (tag,total,create_time) values ('$value',1,'$time')");
				$x=$dsql->one("select id from ay_tagindex where tag='$value' limit 1",1);
				$tid=$x['id'];
			}
			if($tid){
				$dsql->amd("insert into ay_taglist (tid,id,scode,tag) values ('$tid','$id','$scode','$value')");
				$xxx='ok';
			}
		}
		if(!$add){
			$xxx='';
			$dd=$dsql->all("select tid,tag from ay_taglist where id=$id",1);
			foreach($dd as $xx)
			{
				$tid=$xx[tid];
				$tag=$xx[tag];
				$url=pbcode('tags',$tid);
				$xxx.='<a href="'.$url.'" title="'.$tag.'" target="_blank">'.$tag.'</a>、';
			}
		}
	}
	if(!$add){
		$xxx=rtrim($xxx,'、');
		if(!$xxx){
			$xxx='<style>.contentxx{display:none}</style>';
		}
	}
	return $xxx;
}
function aaatags($my=null,$page=null,$ob=null,$beinum=null,$lienum=null,$duonum=null){
	global $dsql,$OnePAGE_NUM;
	!$beinum?$beinum=6:'';
	!$lienum?$lienum=4:'';
	$num=$OnePAGE_NUM*$beinum;
	$num=$num+($lienum-$num%$lienum)+$duonum;
	!$page?$page=1:'';
	$x=$dsql->one("select count(*) from ay_tagindex",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$num);
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$num;
	if($ob=='p2'){
		$x='total';
	}else if($ob=='p3'){
		$x='create_time';
	}else{
		$x='count';
	}
	$sql="select id,tag,count,total from ay_tagindex order by $x desc,id desc,id desc limit $offset,$num";
	$arr=array();
	$dd=$dsql->all($sql,1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$tag=$xx[tag];
		$count=$xx[count];
		$totalcur=$xx[total];
		if($ob){
			$n=$totalcur;
		}else{
			$n=$count;
		}
		if($n>1){
			$n='<span>('.$n.')</span>';
		}else{
			$n='';
		}
		$url=pbcode($my,$id);
		$li='<li><a href="'.$url.'">'.$tag.'</a>'.$n.'</li>';
		$arr[ $li ]=$tag;
	}
	$arr=array_unique($arr);
	foreach ($arr as $k => $v){
		$xxx.=$k;
	}
	if(!$xxx){
		$xxx='<div class="aaano">暂无内容</div>';
	}
	if($ii<$num){
		$pagenum=$page;
		$nextpg=$page;
	}
	$pg=aaapage($my,$ob,$page,$pagenum,$prepg,$nextpg);
	return $xxx.'{x}'.$pg;
}
function aaalist($my=null,$ob=null,$tag=null,$page=null){
	global $dsql,$OnePAGE_NUM;
	$num=$OnePAGE_NUM;
	!$page?$page=1:'';
	$x=$dsql->one("select count(*) from ay_taglist where tag='$tag'",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$num);
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$num;
	$arr=array();
	$dd=$dsql->all("select id from ay_taglist where tag='$tag' order by id desc limit $offset,$num",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$x=$dsql->one("select title,description from ay_content where id='$id' limit 1",1);
		$title=$x['title'];
		if(!$title){
			continue;
		}
		$description=$x['description'];
		$description=strip_tags($description);
		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
		if(file_exists($x)){
			$url=pbcode('book',$id);
		}else{
			$url=pbcode('content',$id);
		}
		$title2=str_replace($tag,'<strong>'.$tag.'</strong>',$title);
		$description2=str_replace($tag,'<strong>'.$tag.'</strong>',$description);
		$xxx.='
		<li>
			<div class="h"><a href="'.$url.'" target="_blank" title="'.$title.'">'.$title2.'</a></div>
			<div class="c">'.$description2.'</div>
		</li>';
	}
	if(!$xxx){
		$xxx='<div class="aaano">暂无内容</div>';
	}else{
		$rand=rand(1,5);
		$dsql->amd("update ay_tagindex set count=count+$rand where id='$ob' limit 1");
	}
	if($ii<$num){
		$pagenum=$page;
		$nextpg=$page;
	}
	$pg=aaapage($my,$ob,$page,$pagenum,$prepg,$nextpg);
	return $xxx.'{x}'.$pg;
}
function aaapage($my=null,$ob=null,$page=null,$pagenum=null,$prepg=null,$nextpg=null){
	!$pagenum?$prepg='':'';
	if($prepg){
		$pre='<a id="pre_page" href="'.pbcode($my,$ob,$prepg,'','').'" title="上一页">上一页</a>';
		$first='<a href="'.pbcode($my,$ob,1,'','').'" title="首页">首页</a>';
	}else{
		$pre='<a class="no" title="上一页">上一页</a>';
		$first='<a class="no" title="首页">首页</a>';
	}
	if($nextpg){
		$next='<a id="next_page" href="'.pbcode($my,$ob,$nextpg,'','').'" title="下一页">下一页</a>';
		$last='<a href="'.pbcode($my,$ob,$pagenum,'','').'" title="末页">末页</a>';
	}else{
		$next='<a class="no" title="下一页">下一页</a>';
		$last='<a class="no" title="末页">末页</a>';
	}
	$ps=$page-4;
	$pe=$page+5;
	if($page>($pagenum-5)){
		$ps=$page-(10-($pagenum-$page+1));
	}
	if($ps<1){
		$ps=1;
	}
	if($page<5){
		$pe=$pe+(10-$pe);
	}
	if($pe>$pagenum){
		$pe=$pagenum;
	}
	for($i=$ps;$i<=$pe;$i++){
		if($i==$page){
			$c=' class="cur"';
		}else{
			$c='';
		}
		$px.='<a'.$c.' href="'.pbcode($my,$ob,$i,'','').'">'.$i.'</a>';
	}
	$xxx='
	'.$first.'
	'.$pre.'
	'.$px.'
	'.$next.'
	'.$last;
	return $xxx;
}
function copycontent($code=null,$xc=null,$id=null,$wap=null){
	global $WAP_URL,$copymama,$pcbaseall;
	if($xc=='3'){
		$code=strip_tags($code);
		$code=trim($code);
		$code=str_replace('','',$code);
		if($code==$copymama){
			$xxx='yes';
		}else{
			$xxx='no';
		}
		if(!$copymama){
			$xxx='none';
		}
	}else{
		if($copymama){
			if($wap){
				if(strstr($WAP_URL,'wap1')){
					$x='wap1';
				}else{
					$x='wap';
				}
				$x=ROOT_PATH.'/'.$x.'/template/copy.html';
			}else{
				$x=ROOT_PATH.'/template/tpl/html/copy.html';
			}
			$xxx=xxx_url($x);
			$xxx=str_replace('{pcbaseall}',$pcbaseall,$xxx);
			$arr=explode('<!-- ad -->',$xxx);
			$xxx=$arr[0];
			if(strstr($pcbaseall,'669977') || strstr($pcbaseall,'697code') || strstr($pcbaseall,'pbcode') || strstr($pcbaseall,'pbcms')){
				$xxx=str_replace('class="cp-testcode"','class="cp-testcode on"',$xxx);
				$xxx=str_replace('{testcode}',$copymama,$xxx);
			}
		}else{
			$xxx='none';
		}
	}
	return $xxx;
}
function catoption($my=null,$scode=null,$id=null){
	global $dsql;
	if(!$my || $my=='book'){
		$dd=$dsql->all("select scode,name from ay_content_sort where status=1 order by sorting asc,id asc",1);
		foreach($dd as $xx)
		{
			$idc=$xx[scode];
			$name=$xx[name];
			if($my=='book'){
				if($scode==$idc){
					$s=' selected';
				}else{
					$s='';
				}
			}
			$xxx.='<option value="'.$idc.'"'.$s.'>'.$name.'</option>';
		}
	}else if($my=='article'){
		if($scode && !$id){
			$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
			$name=$x['name'];
			$xxx='<option value="'.$scode.'" selected>'.$name.'</option>';
		}else if(!$scode && $id){
			$x=$dsql->one("select title from ay_content where id=$id limit 1",1);
			$title=$x['title'];
			$xxx='<option value="'.$id.'" selected>'.$title.'</option>';
		}
	}
	return $xxx;
}
function overoption($subtitle=null){
	if($subtitle){
		$xxx='
		<option value="0">连载</option>
		<option value="1" selected>完结</option>';
	}else{
		$xxx='
		<option value="0" selected>连载</option>
		<option value="1">完结</option>';
	}
	return $xxx;
}
function yclist($id=null,$wap=null){
	global $dsql;
	$dd=$dsql->all("select xid,status,xtitle from ay_article where id=$id order by xid desc",1);
	foreach($dd as $xx)
	{
		$ii++;
		$xid=$xx[xid];
		$status=$xx[status];
		$xtitle=$xx[xtitle];
		if($status=='-2'){
			$zt='<font style="color:red">(审核不通过)</font>';
		}else if($status=='-1'){
			$zt='<font style="color:blue">(待审核)</font>';
		}else{
			$zt='';
		}
		if($wap){
			$url=pbcode('member','yc_edit_article',$id.'/'.$xid);
		}else{
			$url=pbcode('member','yc_edit_article',$id.'/'.$xid);
		}
		$xxx.='
		<div class="abox">
			<input id="ck'.$ii.'" type="checkbox" value="'.$xid.'" />
			<a href="'.$url.'" title="'.$xtitle.'">'.$zt.$xtitle.'</a>
		</div>';
	}
	if(!$xxx){
		$xxx='<p style="font-size:20px;text-align:center;line-height:150px">抱歉，暂时没有相关内容...</p>';
	}
	return $xxx;
}
function maketxt($scode=null,$id=null,$txtid=null,$total=null,$filename=null){
	global $dsql,$lang;
	$num=50;
	if(!$filename){
		$x=ROOT_PATH.'/txt/header.txt';
		$header=xxx_url($x);
		$content=$header."\n\n\n\n";
		$x=$dsql->one("select name,filename from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$filename=$x[filename];
		$x=$dsql->one("select title,author,subscode,description from ay_content where id=$id limit 1",1);
		$title=$x[title];
		$author=$x[author];
		$subscode=$x[subscode];
		$description=$x[description];
		$description=xxx_DES($description);
		$content.="书名：《".$title."》\n作者：$author\n字数：$subscode\n类型：$name\n简介：$description\n来源：".pbcode('book',$id,'','all')."\n\n\n\n";
	}
	$file=ROOT_PATH.'/txt/txt';
	mkdir($file);
	$file.='/'.$filename;
	mkdir($file);
	$file.='/'.$id.'.txt';
	if(!$txtid){
		unlink($file);
	}
	if(!$total){
		$x=$dsql->one("select count(*) from ay_article where status=1 and id=$id",1);
		$total=$x['count(*)'];
	}
	$pagenum=ceil($total/$num);
	$txtid?'':$txtid='1';
	$txtid=min($pagenum,$txtid);!$txtid?$txtid=1:"";
	$prepg=$txtid-1;
	$nextpg=($txtid==$pagenum?0:$txtid+1);
	$o=($txtid-1)*$num;
	$dd=$dsql->all("select xid,xtitle,content from ay_article where status=1 and id=$id order by xid asc limit $o,$num",1);
	foreach($dd as $xx)
	{
		$xid=$xx[xid];
		$xtitle=$xx[xtitle];
		$contentcur=$xx[content];
		$xxx.='<p>成功生成：'.$xtitle.'</p>';
		$strlen=strlen($contentcur);
		$url=pbcode('article',$xid,'','all');
		$txt=sbyouNet_Body($xid,$contentcur);
		$txt=str_replace(array('<div id="bodybox">','</div>','<p>','</p>'),"\n",$txt);
		$txt=strip_tags($txt);
		$txt=trim($txt);
		if(!$txt){
			$txt=$url;
		}
		$content.=$xtitle."\n\n".$txt."\n\n\n\n";
	}
	$x=$o+$num;
	if($x>$total || $x==$total){
		$xxx.='<!--success-->';
		$x=ROOT_PATH.'/txt/footer.txt';
		$footer=xxx_url($x);
		$content.=$footer;
	}
	$content=trim($content);
	if($content){
		if($lang=='ft'){
			$content=jt2ft($content);
		}
		file_put_contents($file,$content,FILE_APPEND);
	}
	$xxx.='{x}'.$total.'{x}'.$filename;
	return $xxx;
}
function makezip($zip=null,$txt=null,$title=null,$filename=null){
	$x=ROOT_PATH.'/txt/zip';
	mkdir($x);
	$x.='/'.$filename;
	mkdir($x);
	$z=new ZipArchive();
	if($z->open($zip,ZIPARCHIVE::OVERWRITE | ZIPARCHIVE::CREATE)!==TRUE){
		error('暂未支持ZIP',$pcbase);
		exit;
	}
	$z->addFile($txt,$title.'.txt');
	$ad=ROOT_PATH.'/txt/ad';
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
function solist($key=null,$page=null){
	global $dsql,$OnePAGE_NUM;
	$arr_title=array();
	global $langArr;
	if($langArr){
		$lang=session('lang');
		if(defined('LANG')){
			$lang=LANG;
		}
		if($lang=='cn' || $lang=='ft'){
			$key_=jt2ft($key,false);
			$title_='title';
		}else{
			$key_=$key;
			$title_='title_'.$lang;
		}
	}else{
		$key_=$key;
		$title_='title';
	}
	$sql="and $title_='$key_'";
	$dd=$dsql->all("select id from ay_content where status=1 $sql order by id asc",1);
	foreach($dd as $xx)
	{
		$ii++;
		$id=$xx[id];
		$xx=$dsql->one("select scode,title,author,update_time from ay_content where id=$id limit 1",1);
		$scode=$xx[scode];
		$title=$xx[title];
		$arr_title[]=$title;
		$filename=$xx[filename];
		$author=$xx[author];
		$visits=$xx[visits];
		$update_time=$xx[update_time];
		$update_time=strtotime($update_time);
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x['name'];
		$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
		$xid=$x[xid];
		$xtitle=$x[xtitle];
		$title2=str_replace($key,'<font class="red">'.$key.'</font>',$title);
		$title2=str_replace($key_,'<font class="red">'.$key_.'</font>',$title);
		if($author){
			$author2=str_replace($key,'<font class="red">'.$key.'</font>',$author);
			$author2=str_replace($key_,'<font class="red">'.$key_.'</font>',$author);
		}else{
			$author2='未统计';
		}
		$xxx.='
		<li>
			<span class="s1">['.str_replace('·','',$name).']</span>
			<span class="s2">
				<a href="'.pbcode('book',$id).'" title="点击阅读《'.$title.'》" target="_blank">'.$title2.'</a>
			</span>
			<span class="s3">
				<p class="vip"></p>
				<a class="chapter" href="'.pbcode('article',$xid).'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>
			</span>
			<span class="s4"><a>'.$author2.'</a></span>
			<span class="s5">'.date('m-d',$update_time).'</span>
		</li>';
	}
	$sql="and ($title_ like '%$key_%' or author like '%$key_%')";
	$dd=$dsql->all("select id from ay_content where status=1 $sql order by id desc limit 100",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		$xx=$dsql->one("select scode,title,author,update_time from ay_content where id=$id limit 1",1);
		$scode=$xx[scode];
		$title=$xx[title];
		if(in_array($title,$arr_title)){
			continue;
	}
		$ii++;
		$filename=$xx[filename];
		$author=$xx[author];
		$visits=$xx[visits];
		$update_time=$xx[update_time];
		$update_time=strtotime($update_time);
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x['name'];
		$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
		$xid=$x[xid];
		$xtitle=$x[xtitle];
		$title2=str_replace($key,'<font class="red">'.$key.'</font>',$title);
		$title2=str_replace($key_,'<font class="red">'.$key_.'</font>',$title);
		if($author){
			$author2=str_replace($key,'<font class="red">'.$key.'</font>',$author);
			$author2=str_replace($key_,'<font class="red">'.$key_.'</font>',$author);
		}else{
			$author2='未统计';
		}
		$xxx.='
		<li>
			<span class="s1">['.str_replace('·','',$name).']</span>
			<span class="s2">
				<a href="'.pbcode('book',$id).'" title="点击阅读《'.$title.'》" target="_blank">'.$title2.'</a>
			</span>
			<span class="s3">
				<p class="vip"></p>
				<a class="chapter" href="'.pbcode('article',$xid).'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>
			</span>
			<span class="s4"><a>'.$author2.'</a></span>
			<span class="s5">'.date('m-d',$update_time).'</span>
		</li>';
	}
	if(!$xxx){
		$xxx='<br /><p style="text-align:center;font-size:16px;">抱歉，暂无内容...</p><br />';
	}
	return $xxx;
}
function SByOu_NET_link(){
	global $dsql;
	$dd=$dsql->all("select link,name from ay_link order by sorting asc,id asc",1);
	foreach($dd as $xx)
	{
		$url=$xx[link];
		$name=$xx[name];
		$xxx.='<a href="'.$url.'" title="'.$name.'" target="_blank">'.$name.'</a>&nbsp;&nbsp;';
	}
	$xxx=rtrim($xxx,'&nbsp;&nbsp;');
	return $xxx.'</a>';
}
function axd_free(){
	global $dsql,$id,$xid,$axd2free,$axd2xx,$axd2num,$axd2read;
	if(!$xid || $axd2free!='1'){
		return false;
	}
	if(is_mobile()){
		$adid=123;
	}else{
		$adid=122;
	}
	$x=$dsql->one("select adbody from ay_ad where adstatus=1 and id=$adid limit 1",1,usedbname());
	$ad=$x[adbody];
	$ad=trim($ad);
	if(!$ad){
		return false;
	}
	$time=get_datetime();
	$time=strtotime($time);
	$axd_session=session('axd_session');
	$arr=explode(',',$axd_session);
	$axd_ses=$arr[0];
	$axd_id=$arr[1];
	$axd_xid=$arr[2];
	$axd_t=$arr[3];
	$axd_num=$arr[4];
	if($axd2read==1){
		$axd_num=session('axd_session_'.$id);
	}
	if(!$axd_num){
		$axd_num=0;
	}
	if(!$axd_t){
		$axd_t=$time;
	}
	$x1=$time-$axd_t;
	$x2='86400';
	if(!$axd_ses || $x1>$x2){
		if($x1>$x2){
			session('axd_session','');
			if($axd2read==1){
				session('axd_session_'.$id,'');
			}
		}
		$ses=0;
	}else{
		if($axd_ses==1){
			$ses=1;
		}else if($axd_ses==2){
			if($axd_num<$axd2num){
				if(strstr($axd_id,'-'.$id.'-') && strstr($axd_xid,'-'.$xid.'-')){
					$xxx='ok';
					return $xxx;
				}else{
					$ses=0;
				}
			}else{
				if($axd2read==1){
					if(strstr($axd_id,'-'.$id.'-')){
						$xxx='ok';
						return $xxx;
					}else{
						$ses=0;
					}
				}else{
					$xxx='ok';
					return $xxx;
				}
			}
		}else{
			$ses=0;
		}
	}
	if(!$ses){
		if($axd2read=='1'){
			$free='本书';
		}else{
			$free='全站';
		}
		$xxx='
		<div class="axd_free af0" id="axd_free">
			<div class="axd_bg" onclick="axd_free(0)"></div>
			<div class="axd_box">
				<div class="axd_h">
					看'.$axd2xx.'秒广告，免费阅读：
					<span onclick="axd_free(0)">X</span>
				</div>
				<div class="axd_m">
					<p>当前为VIP内容，需要充值订阅才可阅读！</p>
					<p>或者，看'.$axd2xx.'秒广告，也可以免费阅读本章节！</p>
					<p class="red">(注：看够'.$axd2num.'个广告，'.$free.'免费阅读，当前已看'.$axd_num.'个广告)</p>
					<p>是否立即打开广告？</p>
				</div>
				<div class="axd_b">
					<div class="btn" onclick="axd_free(1)">确定</div>
					<div class="btn" onclick="axd_free(0)">取消</div>
				</div>
			</div>
		</div>';
	}else{
		$xxx='
		<div class="axd_free" id="axd_free">
			<div class="axd_bg"></div>
			<div class="axd_box">'.$ad.'</div>
			<div class="axd_tbox">
				广告
				<span id="st">'.$axd2xx.'</span>
				秒后自动关闭
			</div>
		</div>
		<script>
		sectime();
		setTimeout(function(){
			axd_free("",'.$id.','.$xid.');
		},'.($axd2xx*1000).');
		</script>';
	}
	return $xxx;
}
function axd_session($id=null,$xid=null){
	global $axd2free,$axd2num,$axd2read;
	if($axd2free!='1'){
		return false;
	}
	$time=get_datetime();
	$time=strtotime($time);
	$axd_session=session('axd_session');
	$arr=explode(',',$axd_session);
	$axd_ses=$arr[0];
	$axd_id=$arr[1];
	$axd_xid=$arr[2];
	$axd_t=$arr[3];
	$axd_num=$arr[4];
	if($axd2read==1){
		$axd_num_=session('axd_session_'.$id);
	}
	if(!$id || !$xid){
		$xxx='1,'.$axd_id.','.$axd_xid.','.$axd_t.','.$axd_num;
		if($axd2read==1){
			$xxx_=$axd_num_;
		}
	}else{
		if($axd_num<$axd2num){
			$axd_num=$axd_num+1;
		}else{
			$axd_num=$axd2num;
		}
		if($axd2read==1){
			if($axd_num_<$axd2num){
				$axd_num_=$axd_num_+1;
			}else{
				$axd_num_=$axd2num;
			}
		}
		$axd_ses=2;
		$axd_id=trim($axd_id,'-');
		$axd_id.='-'.$id;
		$axd_id=trim($axd_id,'-');
		$axd_id='-'.$axd_id.'-';
		$axd_xid=trim($axd_xid,'-');
		$axd_xid.='-'.$xid;
		$axd_xid=trim($axd_xid,'-');
		$axd_xid='-'.$axd_xid.'-';
		$axd_t=$time;
		$xxx=$axd_ses.','.$axd_id.','.$axd_xid.','.$axd_t.','.$axd_num;
		if($axd2read==1){
			$xxx_=$axd_num_;
		}
	}
	session('axd_session',$xxx);
	if($axd2read==1){
		session('axd_session_'.$id,$xxx_);
	}
	return $xxx;
}
function AutoReader($id=null){
	global $dsql,$pcbaseall;
	$x=$dsql->one("select xid from ay_article where status=1 and id=$id order by xid asc limit 1",1);
	$firstxid=$x[xid];
	$firsturl=pbcode('article',$firstxid);
	$xxx='
	<div class="settingbtn" id="settingbtn" onclick="reader.toggleSettingsBox()" style="background: url(\''.$pcbaseall.'include/member/image/read.png\') no-repeat #f3ebe2">自动阅读</div>
	<div class="settings-box">
		<button class="close-btn" onclick="reader.toggleSettingsBox()">×</button>
		<h3>阅读设置</h3>
		<div class="autoNext2">
			<input type="checkbox" id="autoNext2"> <label for="autoNext2">无感加载下一章</label>
		</div>
		<div>
			<input type="checkbox" id="autoRead"> <label for="autoRead">启用自动阅读</label>
		</div>
		<div class="speed-options">
			<label for="speed">速度调节：</label>
			<input type="range" id="speed" min="1" max="10" value="5">
			<span id="speedValue" class="speed-value">5</span>
		</div>
		<div class="autoNext">
			<input type="checkbox" id="autoNext" checked> <label for="autoNext">刷新跳转下一章</label>
		</div>
		<div>
			<input type="checkbox" id="autoRestart" checked> <label for="autoRestart">阅读完后从头开始</label>
		</div>
	</div>
	<script>
		const reader=AutoReader({firsturl: "'.$firsturl.'"});
		reader.init();
	</script>';
	return $xxx;
}
function content_page(){
	global $content,$page,$content_words,$content_pages,$xtitle;
	if($content_words && strstr($content,'bodybox')){
		$content=str_replace(array('<div id="bodybox">','</div>','<p>'),'',$content);
		$content=str_replace(array(PHP_EOL,"\n"),'',$content);
		$content=str_replace('</p>',"\n",$content);
		$len=mb_strlen($content);
		if($len>$content_words){
			$num=ceil($len/$content_words);
			for($i=1;$i<=$num;$i++){
				$s=($i-1)*$content_words;
				$x2_=mb_substr($content,$s,$content_words);
				$x=trim($x2_);
				if($x){
					$arrall[]=$x2_;
				}
			}
		}else{
			$arrall[]=$content;
		}
		$arrall_=array();
		foreach($arrall as $xx){
			$xx=str_replace("\n",'{p}',$xx);
			$xx=str_replace(array(PHP_EOL,"\n"),'',$xx);
			$xx=str_replace('{p}','</p><p>',$xx);
			$xx='<p>'.$xx.'</p>';
			$xx=str_replace('<p></p>','',$xx);
			$arrall_[]=$xx;
		}
		$arrall=$arrall_;
		$content=$arrall[ $page-1 ];
		$content='<div id="bodybox">'.$content.'</div>';
		$content_pages=count($arrall);
		if($content_pages>1 && $page>1){
			$xtitle.='('.$page.'/'.$content_pages.')';
		}
	}
}
function ajaxcontent($id=null,$xid=null,$ajax_content=null){
	global $dsql,$free_chapter;
	if(!$id || !$xid || !is_numeric($id) || !is_numeric($xid)){
		return false;
	}
	if(!$ajax_content){
		$x=$dsql->one("select content from ay_article where xid=$xid limit 1",1);
		$content=$x[content];
		$pre_content=sbyouNet_Body($xid,$content);
		$pre_content=str_replace(array('<div id="bodybox">','</div>'),'',$pre_content);
	}
	$x=$dsql->one("select xid,xtitle,content from ay_article where status=1 and id=$id and xid>$xid order by xid asc limit 1",1);
	$xid_cur=$x[xid];
	$xtitle=$x[xtitle];
	$content=$x[content];
	if($xid_cur){
		$xxx=sbyouNet_Body($xid_cur,$content);
		$xxx=str_replace(array('<div id="bodybox">','</div>'),'',$xxx);
		$xxx='<p style="font-weight:bold;color:red">'.$xtitle.'</p>'.$xxx;
		$x=checkuid(18,$id,$xid_cur,$xxx);
		$arr=explode('{697}',$x);
		$tip_=$arr[1];
		if($tip_){
			if(!$free_chapter){
				$free_chapter=0;
			}
			$x=$dsql->one("select xid from ay_article force index(669977_net_six) where status=1 and id=$id order by xid asc limit $free_chapter,1",1);
			$x=$x[xid];
			if($x && $xid_cur>=$x){
				$xxx='';
				$xxx.='<p style="text-indent:0;color:#999;text-align:center" id="lastchapter">----- 余下章节需要订阅啦 -----</p>';
				$xxx.=$tip_;
			}
		}
		$url=pbcode('article',$xid_cur);
		$url1=pbcode('article',$xid);
		$x=$dsql->one("select xid from ay_article where status=1 and id=$id and xid>$xid_cur order by xid asc limit 1",1);
		$xidc=$x[xid];
		if($xidc){
			$url2=pbcode('article',$xidc);
		}else{
			$url2="javascript:alert('已经是最后一条内容啦！');";
		}
	}else{
		$xxx='<p style="text-indent:0;color:#999;text-align:center" id="lastchapter">----- 已经是最后一个章节啦 -----</p>';
		$url=pbcode('article',$xid);
		$x=$dsql->one("select xid from ay_article where status=1 and id=$id and xid<$xid order by xid desc limit 1",1);
		$xidc=$x[xid];
		if($xidc){
			$url1=pbcode('article',$xidc);
		}else{
			$url1="javascript:alert('已经是第一条内容啦！');";
		}
		$url2="javascript:alert('已经是最后一条内容啦！');";
	}
	if(!$xid_cur){
		$xid_cur=$xid;
	}
	$xxx.='{697}'.$id.','.$xid_cur.'{697}'.$url.'{697}'.$url1.'{697}'.$url2.'{697}'.$xtitle;
	$xxx=$pre_content.$xxx;
	return $xxx;
}
function lastread($id=null,$cookie=null,$uid=null,$username=null,$register_time=null){
	global $dsql;
	if($uid){
		$sbyou_net=$dsql->one("select ucode,username,register_time from ay_member where ucode=$uid limit 1",1);
		$uid_2=$sbyou_net['ucode'];
		$username_2=$sbyou_net['username'];
		$register_time_2=$sbyou_net['register_time'];
	}
	if(!$uid || $uid!=$uid_2 || $username!=$username_2 || $register_time!=$register_time_2){
	}else{
		$x=$dsql->one("select www_669977_net_look from ay_member_data where uid=$uid limit 1",1);
		$data=$x['www_669977_net_look'];
		if(!$data){
		}else{
			$cookie=$data;
		}
	}
	if(strstr($cookie,$id.'-')){
		$x=strstr($cookie,$id.'-');
		$arr=explode('-',$x);
		$id=$arr[1];
		$arr=explode(',',$id);
		$xid=$arr[0];
		if(is_numeric($xid)){
			$url=pbcode('article',$xid);
			$x=$dsql->one("select xtitle from ay_article where xid=$xid limit 1",1);
			$xtitle=$x['xtitle'];
			if($xtitle){
				$xxx='<a href="'.$url.'">'.$xtitle.'</a>';
			}
		}
	}
	if($xxx){
		$xxx='最近阅读本书：'.$xxx;
	}
	return $xxx;
}
?>
