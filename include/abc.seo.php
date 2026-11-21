<?php
/*-----------------
源码官网：669977.net
淘宝店铺：669977.taobao.com
升级日志：update.669977.net
售后QQ：1981255858
此页为全局函数，涉及整站功能，请谨慎修改
-----------------*/



// 伪静态，URL格式（不同格式，需要编写不同伪静态规则）
$REARR=array();
$REARR[category1]='{栏目URL名称}.html';//栏目首页，可用项：{栏目ID}、{栏目URL名称}
$REARR[category2]='{栏目URL名称}/{栏目ID}-{页码}.html';//栏目第二页及以上，可用项：{栏目ID}、{栏目URL名称}、{页码}
$REARR[book]='{小说URL名称}/';//书页，可用项：{栏目ID}、{栏目URL名称}、{小说ID}、{小说URL名称}、{页码}
$REARR[chapter1]='{小说URL名称}/1.html';//章节目录页，可用项：{栏目ID}、{栏目URL名称}、{小说ID}、{小说URL名称}、{页码}
$REARR[chapter2]='{小说URL名称}/1_{页码}.html';//章节目录第二页及以上，可用项：{栏目ID}、{栏目URL名称}、{小说ID}、{小说URL名称}、{页码}
$REARR[article]='{小说URL名称}/{章节ID}.html';//章节内容页，可用项：{栏目ID}、{栏目URL名称}、{小说ID}、{小说URL名称}、{章节ID}



$seoArr=array(
	'网站名'=>'sitetitle',
	'栏目名'=>'name',
	'书名'=>'title',
	'章节名'=>'xtitle',
	'SEO标题'=>'seotitle',
	'关键字'=>'keywords',
	'简介'=>'description',
	'作者'=>'author',
	'点击量'=>'visits',
	'年'=>'year',
	'连载或完结'=>'subtitle',
	'字数'=>'subscode',
	'域名'=>'domain',
);
function head4pc($scode=null,$id=null,$page=null){
	global $template,$sitetitle,$seoArr,$x2x,$HTTP_HOST;
	$year=date('Y',time());
	if($x2x){
		$domain='all';
	}else{
		$domain=$HTTP_HOST;
	}
	$file=dirname(__FILE__).'/abc.seo.txt';
	if(!file_exists($file)){
$txt='domain:all
web_name:
index_title:
index_keywords:
index_description:
category_title:好看的{栏目名}小说在线免费阅读,{栏目名}小说txt打包下载-{网站名}
category_keywords:{栏目名},最新{栏目名}小说,最新{栏目名}txt电子书,{栏目名}小说排行榜
category_description:{网站名}提供最新{栏目名}小说免费在线阅读，好看的{栏目名}小说新书txt电子书免费下载，最新{栏目名}小说排行榜。
book_title:{书名}({作者})最新章节_{书名}无弹窗广告_{栏目名}_{网站名}
book_keywords:{书名}txt下载,{书名}最新章节,{作者}作品
book_description:{网站名}为{作者}书迷提供{栏目名}小说{书名}在线免费阅读,{书名}内容介绍:{简介}。
chapter_title:{书名}({作者})最新章节_{书名}无弹窗广告阅读_{栏目名}_{网站名}
chapter_keywords:{书名}最新章节列表,{书名}
chapter_description:{书名}最新章节由{网站名}({域名})提供，{作者}写的{栏目名}{书名}是一本经典小说，本站无弹窗广告可放心阅读。
article_title:{章节名}_{书名}_{栏目名}_{网站名}({域名})
article_keywords:{章节名},{书名},{作者}
article_description:此小说是{作者}写的{栏目名}小说《{书名}》章节{章节名}，{网站名}提供的《{书名}》最新章节。';
		global $pc2url,$PC_URL;
		if($pc2url){
			foreach ($pc2url as $k => $v){
				$p2u++;
				if($p2u=='1'){
					$x=str_replace(array('http://','https://','/'),'',$PC_URL);
					if($x==$k){
						$k='all';
					}else{
						$txtxxx=$txt.PHP_EOL;
					}
				}
				$txtxxx.=str_replace('all',$k,$txt).PHP_EOL;
			}
		}else{
			$txtxxx=$txt;
		}
		file_put_contents($file,$txtxxx);
	}
	$html=file_get_contents($file);
	if(!strstr($html,'domain:'.$domain)){
		$domain='all';
	}
	$x01=strstr($html,'domain:'.$domain);
	$x01=ltrim($x01,'domain:'.$domain);
	$x02=strstr($x01,'domain:');
	$seo=str_replace($x02,'',$x01);
	$seo=trim($seo);
	$arr=explode(PHP_EOL,$seo);
	$web_name=$arr[0];
	$web_name=strstr($web_name,':');
	$web_name=ltrim($web_name,':');
	$web_name=trim($web_name);
	if($web_name){
		$sitetitle=$web_name;
	}
	$tplarr=array('index.html','book.html','chapter.html','article.html','contents.html');
	if(!$template || strstr($template,'category') || in_array($template,$tplarr)){
		if(!$template || $template=='index.html'){
			$s=1;
			$e=3;
		}else if(strstr($template,'category')){
			$s=4;
			$e=6;
		}else if(strstr($template,'book')){
			$s=7;
			$e=9;
		}else if(strstr($template,'chapter')){
			$s=10;
			$e=12;
		}else if(strstr($template,'article')){
			$s=13;
			$e=15;
		}
		for($i=$s;$i<=$e;$i++){
			$x=$arr[ $i ];
			$x=explode(':',$x);
			$c=$x[0];
			$c='web'.strstr($c,'_');
			$v=$x[1];
			$v=trim($v);
			$$c=$v;
			if(!$$c){
				global $indextitle,$sitekeywords,$sitedescription;
				if($i==1){
					$$c=$indextitle;
				}else if($i==2){
					$$c=$sitekeywords;
				}else if($i==3){
					$$c=$sitedescription;
				}
			}
		}
	}
	if(strstr($template,'shuku')){
		$web_title='全站书库'.'-'.$year.'小说大全'.'-'.$year.'最新热门小说推荐'.'-'.$sitetitle;
		$web_keywords='全站书库,'.$year.'小说大全,'.$year.'最新热门小说推荐';
		$web_description=$sitetitle.'全站书库，收集整理'.$year.'所有的热门小说，推荐精品'.$year.'最新小说。';
	}
	if(strstr($template,'paihang')){
		global $name;
		if(!$name){
			$web_title=$year.'新书榜'.'-'.$year.'最新小说排行榜'.'-'.$year.'最新小说推荐榜'.'-'.$sitetitle;
			$web_keywords=$year.'新书榜,'.$year.'最新小说排行榜,'.$year.'最新小说推荐榜';
			$web_description=$sitetitle.'免费提供'.$year.'新书榜，发布'.$year.'最新小说排行榜，收集整理'.$year.'最新小说推荐榜。';
		}else{
			$web_title=$year.$name.'新书榜'.'-'.$year.$name.'最新小说排行榜'.'-'.$year.$name.'最新小说推荐榜'.'-'.$sitetitle;
			$web_keywords=$year.$name.'新书榜,'.$year.$name.'最新小说排行榜,'.$year.$name.'最新小说推荐榜';
			$web_description=$sitetitle.'免费提供'.$year.$name.'新书榜，发布'.$year.$name.'最新小说排行榜，收集整理'.$year.$name.'最新小说推荐榜。';
		}
	}
	if(strstr($template,'quanben')){
		global $name;
		if(!$name){
			$web_title='全站'.$year.'全本小说'.'-'.'好看的'.$year.'全本小说大全'.'-'.$year.'最新热门完结小说推荐'.'-'.$sitetitle;
			$web_keywords=$year.'全本小说,好看的'.$year.'全本小说大全,'.$year.'最新热门完结小说推荐';
			$web_description=$year.$name.'全本小说,好看的'.$year.$name.'全本小说大全,'.$year.'最新热门'.$name.'完结小说推荐';
		}else{
			$web_title=$year.$name.'全本小说'.'-'.'好看的'.$year.$name.'全本小说大全'.'-'.$year.'最新热门'.$name.'完结小说推荐'.'-'.$sitetitle;
			$web_keywords=$year.$name.'全本小说,好看的'.$year.$name.'全本小说大全,'.$year.'最新热门'.$name.'完结小说推荐';
			$web_description=$sitetitle.'收集整理的'.$year.$name.'全本小说，为网友免费提供好看的'.$year.$name.'全本小说大全，并推荐'.$year.'最新热门'.$name.'完结小说。';
		}
	}
	if(strstr($template,'tuijian')){
		global $name;
		if(!$name){
			$web_title='全站推荐小说'.'-'.'热门推荐小说大全'.'-'.$year.'最新完结小说推荐'.'-'.$sitetitle;
			$web_keywords='全站推荐小说,热门推荐小说大全,'.$year.'最新完结小说推荐';
			$web_description=$sitetitle.'推荐的小说，推荐热门小说大全，推荐'.$year.'最新完结小说。';
		}else{
			$web_title='推荐'.$name.'小说'.'-'.'网友推荐的'.$name.'小说'.'-'.$year.'最新推荐'.$name.'小说'.'-'.$sitetitle;
			$web_keywords='推荐'.$name.'小说,网友推荐的'.$name.'小说,'.$year.'最新推荐,'.$name.'小说';
			$web_description=$sitetitle.'推荐的'.$name.'小说，网友推荐的'.$name.'小说，推荐'.$year.'最新的'.$name.'全本小说。';
		}
	}
	if(strstr($template,'author')){
		global $author;
		if($author=='所有作者'){
			$web_title='作者库'.'-'.'全站收录的作者'.'-'.$year.'热门作者'.'-'.$sitetitle;
			$web_keywords='作者库,全站收录的作者,'.$year.'热门作者';
			$web_description=$sitetitle.'收录的所有作者名单，并提供这些作者的最新小说，免费阅读。';
		}else{
			$web_title=$author.'作品大全'.'-'.'作者'.$author.'的小说'.'-'.$year.$author.'的新书'.'-'.$sitetitle;
			$web_keywords=$author.'作品大全,作者'.$author.'的小说,'.$year.$author.'的新书';
			$web_description=$sitetitle.'收集整理'.$author.'作品大全，免费分享作者'.$author.'的新书。';
		}
	}
	if(strstr($template,'so')){
		global $key;
		if($key){
			$x=$key;
		}
		$web_title='搜索"'.$x.'"结果'.'-'.$sitetitle;
		$web_keywords='搜索'.$x.'结果,关键字为'.$x.'的小说';
		$web_description=$sitetitle.'搜索关键字为'.$x.'的小说结果。';
	}
	if(strstr($template,'..')){
		global $my;
		$web_title='会员中心'.'-'.$sitetitle;
		$web_keywords='会员中心';
		$web_description='会员中心。';
		if($my=='look'){
			$web_title='我看过的小说'.'-'.$web_title;
			$web_keywords='我看过的小说,会员浏览记录';
			$web_description='会员中心展示会员所有的浏览记录。';
		}else if($my=='good'){
			$web_title='我点赞的小说'.'-'.$web_title;
			$web_keywords='我点赞的小说,会员点赞记录';
			$web_description='会员中心展示会员所有的点赞记录。';
		}else if($my=='bad'){
			$web_title='我鄙视的小说'.'-'.$web_title;
			$web_keywords='我鄙视的小说,会员鄙视记录';
			$web_description='会员中心展示会员所有的鄙视记录。';
		}else if($my=='fav'){
			$web_title='我收藏的小说'.'-'.$web_title;
			$web_keywords='我收藏的小说,会员收藏记录';
			$web_description='会员中心展示会员所有的收藏记录。';
		}else if($my=='umodify'){
			$web_title='修改信息'.'-'.$web_title;
			$web_keywords='修改信息';
			$web_description='在这里您可以修改会员信息。';
		}else if(strstr($my,'yc')){
			$web_title='我的原创'.'-'.$web_title;
			$web_keywords='我的原创';
			$web_description='会员中心展示会员所有的原创内容。';
		}
	}
	if(strstr($template,'tags')){
		global $tag,$ob,$obarr;
		if(!$tag){
			$x=str_replace('p','',$ob);
			$obx=$obarr[ $x-1 ];
			$web_title='Tags标签列表-'.$obx.'-'.$sitetitle;
			$web_keywords='Tags标签列表,tags'.$obx.',关键字';
			$web_description=$sitetitle.$obx.'的所有Tags标签列表。';
		}else{
			$web_title='Tags标签为“'.$tag.'”的内容'.'-'.$sitetitle;
			$web_keywords=$tag.',tags标签'.$tag.'';
			$web_description=$sitetitle.'tags标签为“'.$tag.'”的所有内容。';
		}
	}
$xxx='<title>'.$web_title.'</title>
	<meta name="keywords" content="'.$web_keywords.'" />
	<meta name="description" content="'.$web_description.'" />
';
if(!$template || $template=='index.html'){
	$xxx=trim($xxx,PHP_EOL);
}
	$tplarr=array('index.html','book.html','chapter.html','article.html','contents.html');
	if(!$template || strstr($template,'category') || in_array($template,$tplarr)){
		foreach ($seoArr as $k => $v){
			if($v!='year' && $v!='domain'){
				global $$v;
				if($v=='name'){
					global $name;
					$name=$name;
				}
			}
			if($v=='domain'){
				$domain=$HTTP_HOST;
			}
			$k='{'.$k.'}';
			$v=$$v;
			if($k=='{简介}'){
				if(!$v){
					$v=$name;
				}
			}
			$xxx=str_replace($k,$v,$xxx);
		}
	}
	$xxx=str_replace('・','',$xxx);
	return $xxx;
}
function sitemap($x=null,$wap=null){
	global $dsql,$WAP_URL,$pcbaseall,$MAKE_HTML_TIME;
	$time=time();
	$URL=$pcbaseall;
	$dir=ROOT_PATH.'/sitemap.xml';
	if($wap){
		$dir=ROOT_PATH.'/m';
		if(!is_dir($dir)){
			$dir=ROOT_PATH.'/wap';
		}
		$dir.='/sitemap.xml';
		$URL=$WAP_URL;
	}
	if($x){
		$filemtime=abs(filemtime($dir));
		$tt=$time-$filemtime;
		if($tt<$MAKE_HTML_TIME){
			return false;
		}
	}
	unlink($dir);
$xxx='
  <url>
	<loc>'.$URL.'</loc>
	<lastmod>'.date('Y-m-d',$time).'</lastmod>
	<changefreq>hourly</changefreq>
	<priority>1.0</priority>
  </url>';
	$dd=$dsql->all("select scode from ay_content_sort where status=1 order by id asc",1);
	foreach($dd as $xx)
	{
		$scode=$xx[scode];
		if(!$wap){
			$url=pbcode('category',$scode,'','all');
		}else{
			$url=pbcode('category',$scode,'','all','','','wap');
		}
$xxx.='
  <url>
	<loc>'.$url.'</loc>
	<lastmod>'.date('Y-m-d',$time).'</lastmod>
	<changefreq>hourly</changefreq>
	<priority>0.9</priority>
  </url>';
	}
	$dd=$dsql->all("select id from ay_content where status=1 order by id desc limit 500",1);
	foreach($dd as $xx)
	{
		$id=$xx[id];
		if(!$wap){
			$url=pbcode('book',$id,'','all');
		}else{
			$url=pbcode('book',$id,'','all','','','wap');
		}
$xxx.='
  <url>
	<loc>'.$url.'</loc>
	<lastmod>'.date('Y-m-d',$time).'</lastmod>
	<changefreq>hourly</changefreq>
	<priority>0.9</priority>
  </url>';
	}
	$dd=$dsql->all("select xid from ay_article order by xid desc limit 500",1);
	foreach($dd as $xx)
	{
		$xid=$xx[xid];
		if(!$wap){
			$url=pbcode('article',$xid,'','all');
		}else{
			$url=pbcode('article',$xid,'','all','','','wap');
		}
$xxx.='
  <url>
	<loc>'.$url.'</loc>
	<lastmod>'.date('Y-m-d',$time).'</lastmod>
	<changefreq>hourly</changefreq>
	<priority>0.9</priority>
  </url>';
	}
$xxx='<?xml version="1.0" encoding="gbk"?>
<?xml-stylesheet type="text/xsl" href="include/sitemap.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$xxx.'
</urlset>';
	file_put_contents($dir,$xxx);
	if(file_exists($dir)){
		return 'ok';
	}
}
?>