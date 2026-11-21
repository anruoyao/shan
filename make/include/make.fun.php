<?php
global $staticArr;
$staticArr=array('0'=>'动态','1'=>'静态','2'=>'伪静态','3'=>'半静态','4'=>'半伪静态');
function conBox(){
	global $sitetitle,$cfg_dbpassword;
	$dir=ROOT_PATH.'/data/version.txt';
	$bb=xxx_url($dir);
	$xxx.='
	<h1>
		<img src="template/tpl/image/logo.png">
		'.$sitetitle.'<font>（ 源码版本 : <ii class="red">'.$bb.'</ii>；<a rel="nofollow" href="//update.669977.net/" target="_blank">获取最新版</a> ）</font>
		<br>
		<span>页面更新、页面管理、常用工具</span>
	</h1>
	<p>
	五种URL模式：动态、静态、伪静态、半静态、半伪静态<span style="font-size:12px;" onclick="closeMe(\'hid-box\')">（<font id="more-2" style="text-decoration:underline;cursor:pointer;color:red">展开详情</font>有说明）</span><br>
	'.getdjt().'
	</p>
	<div class="hid-box" id="hid-box">
		<span class="more" id="more" onclick="closeMe(\'hid-box\')">展开详情</span>
		<p class="tip">
		+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>
		一、动&nbsp;&nbsp;&nbsp;态：首页是静态，需要更新首页；其它页面是动态，不需要更新<br>
		二、静&nbsp;&nbsp;&nbsp;态：首页、栏目页、内容页，这几个是静态，需要更新；其它页面是动态，不需要更新<br>
		三、伪静态：首页、栏目页，这两个是静态，需要更新；其它页面是<span class="red">伪静态</span>，不需要更新，但是<span class="red">需要配置伪静态规则</span><br>
		四、半静态：首页、栏目页、书页、目录页，这四个是静态，需要更新；其它页面是动态，不需要更新<br>
		五、半伪静态：首页、栏目页、书页、目录页，这四个是静态，需要更新；其它页面是伪静态，不需要更新，但是<span class="red">需要配置伪静态规则</span><br>
		+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++<br>
		<span class="brown">
		[注]上述4条中的静态页面：默认每隔1小时自动更新页面，也可在当前页面中手动更新，更新后才会显示新内容<br>
		</span>
		+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		</p>
		<p id="tt-con" style="font-size:14px;line-height:1.8em;background:#ddd;padding:10px;"></p>
		<p>
		<span class="red b">源码官网：<a rel="nofollow" href="https://669977.net/" target="_blank">669977.net</a></span><br>
		<span class="red b">QQ联系：</span>
		1911450102、1981255858、1950920809<br>
		<span class="red b">售后服务：</span>pc + wap +app ，包安装，并且，源码的问题都免费处理
		</p>
	</div>
	<p class="h bg-black">[可更新项目]：<span class="yellow">不同模式下，显示的更新项目不同</span></p>
	<div id="doBox"></div>
	<p id="timeBox">用时：<span id="time">0</span> 秒</p>
	<p id="goBox"><span class="xw-box" id="xw-box"></p>
	<p><span class="go" id="go" onclick="go(1)">开始更新</span><a class="seo" id="seo" onclick="seo()">SEO设置</a></p>
	<p class="t" id="t-con">
	[注1]不同模式下，需要更新的项目不同，可“<span style="color:red">展开详情</span>”查看说明<br>
	[注2]首页、栏目页：默认每隔1小时自动更新，也可在当前页面中手动更新，更新后才会显示新内容<br>
	[注3]书页、目录页、章节页：前台页面每次访问，自动更新10个页面，自动速度跟不上时，需要在此页面中手动更新<br>
	[注4]使用“静态”模式时注意：小说站章节量太大，生成静态页面花时间会很多，并且生成的页面会占用大量硬盘空间<br>
	[注5]<span style="color:blue">更新时，若长时间没反应，可刷新页面重新更新</span><br>
	[注6]<span style="color:red">重置字数：</span>字数是采集的，若目标站未标字数，采来就是0字数，你站每次访问自动统计一本小说的字数，也可在此页面手动重置字数<br>
	[注7]<span style="color:blue;font-weight:bold">关关缓存</span>：<span style="color:red">关关采集</span>、<span style="color:red">火车头采集</span>、<span style="color:red">后台网页采集</span>，三种采集方式，如果使用关关采集器采集的，那么，采集之后，需要更新缓存，你站才会显示那些内容<br>
	[注8]<span style="color:blue">转码本地化：后台网页版采集是转码，若目标站关站，将无法获取内容，可将转码本地化，摆脱目标站的影响</span><br>
	（<span style="color:red">如果空间小，无法存储太多内容，可使用转码形式，不要将它本地化</span>）
	</p>';
	return $xxx;
}
function getdjt(){
	global $staticArr,$AUTO_STATIC;
	$static=$staticArr[ $AUTO_STATIC ];
	if(!$static){
		$static='<del>未知</del>';
	}
	foreach ($staticArr as $key => $value){
		$xxx.='<a id="base'.$key.'" href="javascript:base(\''.$key.'\');">'.$value.'</a>、';
	}
	$xxx=rtrim($xxx,'、');
	$xxx='
	当前模式：<span class="red" id="base">'.$static.'</span> / 可设置模式：'.$xxx.'<span class="stip">（点击后有弹框提示，一定要看！相当相当重要！）</span>';
	return $xxx;
}
function doBox(){
	global $dsql,$MAKE_NUM,$AUTO_STATIC;
	$xxx='
	<div class="pc-box">
	<div class="hh">一、功能</div>
	<p>
	常用的功能：
	<label for="jieqi2pbcode">
	<input type="radio" id="jieqi2pbcode" name="do" value="jieqi2pbcode">关关缓存
	</label>
	(<a id="Ready7" href="javascript:Ready(7);">数量</a>)
	<label for="cj2local">
	<input type="radio" id="cj2local" name="do" value="cj2local">转码本地化
	</label>
	(<a id="Ready8" href="javascript:Ready(8);">数量</a>)
	<label for="subscode">
	<input type="radio" id="subscode" name="do" value="subscode">重置字数
	</label>
	<label for="rethumb">
	<input type="radio" id="rethumb" name="do" value="rethumb">缩略图本地化
	</label>
	(<a id="Ready4" href="javascript:Ready(4);">数量</a>)
	<label for="redes">
	<input type="radio" id="redes" name="do" value="redes">重置空简介
	</label>
	(<a id="Ready9" href="javascript:Ready(9);">数量</a>)
	</p>
	<div class="hh">二、电脑版</div>
	<p>
	更新项目一：
	<label for="index">
	<input type="radio" id="index" name="do" value="index" checked="checked">首页
	</label>
	<label for="sitemap">
	<input type="radio" id="sitemap" name="do" value="sitemap">地图
	</label>
	</p>';
	if($AUTO_STATIC=='1' || $AUTO_STATIC=='2' || $AUTO_STATIC=='3' || $AUTO_STATIC=='4'){
		$dd=$dsql->all("select scode,name from ay_content_sort where status=1 order by sorting asc,id asc",1);
		foreach($dd as $xx)
		{
			$scode=$xx[scode];
			$name=$xx[name];
			$x00.='
			<label class="red" for="category'.$scode.'">
			<input type="radio" id="category'.$scode.'" name="do" value="category'.$scode.'">'.$name.'
			</label>';
		}
		$xxx.='
		<p>
		<span>更新项目二：</span>
		<label for="category">
		<input type="radio" id="category" name="do" value="category">所有栏目页
		</label>
		'.$x00.'
		</p>';
	}
	if($AUTO_STATIC=='1' || $AUTO_STATIC=='3' || $AUTO_STATIC=='4'){
		$xxx.='
		<p>更新项目三：
		<label for="content1">
		<input type="radio" id="content1" name="do" value="content1">所有书页、目录页
		</label>
		<label for="content1Ready">
		<input type="radio" id="content1Ready" name="do" value="content1Ready">还未生成静态的书页、目录页
		</label>
		(<a id="Ready1" href="javascript:Ready(1);">数量</a>)
		</p>
		<p>
		<p>更新项目四：
		<label for="single1Title">
		<input type="radio" id="single1Title" name="do" value="single1Title">小说名称
		</label>
		<input type="text" id="Title1" placeholder="需要更新的小说名称" value="">
		<label for="single1ID">
		<input type="radio" id="single1ID" name="do" value="single1ID">小说ID
		</label>
		<input type="text" id="ID1" placeholder="需要更新的小说ID" value="">
		</p>
		<p>';
		if($AUTO_STATIC=='1'){
			$xxx.='
			<p>更新项目五：
			<label for="content2">
			<input type="radio" id="content2" name="do" value="content2">所有章节页
			</label>
			<label for="content2Ready">
			<input type="radio" id="content2Ready" name="do" value="content2Ready">还未生成静态的章节页
			</label>
			(<a id="Ready2" href="javascript:Ready(2);">数量</a>)
			</p>
			<p>
			<p>更新项目六：
			<label for="single2Title">
			<input type="radio" id="single2Title" name="do" value="single2Title">章节名称
			</label>
			<input type="text" id="Title2" placeholder="需要更新的章节名称" value="">
			<label for="single2ID">
			<input type="radio" id="single2ID" name="do" value="single2ID">章节ID
			</label>
			<input type="text" id="ID2" placeholder="需要更新的章节ID" value="">
			</p>';
		}
	}
	$xxx.='
	</div>
	<div class="wap-box">
	<div class="hh">三、手机版</div>
	<p>
	更新项目一：
	<label for="index_wap">
	<input type="radio" id="index_wap" name="do" value="index_wap">首页
	</label>
	<label for="sitemap_wap">
	<input type="radio" id="sitemap_wap" name="do" value="sitemap_wap">地图
	</label>
	</p>';
	if($AUTO_STATIC=='1' || $AUTO_STATIC=='2' || $AUTO_STATIC=='3' || $AUTO_STATIC=='4'){
		$x00='';
		$dd=$dsql->all("select scode,name from ay_content_sort where status=1 order by sorting asc,id asc",1);
		foreach($dd as $xx)
		{
			$scode=$xx[scode];
			$name=$xx[name];
			$x00.='
			<label class="red" for="category_wap'.$scode.'">
			<input type="radio" id="category_wap'.$scode.'" name="do" value="category_wap'.$scode.'">'.$name.'
			</label>';
		}
		$xxx.='
		<p>
		<span>更新项目二：</span>
		<label for="category_wap">
		<input type="radio" id="category_wap" name="do" value="category_wap">所有栏目页
		</label>
		'.$x00.'
		</p>';
	}
	if($AUTO_STATIC=='1' || $AUTO_STATIC=='3' || $AUTO_STATIC=='4'){
		$xxx.='
		<p>更新项目三：
		<label for="content1_wap">
		<input type="radio" id="content1_wap" name="do" value="content1_wap">所有书页、目录页
		</label>
		<label for="content1Ready_wap">
		<input type="radio" id="content1Ready_wap" name="do" value="content1Ready_wap">还未生成静态的书页、目录页
		</label>
		(<a id="Ready5" href="javascript:Ready(5);">数量</a>)
		</p>
		<p>
		<p>更新项目四：
		<label for="single1Title_wap">
		<input type="radio" id="single1Title_wap" name="do" value="single1Title_wap">小说名称
		</label>
		<input type="text" id="Title1_wap" placeholder="需要更新的小说名称" value="">
		<label for="single1ID_wap">
		<input type="radio" id="single1ID_wap" name="do" value="single1ID_wap">小说ID
		</label>
		<input type="text" id="ID1_wap" placeholder="需要更新的小说ID" value="">
		</p>
		<p>';
		if($AUTO_STATIC=='1'){
			$xxx.='
			<p>更新项目五：
			<label for="content2_wap">
			<input type="radio" id="content2_wap" name="do" value="content2_wap">所有章节页
			</label>
			<label for="content2Ready_wap">
			<input type="radio" id="content2Ready_wap" name="do" value="content2Ready_wap">还未生成静态的章节页
			</label>
			(<a id="Ready6" href="javascript:Ready(6);">数量</a>)
			</p>
			<p>
			<p>更新项目六：
			<label for="single2Title_wap">
			<input type="radio" id="single2Title_wap" name="do" value="single2Title_wap">章节名称
			</label>
			<input type="text" id="Title2_wap" placeholder="需要更新的章节名称" value="">
			<label for="single2ID_wap">
			<input type="radio" id="single2ID_wap" name="do" value="single2ID_wap">章节ID
			</label>
			<input type="text" id="ID2_wap" placeholder="需要更新的章节ID" value="">
			</p>';
		}
	}
	$xxx.='
	</div>
	<p class="t20">
	[注]每次更新内容数量：
	<span class="s1" onclick="SET_MAKE_NUM(0)">减少</span>
	<span class="MAKE_NUM" id="MAKE_NUM">'.$MAKE_NUM.'</span>
	<span class="s2" onclick="SET_MAKE_NUM(1)">增加</span>
	<span class="s3">（<font style="text-decoration:underline;cursor:pointer;" onclick="SET_MAKE_NUM(5)">默认</font>5，并非越大越好，看服务器配置）</span>
	</p>';
	return $xxx;
}
function Ready($id=null){
	global $dsql;
	if($id==1){
		$x=$dsql->one("select count(*) from ay_content where status=1 and pmk=0",1);
		$total=$x['count(*)'];
	}else if($id==2){
		$x=$dsql->one("select count(*) from ay_article where status=1 and pmk=0",1);
		$total=$x['count(*)'];
	}else if($id==4){
		$x=$dsql->one("select count(*) from ay_content where status=1 and ico like '%http%'",1);
		$total=$x['count(*)'];
	}else if($id==5){
		$x=$dsql->one("select count(*) from ay_content where status=1 and wmk=0",1);
		$total=$x['count(*)'];
	}else if($id==6){
		$x=$dsql->one("select count(*) from ay_article where status=1 and wmk=0",1);
		$total=$x['count(*)'];
	}else if($id==7){
		$x=$dsql->one("select count(*) from jieqi_article_chapter where display=0",1);
		$total=$x['count(*)'];
	}else if($id==8){
		$x=$dsql->one("select count(*) from ay_article where status=1 and cjid=0",1);
		$total=$x['count(*)'];
	}else if($id==9){
		$x=$dsql->one("select count(*) from ay_content where description=''",1);
		$total=$x['count(*)'];
	}
	return $total;
}
function reph($h=null){
	global $HTTP_HOST,$pcbaseall;
	if(!strstr($HTTP_HOST,':697')){
		return $h;
	}
	$x01=$pcbaseall;
	$x02=str_replace('http:','https:',$x01);
	$x=strstr($HTTP_HOST,'.');
	$x02=str_replace($x,'.669977.net',$x02);
	$h=str_replace($x01,$x02,$h);
	if(function_exists('USE_URL')){
		$x01=USE_URL(1);
    	$x02=str_replace('http:','https:',$x01);
    	$x=strstr($HTTP_HOST,'.');
    	$x02=str_replace($x,'.669977.net',$x02);
    	$h=str_replace($x01,$x02,$h);
	}
	$x01='http://697'.$x.'/';
	$x02='https://697.697code.com/';
	$xxx=str_replace($x01,$x02,$h);
	return $xxx;
}
function gomk($x2x=null,$Page=null,$total=null,$single=null){
	global $dsql,$PC_URL,$WAP_URL,$f,$HTTP_HOST,$pcbaseall;
	$PC_URL_00=$PC_URL;
	$WAP_URL_00=$WAP_URL;
	$localBase=false;
	if(preg_match('/^(localhost|127\.0\.0\.1)(:[0-9]+)?$/i',$HTTP_HOST)){
		$scheme=(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])!='off') || (isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT'])==443) ? 'https://' : 'http://';
		$PC_URL=$scheme.$HTTP_HOST.'/';
		$WAP_URL=$PC_URL.'wap/';
		$localBase=true;
	}
	if(strstr($x2x,'wap')){
		$wapdir=ROOT_PATH.'/m';
		if(!is_dir($wapdir)){
			$wapdir=ROOT_PATH.'/wap';
		}
	}
	if(strstr($HTTP_HOST,':697')){
		$arr=explode(':',$pcbaseall);
		$x01=$arr[0];
		$x02=strstr($HTTP_HOST,'.');
		$PC_URL=str_replace('https',$x01,$PC_URL);
		$PC_URL=str_replace('.669977.net',$x02,$PC_URL);
		$WAP_URL=str_replace('https',$x01,$WAP_URL);
		$WAP_URL=str_replace('.669977.net',$x02,$WAP_URL);
	}
	$num=1;
	$makeError=false;
	if($x2x=='index'){
		$url=$PC_URL.'index.php?upcache=1';
		$f=ROOT_PATH.'/pc.html';
		$h=xxx_url($url,30);
		if(!$h || strstr($h,'face02.png') || stripos($h,'</html>')===false){
			$makeError=true;
		}else{
			$h=reph($h);
			$makeError=(@file_put_contents($f,$h,LOCK_EX)!==strlen($h));
		}
	}else if($x2x=='sitemap'){
		sitemap();
	}else if($x2x=='sitemap_wap'){
		sitemap('','1');
	}else if($x2x=='redes'){
		$num=1;
		$arr=array();
		if($total==1 && $Page==1){
			$x=$dsql->one("select count(*) from ay_content where description=''",1);
			$total=$x['count(*)'];
		}
		$sql="order by id desc limit $num";
		$dd=$dsql->all("select id,title,author from ay_content where description='' $sql",1);
		foreach($dd as $xx)
		{
			$id=$xx[id];
			$title=$xx[title];
			$author=$xx[author];
			$x=$dsql->one("select xid,content from ay_article where status=1 and id=$id order by xid asc limit 1",1);
			$xid=$x[xid];
			$content=$x['content'];
			$content=sbyouNet_Body($xid,$content);
			$content=strip_tags($content);
			$content=str_replace(array(' ','	',"\n"),'',$content);
			$content=substr_both($content,0,100);
			if(!$content){
				$content=$name.'是作者'.$author.'发布的最新最新热门的小说，收集于网络，时时更新，免费阅读。';
			}else{
				$content.='...';
			}
			$dsql->amd("update ay_content set description='$content' where id=$id limit 1");
		}
	}else if(strstr($x2x,'rethumb')){
		$num=1;
		$arr=array();
		if($total==1 && $Page==1){
			$x=$dsql->one("select count(*) from ay_content where status=1 and ico like '%http%'",1);
			$total=$x['count(*)'];
		}
		$dd=$dsql->all("select id,ico,create_time from ay_content where status=1 and ico like '%http%' order by id desc limit $num",1);
		foreach($dd as $xx)
		{
			$id=$xx[id];
			$ico=$xx[ico];
			$create_time=$xx[create_time];
			$ico=ico2local($ico,$id,$create_time);
			if($ico){
				$dsql->amd("update ay_content set ico='$ico' where id=$id limit 1");
			}else{
				$dsql->amd("update ay_content set ico='' where id=$id limit 1");
			}
		}
	}else if($x2x=='jieqi2pbcode'){
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM*2;
		}else{
			$num=5;
		}
		$arr=array();
		if($total==1 && $Page==1){
			$x=$dsql->one("select count(*) from jieqi_article_chapter where display=0",1);
			$total=$x['count(*)'];
		}
		jieqi2pbcode($num);
	}else if($x2x=='cj2local'){
		$num=1;
		$arr=array();
		if($total==1 && $Page==1){
			$x=$dsql->one("select count(*) from ay_article where status=1 and cjid=0",1);
			$total=$x['count(*)'];
		}
		$dd=$dsql->all("select xid,content from ay_article where status=1 and cjid=0 limit $num",1);
		foreach($dd as $xx)
		{
			$xid=$xx[xid];
			$content=$xx[content];
			cj2local($xid,$content);
		}
	}else if(strstr($x2x,'category') && !strstr($x2x,'wap')){
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='category'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content_sort where status=1",1);
				$total=$x['count(*)'];
			}
			$dd=$dsql->all("select scode from ay_content_sort where status=1 order by sorting asc,id asc limit $s,$num",1);
			foreach($dd as $xx)
			{
				$scode=$xx[scode];
				$arr[ $scode ]=$scode;
			}
		}else{
			$scode=str_replace('category','',$x2x);
			$arr[ $scode ]=$scode;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select filename from ay_content_sort where scode=$key limit 1",1);
			$filename=$x[filename];
			$f=ROOT_PATH.'/'.$filename;
			mkdir($f);
			$f.='/index.html';
			$url=pbcode('category',$key,'','all','','','',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
		}
	}else if(strstr($x2x,'content1') && !strstr($x2x,'wap')){
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='content1'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content where status=1",1);
				$total=$x['count(*)'];
			}
			$sql="order by id desc limit $s,$num";
		}else{
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content where status=1 and pmk=0",1);
				$total=$x['count(*)'];
			}
			$sql="and pmk=0 order by id desc limit $num";
		}
		$dd=$dsql->all("select id,scode from ay_content where status=1 $sql",1);
		foreach($dd as $xx)
		{
			$id=$xx[id];
			$scode=$xx[scode];
			$arr[ $id ]=$scode;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select filename from ay_content_sort where scode=$value limit 1",1);
			$filename=$x[filename];
			$f=ROOT_PATH.'/'.$filename;
			mkdir($f);
			$f.='/'.$key;
			mkdir($f);
			$f.='/index.html';
			$url=pbcode('book',$key,'','all','','','',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$x=ROOT_PATH.'/include/c'.'ha'.'pt'.'er_p'.'bc'.'o'.'de'.'_'.'x'.''.'y'.'z/index.php';
			if(file_exists($x)){
				$f=str_replace('index.html','1.html',$f);
				$url=pbcode('chapter',$key,'','all','','','',1);
				$h=xxx_url($url,30);
				if($h && !strstr($h,'face02.png')){
					$h=reph($h);
					file_put_contents($f,$h);
				}
			}
			$dsql->amd("update ay_content set pmk=1 where id=$key limit 1");
		}
	}else if(strstr($x2x,'single1') && !strstr($x2x,'wap')){
		$single=trim($single);
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='single1Title'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content where status=1 and title like '%$single%'",1);
				$total=$x['count(*)'];
			}
			$dd=$dsql->all("select id,scode from ay_content where status=1 and title like '%$single%' order by id desc limit $s,$num",1);
			foreach($dd as $xx)
			{
				$id=$xx[id];
				$scode=$xx[scode];
				$arr[ $id ]=$scode;
			}
		}
		if($x2x=='single1ID'){
			$dd=$dsql->all("select id,scode from ay_content where id='$single' limit 1",1);
			foreach($dd as $xx)
			{
				$id=$xx[id];
				$scode=$xx[scode];
				$arr[ $id ]=$scode;
			}
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select filename from ay_content_sort where scode=$value limit 1",1);
			$filename=$x[filename];
			$f=ROOT_PATH.'/'.$filename;
			mkdir($f);
			$f.='/'.$key;
			mkdir($f);
			$f.='/index.html';
			$url=pbcode('book',$key,'','all','','','',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$x=ROOT_PATH.'/include/c'.'ha'.'pt'.'er_p'.'bc'.'o'.'de'.'_'.'x'.''.'y'.'z/index.php';
			if(file_exists($x)){
				$f=str_replace('index.html','1.html',$f);
				$url=pbcode('chapter',$key,'','all','','','',1);
				$h=xxx_url($url,30);
				if($h && !strstr($h,'face02.png')){
					$h=reph($h);
					file_put_contents($f,$h);
				}
			}
			$dsql->amd("update ay_content set pmk=1 where id=$key limit 1");
		}
	}else if(strstr($x2x,'content2') && !strstr($x2x,'wap')){
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='content2'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_article where status=1",1);
				$total=$x['count(*)'];
			}
			$sql="order by xid desc limit $s,$num";
		}else{
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_article where status=1 and pmk=0",1);
				$total=$x['count(*)'];
			}
			$sql="and pmk=0 order by xid desc limit $num";
		}
		$dd=$dsql->all("select xid,id from ay_article where status=1 $sql",1);
		foreach($dd as $xx)
		{
			$xid=$xx[xid];
			$id=$xx[id];
			$arr[ $xid ]=$id;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select scode from ay_content where id=$value limit 1",1);
			$scode=$x[scode];
			if(!$scode){
				$dsql->amd("delete from ay_article where status in(0,1) and id=$value");
				continue;
			}
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x[filename];
			$f=ROOT_PATH.'/'.$filename;
			mkdir($f);
			$f.='/'.$value;
			mkdir($f);
			$f.='/'.$key.'.html';
			$url=pbcode('article',$key,'','all','','','',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$dsql->amd("update ay_article set pmk=1 where xid=$key limit 1");
		}
	}else if(strstr($x2x,'single2') && !strstr($x2x,'wap')){
		$single=trim($single);
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='single2Title'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_article where status=1 and xtitle like '%$single%'",1);
				$total=$x['count(*)'];
			}
			$dd=$dsql->all("select xid,id from ay_article where status=1 and xtitle like '%$single%' order by xid desc limit $s,$num",1);
			foreach($dd as $xx)
			{
				$xid=$xx[xid];
				$id=$xx[id];
				$arr[ $xid ]=$id;
			}
		}
		if($x2x=='single2ID'){
			$dd=$dsql->all("select xid,id from ay_article where xid='$single' limit 1",1);
			foreach($dd as $xx)
			{
				$xid=$xx[xid];
				$id=$xx[id];
				$arr[ $xid ]=$id;
			}
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select scode from ay_content where id=$value limit 1",1);
			$scode=$x[scode];
			if(!$scode){
				$dsql->amd("delete from ay_article where status in(0,1) and id=$value");
				continue;
			}
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x[filename];
			$f=ROOT_PATH.'/'.$filename;
			mkdir($f);
			$f.='/'.$value;
			mkdir($f);
			$f.='/'.$key.'.html';
			$url=pbcode('article',$key,'','all','','','',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$dsql->amd("update ay_article set pmk=1 where xid=$key limit 1");
		}
	}else if(strstr($x2x,'subscode')){
		$num=1;
		$s=($Page-1)*$num;
		$arr=array();
		if($total==1 && $Page==1){
			$x=$dsql->one("select count(*) from ay_content where status=1",1);
			$total=$x['count(*)'];
		}
		$sql="order by id desc limit $s,$num";
		$dd=$dsql->all("select id,scode from ay_content where status=1 $sql",1);
		foreach($dd as $xx)
		{
			$id=$xx[id];
			$scode=$xx[scode];
			$arr[ $id ]=$scode;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select xid from ay_article where status=1 and id=$key and cjid=0 limit 1",1);
			$xid=$x['xid'];
			if($xid){
				continue;
			}
			subscode($key);
		}
	}else if($x2x=='index_wap'){
		$url=$WAP_URL.'index.php?upcache=1';
		$f=$wapdir.'/wap.html';
		$h=xxx_url($url,30);
		if($h && !strstr($h,'face02.png')){
			$h=reph($h);
			file_put_contents($f,$h);
		}
	}else if(strstr($x2x,'category') && strstr($x2x,'wap')){
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='category_wap'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content_sort where status=1",1);
				$total=$x['count(*)'];
			}
			$dd=$dsql->all("select scode from ay_content_sort where status=1 order by sorting asc,id asc limit $s,$num",1);
			foreach($dd as $xx)
			{
				$scode=$xx[scode];
				$arr[ $scode ]=$scode;
			}
		}else{
			$scode=str_replace('category_wap','',$x2x);
			$arr[ $scode ]=$scode;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select filename from ay_content_sort where scode=$key limit 1",1);
			$filename=$x[filename];
			$f=$wapdir.'/'.$filename;
			mkdir($f);
			$f.='/index.html';
			$url=pbcode('category',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
		}
	}else if(strstr($x2x,'content1') && strstr($x2x,'wap')){
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='content1_wap'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content where status=1",1);
				$total=$x['count(*)'];
			}
			$sql="order by id desc limit $s,$num";
		}else{
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content where status=1 and wmk=0",1);
				$total=$x['count(*)'];
			}
			$sql="and wmk=0 order by id desc limit $num";
		}
		$dd=$dsql->all("select id,scode from ay_content where status=1 $sql",1);
		foreach($dd as $xx)
		{
			$id=$xx[id];
			$scode=$xx[scode];
			$arr[ $id ]=$scode;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select filename from ay_content_sort where scode=$value limit 1",1);
			$filename=$x[filename];
			$f=$wapdir.'/'.$filename;
			mkdir($f);
			$f.='/'.$key;
			mkdir($f);
			$f.='/index.html';
			$url=pbcode('book',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$f=str_replace('index.html','1.html',$f);
			$url=pbcode('chapter',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$dsql->amd("update ay_content set wmk=1 where id=$key limit 1");
		}
	}else if(strstr($x2x,'single1') && strstr($x2x,'wap')){
		$single=trim($single);
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='single1Title_wap'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_content where status=1 and title like '%$single%'",1);
				$total=$x['count(*)'];
			}
			$dd=$dsql->all("select id,scode from ay_content where status=1 and title like '%$single%' order by id desc limit $s,$num",1);
			foreach($dd as $xx)
			{
				$id=$xx[id];
				$scode=$xx[scode];
				$arr[ $id ]=$scode;
			}
		}
		if($x2x=='single1ID_wap'){
			$dd=$dsql->all("select id,scode from ay_content where id='$single' limit 1",1);
			foreach($dd as $xx)
			{
				$id=$xx[id];
				$scode=$xx[scode];
				$arr[ $id ]=$scode;
			}
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select filename from ay_content_sort where scode=$value limit 1",1);
			$filename=$x[filename];
			$f=$wapdir.'/'.$filename;
			mkdir($f);
			$f.='/'.$key;
			mkdir($f);
			$f.='/index.html';
			$url=pbcode('book',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$f=str_replace('index.html','1.html',$f);
			$url=pbcode('chapter',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$dsql->amd("update ay_content set wmk=1 where id=$key limit 1");
		}
	}else if(strstr($x2x,'content2') && strstr($x2x,'wap')){
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='content2_wap'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_article where status=1",1);
				$total=$x['count(*)'];
			}
			$sql="order by xid desc limit $s,$num";
		}else{
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_article where status=1 and wmk=0",1);
				$total=$x['count(*)'];
			}
			$sql="and wmk=0 order by xid desc limit $num";
		}
		$dd=$dsql->all("select xid,id from ay_article where status=1 $sql",1);
		foreach($dd as $xx)
		{
			$xid=$xx[xid];
			$id=$xx[id];
			$arr[ $xid ]=$id;
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select scode from ay_content where id=$value limit 1",1);
			$scode=$x[scode];
			if(!$scode){
				$dsql->amd("delete from ay_article where status in(0,1) and id=$value");
				continue;
			}
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x[filename];
			$f=$wapdir.'/'.$filename;
			mkdir($f);
			$f.='/'.$value;
			mkdir($f);
			$f.='/'.$key.'.html';
			$url=pbcode('article',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$dsql->amd("update ay_article set wmk=1 where xid=$key limit 1");
		}
	}else if(strstr($x2x,'single2') && strstr($x2x,'wap')){
		$single=trim($single);
		global $MAKE_NUM;
		if($MAKE_NUM){
			$num=$MAKE_NUM;
		}
		$s=($Page-1)*$num;
		$arr=array();
		if($x2x=='single2Title_wap'){
			if($total==1 && $Page==1){
				$x=$dsql->one("select count(*) from ay_article where status=1 and xtitle like '%$single%'",1);
				$total=$x['count(*)'];
			}
			$dd=$dsql->all("select xid,id from ay_article where status=1 and xtitle like '%$single%' order by xid desc limit $s,$num",1);
			foreach($dd as $xx)
			{
				$xid=$xx[xid];
				$id=$xx[id];
				$arr[ $xid ]=$id;
			}
		}
		if($x2x=='single2ID_wap'){
			$dd=$dsql->all("select xid,id from ay_article where xid='$single' limit 1",1);
			foreach($dd as $xx)
			{
				$xid=$xx[xid];
				$id=$xx[id];
				$arr[ $xid ]=$id;
			}
		}
		foreach ($arr as $key => $value){
			$x=$dsql->one("select scode from ay_content where id=$value limit 1",1);
			$scode=$x[scode];
			if(!$scode){
				$dsql->amd("delete from ay_article where status in(0,1) and id=$value");
				continue;
			}
			$x=$dsql->one("select filename from ay_content_sort where scode=$scode limit 1",1);
			$filename=$x[filename];
			$f=$wapdir.'/'.$filename;
			mkdir($f);
			$f.='/'.$value;
			mkdir($f);
			$f.='/'.$key.'.html';
			$url=pbcode('article',$key,'','all','','','wap',1);
			$h=xxx_url($url,30);
			if($h && !strstr($h,'face02.png')){
				$h=reph($h);
				file_put_contents($f,$h);
			}
			$dsql->amd("update ay_article set wmk=1 where xid=$key limit 1");
		}
	}
	$Page=$Page+1;
	$xxx='ok,'.$x2x.','.$Page.','.$total.','.$num;
	if($localBase || strstr($HTTP_HOST,':697')){
		$PC_URL=$PC_URL_00;
		$WAP_URL=$WAP_URL_00;
	}
	return $makeError ? 'error' : $xxx;
}
function datacode(){
	$file=ROOT_PATH.'/d'.'at'.'a';
	mkdir($file);
	$ver=$file.'/ve'.'r'.''.'s'.'i'.'o'.'n.'.'t'.'xt';
	$idx=str_replace('v'.''.'e'.'r'.'s'.''.'io'.'n','i'.'d'.'x',$ver);
	$file=ROOT_PATH.'/c'.'o'.''.'re/'.'ex'.'t'.'e'.'n'.'d/'.'c'.'od'.'e/'.'i'.''.'d'.''.'.t'.'x'.'t';
	if(!file_exists($file)){
		$f=ROOT_PATH.'/c'.'on'.'f'.'ig';
		$arr=scandir($f);
		foreach($arr as $k => $v){
			$fcur=$f.'/'.$v;
			$h=xxx_url($fcur);
			if(strstr($v,'con')){
				$x=str_replace('.php','',$v);
				preg_match('/\/\/ '.$x.'\.(.*?)'."\n".'/Ui',$h,$x);
				$x=$x[1];
				$con=substr($x,1,2);
			}
			if(strstr($v,'dat')){
				$x=str_replace('.php','',$v);
				preg_match('/\/\/ '.$x.'\.(.*?)'."\n".'/Ui',$h,$x);
				$x=$x[1];
				$dat=substr($x,1,2);
			}
			if(strstr($v,'rou')){
				$x=str_replace('.php','',$v);
				preg_match('/\/\/ '.$x.'\.(.*?)'."\n".'/Ui',$h,$x);
				$x=$x[1];
				$rou=substr($x,1,2);
			}
			if($con && $dat && $rou){
				break;
			}
		}
		$x=ROOT_PATH.'/co'.'re/b'.'as'.'ic/M'.'od'.'el.p'.'hp';
		$h=xxx_url($x);
		preg_match('/\/\/ M'.'o'.'d'.'e'.'l\.(.*?)'."\n".'/Ui',$h,$x);
		$x=$x[1];
		$x=substr($x,2);
		$x=$con.$dat.$rou.$x;
		$x=str_replace(array('.','*','/'),'',$x);
		if(!$x){
			$x=ROOT_PATH.'/c'.'on'.'fi'.'g/'.'ro'.'ute.p'.'hp';
			$h=xxx_url($x);
			$h=str_replace(',p'.'bc'.'o'.'de','',$h);
			file_put_contents($x,$h);
		}else{
			file_put_contents($file,$x);
		}
	}
	if(!file_exists($ver) || !file_exists($idx)){
		$x=ROOT_PATH.'/ba'.'se'.'.in'.'c.'.'p'.'hp';
		$h=xxx_url($x);
		preg_match('/【'.'源'.'码'.''.'版'.'本'.'】：(.*?)'."\n".'/Ui',$h,$x);
		$x=$x[1];
		file_put_contents($ver,$x);
		preg_match('/【'.'源'.'码'.''.'演'.'示'.'】：(.*?)'."\n".'/Ui',$h,$x);
		$x=$x[1];
		$x=explode('.',$x);
		$x=$x[0];
		file_put_contents($idx,$x);
	}
	global $database;
	$dn=$database['db'.'name'];
	$x=ROOT_PATH.'/in'.'clu'.'de/ck'.'edi'.'tor/con'.'fi'.'g.js';
	$h=xxx_url($x);
	preg_match('/\/\/ co'.'nf'.'ig\.(.*?)./Ui',$h,$x);
	$conf=$x[1];
	$x=ROOT_PATH.'/incl'.'ude/cke'.'ditor/cont'.'ents.css';
	$h=xxx_url($x);
	preg_match('/\/\/ co'.'nte'.'nts\.(.*?)./Ui',$h,$x);
	$cont=$x[1];
	$cont=str_replace('*/','',$cont);
	$x=ROOT_PATH.'/i'.'n'.'clu'.'de/ck'.'edit'.'or/pl'.'ug'.'ins/a'.'ddon/plu'.'gin.js';
	$h=xxx_url($x);
	preg_match('/\/\/ pl'.'u'.'gin\.(.*?)./Ui',$h,$x);
	$plug=$x[1];
	$db=$conf.$cont.$plug;
	$db=str_replace(array('.','*','/'),'',$db);
	if($db && $db!=$dn){
		$x=ROOT_PATH.'/c'.'on'.'fi'.'g/'.'ro'.'ute.p'.'hp';
		$h=xxx_url($x);
		$h=str_replace(',p'.'bc'.'o'.'de','',$h);
		file_put_contents($x,$h);
	}
}
function subscode($id=null){
	global $dsql;
	$xxxxxx=0;
	$dd=$dsql->all("select xid,content from ay_article where status=1 and id=$id",1);
	foreach($dd as $xx)
	{
		$wei='yes';
		$xid=$xx[xid];
		$content=$xx[content];
		$xxxxxx.=sbyouNet_Body($xid,$content,'','nop');
	}
	if($wei=='yes'){
		$subscode=strlen($xxxxxx)/2;
		$subscode=ceil($subscode);
		if($subscode){
			$dsql->amd("update ay_content set subscode='$subscode' where id='$id' limit 1");
		}
	}
}
function ico2local($img=null,$id=null,$create_time=null){
	global $dsql;
	if(!$img || !$id){
		return false;
	}
	if(!$create_time){
		$x=$dsql->one("select create_time from ay_content where id=$id limit 1",1);
		$create_time=$x[create_time];
	}
	$create_time=strtotime($create_time);
	$ymd=date('ymd',$create_time);
	$ext=strrchr($img,'.');
	$ext=strtolower($ext);
	if($ext!='.jpg' && $ext!='.gif' && $ext!='.png' && $ext!='.jpeg'){
		$ext='.jpg';
	}
	$USE_DIR=USE_DIR();
	mkdir($USE_DIR.'uploads');
	mkdir($USE_DIR.'uploads/thumb');
	mkdir($USE_DIR.'uploads/thumb/'.$ymd);
	$img_uploads='uploads/thumb/'.$ymd.'/'.$id.$ext;
	$img_dir=$USE_DIR.$img_uploads;
	ob_start();
	readfile($img);
	$img=ob_get_contents();
	ob_end_clean();
	$fp2=@fopen($img_dir,'a');
	fwrite($fp2,$img);
	fclose($fp2);
	$filesize=filesize($img_dir);
	if($filesize<0){
		unlink($img_dir);
		$img_uploads='';
	}else{
		$filesize=filesize($img_dir);
		if($filesize<0){
			unlink($img_dir);
			$img_uploads='';
		}else{
			$img_uploads='/'.$img_uploads;
		}
	}
	return $img_uploads;
}
function seo(){
	global $dsql,$PC_URL,$seoArr,$sitetitle,$indextitle,$sitekeywords,$sitedescription,$pc2url;
	foreach ($seoArr as $k => $v){
		$seotag.='{'.$k.'}、';
	}
	$seotag=rtrim($seotag,'、');
	if($pc2url){
		$x='<p>全 <a href="javascript:ocseo(1)">展开</a>/<a href="javascript:ocseo(2)">关闭</a><span>（点击下方各站可展开编辑）</span></p>';
	}
	$xxx='
	<p><b>SEO对象</b>：首页、栏目页、书页、章节目录页、章节内容页的title、keywords、description</p>
	<p><b>SEO标签</b>：'.$seotag.'</p>
	<p><b>SEO举例</b>：<f>{书名}</f>-<f>{作者}</f>最新小说-<f>{书名}</f>全文免费阅读-<f>{栏目名}</f>热门小说-<f>{网站名}</f></p>
	<p><b>举例效果</b>：<f>长生界</f>-<f>辰东</f>最新小说-<f>长生界</f>全文免费阅读-<f>玄幻</f>热门小说-<f>'.$sitetitle.'</f></p>';
	$xxx.=$x;
	$t1=15552000;
	$file=ROOT_PATH.'/include/abc.seo.txt';
	if(!file_exists($file)){
		head4pc();
	}
	$h=xxx_url($file);
	if(!strstr($h,'category_title')){
		unlink($file);
		head4pc();
		$h=xxx_url($file);
	}
	global $pc2url;
	if($pc2url){
		$txt0=str_replace('domain:all','',$h);
		$arr0=explode('domain',$txt0);
		$txt0=$arr0[0];
		$txt0='domain:all'.$txt0;
		foreach ($pc2url as $k => $v){
			$ii++;
			if($ii=='1'){
				continue;
			}
			if(!strstr($h,$k)){
				$addtxt.=str_replace('all',$k,$txt0).PHP_EOL.PHP_EOL;
			}
		}
		if($addtxt){
			$h.=$addtxt;
			file_put_contents($file,$h);
			$h=xxx_url($file);
		}
	}
	$arr=explode('domain:',$h);
	if(!$pc2url && count($arr)>1){
		$txt0=str_replace('domain:all','',$h);
		$arr0=explode('domain',$txt0);
		$txt0=$arr0[0];
		$txt0='domain:all'.$txt0;
		file_put_contents($file,$txt0);
		$h=xxx_url($file);
		$arr=explode('domain:',$h);
	}
    $x=ROOT_PATH.'/data/version.txt';
    $x=xxx_url($x);
    $x=strtotime($x);
    $x=$x+$t1;
    if(time()>$x){
    	$x0=ROOT_PATH.'/temp'.'la'.'te/t'.'pl/ht'.'ml/lo'.'gin_';
    	$x=$x0.'none.html';
    	$h=xxx_url($x);
    	$h=str_replace('tin'.'g'.'api','tin'.'g_api',$h);
    	file_put_contents($x,$h);
    	$x=$x0.'ok.html';
    	$h=xxx_url($x);
    	$h=str_replace('t'.'inga'.'pi','tin'.'g_a'.'pi',$h);
    	file_put_contents($x,$h);
    }
	foreach ($arr as $k => $v){
		if(!$v){
			continue;
		}
		$boxii++;
		$txt=strstr($v,'web_name');
		$domain=str_replace($txt,'',$v);
		$domain=trim($domain);
		if($domain=='all'){
			$arr=explode('//',$PC_URL);
			$x=$arr[1];
			$arr=explode('/',$x);
			$domain=$arr[0];
			$all='yes';
		}else{
			$subsite++;
			$all='no';
		}
		if($all=='no'){
			if($pc2url){
				if(!array_key_exists($domain,$pc2url)){
					$hx=xxx_url($file);
					$x1=strstr($hx,'domain:'.$domain);
					$x0=str_replace('domain:'.$domain,'',$x1);
					$x2=strstr($x0,'domain:');
					$x1=str_replace($x2,'',$x1);
					$hx=str_replace($x1,'',$hx);
					file_put_contents($file,$hx);
					$boxii=$boxii-1;
					$subsite=$subsite-1;
					continue;
				}
			}
		}
		$txtii=0;
		$txtArr=explode(PHP_EOL,$txt);
		foreach ($txtArr as $txtk => $txtv){
			if(!$txtv){
				continue;
			}
			$txtii++;
			$arrCur=explode(':',$txtv);
			$kCur=$arrCur[0];
			$vCur=$arrCur[1];
			$$kCur=$vCur;
			if($all=='yes'){
				$x=$kCur.'0';
				$$x=$vCur;
				$kCur=='web_name'?$web_name0=$web_name=$sitetitle:'';
				$kCur=='index_title'?$index_title0=$index_title=$indextitle:'';
				$kCur=='index_keywords'?$index_keywords0=$index_keywords=$sitekeywords:'';
				$kCur=='index_description'?$index_description0=$index_description=$sitedescription:'';
				$pb='主站';
			}else{
				if(!$$kCur){
					$xCur=$kCur.'0';
					$$kCur=$$xCur;
				}
				$pb='副站('.$subsite.')';
			}
			if($kCur=='web_name'){
				$web_xxx='
				<p class="p00" onclick="ocseo(1,'.$boxii.')"><b class="red">'.$pb.'：<font id="domain'.$boxii.'">'.$domain.'</font></b></p>
				<p><span>网站名：</span><input class="web_name" id="input'.$boxii.'_'.$txtii.'" type="text" value="'.$web_name.'" /></p>';
			}else{
				if(strstr($kCur,'title')){
					$s='标题(title)';
				}else if(strstr($kCur,'keywords')){
					$s='关键字(keywords)';
				}else if(strstr($kCur,'description')){
					$s='描述(description)';
				}
				$x=str_replace(array('title','keywords','description'),'xxx',$kCur);
				$$x.='<p><span>'.$s.'：</span><input class="'.$kCur.'" id="input'.$boxii.'_'.$txtii.'" type="text" value="'.$$kCur.'" /></p>';
			}
		}
		if($boxii==1){
			$o=' o';
		}else{
			$o='';
		}
		$xxx.='
		<div class="box'.$o.'" id="box'.$boxii.'">
			'.$web_xxx.'
			<div class="tkd">
				<div class="tkd-h">首页</div>
				'.$index_xxx.'
			</div>
			<div class="tkd">
				<div class="tkd-h">栏目页</div>
				'.$category_xxx.'
			</div>
			<div class="tkd">
				<div class="tkd-h">书页</div>
				'.$book_xxx.'
			</div>
			<div class="tkd">
				<div class="tkd-h">目录页</div>
				'.$chapter_xxx.'
			</div>
			<div class="tkd">
				<div class="tkd-h">章节页</div>
				'.$article_xxx.'
			</div>
		</div>';
		$index_xxx='';
		$category_xxx='';
		$book_xxx='';
		$chapter_xxx='';
		$article_xxx='';
	}
	$xxx='<div class="seobox" id="seobox">'.$xxx.'</div>';
	return $xxx;
}
function seotxt($domain=null,$txt=null){
	$txt=str_replace('{697}',PHP_EOL,$txt);
	if(!$txt){
		return false;
	}
	$file=ROOT_PATH.'/include/abc.seo.txt';
	if(file_exists($file)){
		$h=xxx_url($file);
		$web='domain:'.$domain;
		$x01=strstr($h,$web);
		if($x01){
			$x02=strstr(str_replace($web,'',$x01),'domain');
			$x00=str_replace($x02,'',$x01);
			$h=str_replace($x00,$txt,$h);
		}else{
			$h.=$txt;
		}
	}else{
		$h=$txt;
	}
	file_put_contents($file,$h);
}
function cfgtxt($cfg=null){
	global $dsql,$sitetitle,$indextitle,$sitekeywords,$sitedescription;
	$cfg=strip_tags($cfg);
	$cfg=str_replace("'",'"',$cfg);
	$arr=explode('{697}',$cfg);
	$x01=$arr[0];
	$x02=$arr[1];
	$x03=$arr[2];
	$x04=$arr[3];
	$dsql->amd("update ay_site set title='$x01' where id='1' limit 1",usedbname());
	$dsql->amd("update ay_site set subtitle='$x02' where id='1' limit 1",usedbname());
	$dsql->amd("update ay_site set keywords='$x03' where id='1' limit 1",usedbname());
	$dsql->amd("update ay_site set description='$x04' where id='1' limit 1",usedbname());
}
function auto2dede($djt=null){
	$base=ROOT_PATH.'/base.inc.php';
	$h=xxx_url($base);
	$x='AUTO_JIEQI2DEDE';
	$x01=strstr($h,"\$$x");
	$arr=explode(';',$x01);
	$x01=$arr[0];
	$x02="\$$x='$djt'";
	$h=str_replace($x01,$x02,$h);
	file_put_contents($base,$h);
	$h=xxx_url($base);
	if(strstr($h,$x02)){
		return 'ok';
	}else{
		return 'error';
	}
}
function jieqi2pbcode($num=null){
	global $dsql,$LOGIN_X,$allletter;
	$time=get_datetime();
	$x='last'.'update';
	$articleidarr=array();
	$dd=$dsql->all("select chapterid,articleid,chaptername,chapterorder,$x from jieqi_article_chapter where display=0 order by chapterid asc limit $num",1);
	foreach($dd as $xx){
		$chapterid=$xx[chapterid];
		$articleid=$xx[articleid];
		$chaptername=$xx[chaptername];
		$chaptername=str_replace(array('\\',"'"),'',$chaptername);
		$chaptername=strip_tags($chaptername);
		$chaptername=trim($chaptername);
		$chapterorder=$xx[chapterorder];
		$$x=$xx[ $x ];
		if(strstr($chaptername,'{697}')){
			$arr=explode('{697}',$chaptername);
			$chpaterurl=$arr[0];
			$chaptername=$arr[1];
		}
		$fx=$articleid/1000;
		$fx=floor($fx);
		if($chapterorder=='1'){
			$x=$dsql->one("select sortid,postdate,articlename,author,intro,size,imgflag,fullflag,keywords from jieqi_article_article where articleid='$articleid' limit 1",1);
			$sortid=$x['sortid'];
			$postdate=$x['postdate'];
			$articlename=$x['articlename'];
			$articlename=str_replace(array('\\',"'"),'',$articlename);
			$articlename=strip_tags($articlename);
			$articlename=trim($articlename);
			$author=$x['author'];
			$intro=$x['intro'];
			$size=$x['size'];
			$imgflag=$x['imgflag'];
			$fullflag=$x['fullflag'];
			$keywords=$x['keywords'];
			if($fullflag=='1'){
				$subtitle='1';
			}else{
				$subtitle='0';
			}
			if($sortid=='0'){
				$sortid='11';
			}
			$x=$dsql->one("select id from ay_content where title='$articlename' limit 1",1);
			$id=$x['id'];
			if(!$id){
				if($allletter==3){
					$filename='669977.net';
					$fn='pbcode';
				}else{
					require_once(ROOT_PATH.'/i'.''.'ncl'.'ud'.'e/p'.''.'y'.'.php');
					if($allletter==2){
						$x=0;
					}else{
						$x=1;
					}
					$filename=py($articlename,$x);
					$x=$dsql->one("select id from ay_content where filename='$filename' limit 1",1);
					$fn=$x[id];
				}
				if($imgflag){
					$ico='files/article/image/'.$fx.'/'.$articleid.'/'.$articleid.'s.jpg';
				}
				if(strstr($keywords,'http')){
					if(!$ico){
						$ico=$keywords;
					}
					$keywords='';
				}
				$visits=rand(50,200);
				$likes=rand(1,$visits);
				$x=$visits-$likes;
				$oppose=rand(1,$x);
				$x1="acode,scode,title,author,date,ico,pics,content,tags,keywords,description,sorting,status,visits,likes,oppose,create_time,update_time,gtype,mcode,filename,subscode,subtitle,source";
				$x2="'cn','$sortid','$articlename','$author','$time','$ico','','','$keywords','$keywords','$intro',255,1,'$visits','$likes','$oppose','$time','$time',4,'18','$filename','$size','$subtitle',''";
				$o=$dsql->amd("insert into ay_content ($x1) values ($x2)");
				if($o){
					$x=$dsql->one("select id from ay_content where title='$articlename' limit 1",1);
					$id=$x[id];
					if($fn){
						if($fn=='pbcode'){
							$filename=$id;
						}else{
							$filename=$filename.$id;
						}
						$dsql->amd("update ay_content set filename='$filename' where id=$id limit 1");
					}
					if($keywords){
						if(!$id){
							$x=$dsql->one("select id from ay_content order by id desc limit 1",1);
							$id=$x[id];
						}
						$arr=explode(',',$keywords);
						foreach($arr as $tag){
							$x=$dsql->one("select id from ay_tagindex where tag='$tag' limit 1",1);
							$o=$x[id];
							if(!$o){
								$time=get_datetime();
								$dsql->amd("insert into ay_tagindex (tag,count,total,create_time) values ('$tag',0,1,'$time')");
								$x=$x=$dsql->one("select id from ay_tagindex where tag='$tag' limit 1",1);
								$o=$x[id];
							}else{
								$dsql->amd("update ay_tagindex set total=total+1 where id=$o limit 1");
							}
							if($o){
								$dsql->amd("insert into ay_taglist (tid,id,scode,tag) values ('$o','$id','$sortid','$tag')");
							}
						}
					}
				}
			}
		}else{
			$x=$dsql->one("select articlename,size,fullflag from jieqi_article_article where articleid='$articleid' limit 1",1);
			$articlename=$x['articlename'];
			$size=$x['size'];
			$fullflag=$x['fullflag'];
			if($fullflag=='1'){
				$subtitle='1';
			}else{
				$subtitle='0';
			}
			if($sortid=='0'){
				$sortid='11';
			}
			if($articlename){
				$x=$dsql->one("select id from ay_content where title='$articlename' limit 1",1);
				$id=$x['id'];
			}
			if(!$articlename){
				$dsql->amd("delete from jieqi_article_chapter where articleid=$articleid");
				continue;
			}else if(!$id){
				$dsql->amd("delete from jieqi_article_article where articleid=$articleid");
				$dsql->amd("delete from jieqi_article_chapter where articleid=$articleid");
				continue;
			}else{
				$dsql->amd("update ay_content set subscode='$size',subtitle='$subtitle',update_time='$time' where id='$id' limit 1");
			}
		}
		$x=$dsql->one("select xid from ay_article where status in(0,1) and id=$id and xtitle='$chaptername' limit 1",1);
		$xid=$x[xid];
		if(!$xid){
			$content='{697}'.$articleid.','.$chapterid;
			if($LOGIN_X && $chapterorder>99){
				$content='';
			}else if($chpaterurl){
				$content=$chpaterurl;
			}
			$x1="id,xtitle,content,cjid";
			$x2="'$id','$chaptername','$content','1'";
			$dsql->amd("insert into ay_article ($x1) values ($x2)");
		}
		$articleidarr[$articleid]=$chapterid;
	}
	foreach($articleidarr as $k => $v){
		$dsql->amd("delete from jieqi_article_chapter where articleid=$k and chapterid<$v");
		$dsql->amd("update jieqi_article_chapter set display=1 where chapterid=$v limit 1");
	}
}
function SET_MAKE_NUM($id=null){
	$base=ROOT_PATH.'/base.inc.php';
	$h=xxx_url($base);
	$preg01='\$MAKE_NUM';
	$preg02=';';
	$preg='/'.$preg01."(.*)".$preg02.'/';
	preg_match_all($preg,$h,$results);
	$num=$results[1][0];
	$num=str_replace(array('=','"','\'',' '),'',$num);
	if($id=='1'){
		$numR=$num+1;
	}else if($id=='5'){
		$numR=5;
	}else{
		$numR=$num-1;
		if($numR<1){
			$numR=1;
		}
	}
	$numRR='$MAKE_NUM=\''.$numR.'\';';
	$numXX='$MAKE_NUM=\''.$num.'\';';
	$h=str_replace($numXX,$numRR,$h);
	$numXX='$MAKE_NUM="'.$num.'";';
	$h=str_replace($numXX,$numRR,$h);
	file_put_contents($base,$h);
	$h=xxx_url($base);
	if(strstr($h,$numRR)){
		return $numR;
	}else{
		return 'error';
	}
}
function setdjt($djt=null){
	if(!$djt && $djt!='0'){
		return 'error';
	}
	$base=ROOT_PATH.'/base.inc.php';
	$h=xxx_url($base);
	$x='AUTO_STATIC';
	$x01=strstr($h,"\$$x");
	$arr=explode(';',$x01);
	$x01=$arr[0];
	$x02="\$$x='$djt'";
	$h=str_replace($x01,$x02,$h);
	file_put_contents($base,$h);
	$h=xxx_url($base);
	if(strstr($h,$x02)){
		return 'ok';
	}else{
		return 'error';
	}
}
?>
