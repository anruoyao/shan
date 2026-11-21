<?php
/*+++++++++++++++++++++++++++++++
源码官网：669977.net
淘宝店铺：669977.taobao.com
升级日志：update.669977.net
售后QQ：1981255858
此页为全局函数，涉及整站功能，请谨慎修改
+++++++++++++++++++++++++++++++*/
$cde='3f9c02a1752cb4f277906bcff02500171';
function head4wap($scode,$id,$page){
	global $pcbaseall,$scode,$template,$sitetitle,$seoArr,$x2x,$HTTP_HOST;
	$x=$_SERVER['HTTP_USER_AGENT'];
	$x=strtolower($x);
	if(strstr($x,'html5plus')){
		$appcss='<link rel="stylesheet" type="text/css" href="'.$pcbaseall.'app/append/style/css/common.css" />';
	}
	$year=date('Y',time());
	if($x2x){
		$domain='all';
	}else{
		$domain=$HTTP_HOST;
		global $pc2url;
		if($pc2url){
			$x=array_search($HTTP_HOST,$pc2url);
			if($x){
				$domain=$x;
			}
		}
	}
	$file=dirname(__FILE__).'/../../include/abc.seo.txt';
	$html=xxx_url($file);
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
	$tplarr=array('index.html','book.html','chapter.html','article.html');
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
	if(strstr($template,'quanben')){
		$web_title='完结小说_'.$sitetitle;
		$web_keywords='完结小说,'.$sitetitle;
		$web_description=$sitetitle.'免费分享各类完结小说，各种全本小说，免费阅读。';		
	}
	if(strstr($template,'shuku')){
		$web_title='全站书库_'.$sitetitle;
		$web_keywords='全站书库,'.$sitetitle;
		$web_description=$sitetitle.'免费分享各类小说，快速更新，免费阅读。';
	}
	if(strstr($template,'login')){
		$web_title='会员登录_'.$sitetitle;
		$web_keywords='会员登录,快速登录';
		$web_description='会员登录后享有更多权利，可浏览更多精彩内容。';
	}
	if(strstr($template,'register')){
		$web_title='会员注册_'.$sitetitle;
		$web_keywords='会员注册,快速注册';
		$web_description='会员注册登录后享有更多权利，可浏览更多精彩内容。';
	}
	if(strstr($template,'uid') || strstr($template,'ucenter')){
		$web_title='会员中心_'.$sitetitle;
		$web_keywords='会员中心';
		$web_description='会员中心给会员提供更多服务，提供免费的收藏夹功能，可查看浏览记录、收藏记录、点赞记录、鄙视记录等种功能。';
	}
	if($scode=='fav'){
		$web_title='我收藏的_'.$sitetitle;
		$web_keywords='收藏记录';
		$web_description='免费查看会员所有的收藏记录。';
	}
	if($scode=='look'){
		$web_title='我看过的_'.$sitetitle;
		$web_keywords='浏览记录';
		$web_description='免费查看会员所有的浏览记录。';
	}
	if($scode=='good'){
		$web_title='我点赞的_'.$sitetitle;
		$web_keywords='点赞记录';
		$web_description='免费查看会员所有的点赞记录。';
	}
	if($scode=='bad'){
		$web_title='我鄙视的_'.$sitetitle;
		$web_keywords='鄙视记录';
		$web_description='免费查看会员所有的鄙视记录。';
	}
	if(strstr($template,'paihang')){
		$web_title='排行榜_'.$sitetitle;
		$web_keywords='排行榜,各类小说的排行榜,小说排行榜';
		$web_description=$sitetitle.'免费提供各类小说的排行榜，小说最新排行榜。';
		if($scode=='visits'){
			$web_title='点击排行榜_'.$sitetitle;
			$web_keywords='点击排行榜,各类小说的点击排行榜,小说点击排行榜';
			$web_description=$sitetitle.'免费提供各类小说的点击排行榜、小说最新排行榜。';
		}
		if($scode=='good'){
			$web_title='点赞排行榜_'.$sitetitle;
			$web_keywords='点赞排行榜,各类小说的点赞排行榜,小说点赞排行榜';
			$web_description=$sitetitle.'免费提供各类小说的鄙视排行榜、小说最新排行榜。';
		}
		if($scode=='bad'){
			$web_title='鄙视排行榜_'.$sitetitle;
			$web_keywords='鄙视排行榜,各类小说的鄙视排行榜,小说鄙视排行榜';
			$web_description=$sitetitle.'免费提供各类小说的鄙视排行榜、小说最新排行榜。';
		}
	}
	if(strstr($template,'boy')){
		$web_title='活跃会员_'.$sitetitle;
		$web_keywords='活跃会员,最活跃会员排行榜';
		$web_description='统计本站最活跃的会员。';
	}
	if(strstr($template,'all')){
		$web_title='全部分类_'.$sitetitle;
		$web_keywords='全部分类,各种搞笑小说分类';
		$web_description=$sitetitle.'免费提供各种搞笑小说分类。';
	}
	if(strstr($template,'score')){
		$web_title='积分说明_'.$sitetitle;
		$web_keywords='积分说明';
		$web_description=$sitetitle.'独特的积分功能，增加会员活跃度。';
	}
	if(strstr($template,'so')){
		global $key;
		$web_title='搜索“'.$key.'”结果_'.$sitetitle;
		$web_keywords=$key.',搜索'.$key.'结果';
		$web_description=$sitetitle.'提供所有关键字为'.$key.'的小说，免费阅读。';
	}
	if(strstr($template,'author')){
		global $author;
		$web_title='作者"'.$author.'"结果_'.$sitetitle;
		$web_keywords=$key.',作者'.$author.'结果';
		$web_description=$sitetitle.'提供所有作者为'.$author.'的小说，免费阅读。';
	}
	if(strstr($template,'shujia')){
		global $name;
		$web_title=$name.'_'.$sitetitle;
		$web_keywords=$name.',足迹,我的足迹,看过的小说';
		$web_description='这里有您所有的阅读记录，书签时时保存，看小说不再迷失，尽情阅读。';
	}
	if(strstr($template,'ting')){
		$web_title='听书_可以听的小说_热门小说朗读_'.$sitetitle;
		$web_keywords='听书,听小说,小说朗读';
		$web_description=$sitetitle.'免费提供各种热门小说听书朗读。';
	}
	if(strstr($template,'tags')){
		global $tag,$ob,$obarr;
		if(!$tag){
			$obx=$obarr[ $ob ];
			$web_title='Tags标签列表-'.$obx.'-'.$sitetitle;
			$web_keywords='Tags标签列表,tags'.$obx.',关键字';
			$web_description=$sitetitle.$obx.'的所有Tags标签列表。';
		}else{
			$web_title='Tags标签为“'.$tag.'”的内容'.'-'.$sitetitle;
			$web_keywords=$tag.',tags标签'.$tag.'';
			$web_description=$sitetitle.'tags标签为“'.$tag.'”的所有内容。';
		}
	}
	if(strstr($template,'about')){
		global $name;
		$web_title=$name.'_'.$sitetitle;
		$web_keywords=$name;
		$web_description=$sitetitle.'的'.$name.'相关内容。';
	}
	if(strstr($template,'umodify')){
		$web_title='修改密码_'.$sitetitle;
		$web_keywords='修改密码';
		$web_description=$sitetitle.'提供会员修改密码功能。';
	}
	if(strstr($template,'yc')){
		$web_title='投稿_发布原创内容_'.$sitetitle;
		$web_keywords='投稿,原创内容';
		$web_description=$sitetitle.'提供允许会员投稿发布原创内容。';
	}
	if(strstr($template,'zt')){
		global $name;
		if(!$name){
			$web_title='专题库-'.$sitetitle;
			$web_keywords='专题库';
			$web_description=$sitetitle.'专题库';
		}else{
			$web_title='专题为“'.$name.'”的内容'.'-'.$sitetitle;
			$web_keywords=$name;
			$web_description=$sitetitle.'专题为“'.$name.'”的所有内容。';
		}
	}
	if(strstr($template,'ping')){
		$web_title='书评_'.$sitetitle;
		$web_keywords='书评';
		$web_description=$sitetitle.'提供允许会员发布书评。';
	}
$xxx='<title>'.$web_title.'</title>
	<meta name="keywords" content="'.$web_keywords.'" />
	<meta name="description" content="'.$web_description.'" />
	'.$appcss.'
';
$xxx=trim($xxx);
$xxx.="\n";
	$tplarr=array('index.html','book.html','chapter.html','article.html');
	if(!$template || strstr($template,'category') || in_array($template,$tplarr)){
		foreach ($seoArr as $k => $v){
			if($v!='year' && $v!='domain'){
				global $$v;
				if($v=='name' && !$name){
					global $name;
					$name=$name;
				}
			}
			if($v=='domain'){
				$domain=$HTTP_HOST;
			}
			$k='{'.$k.'}';
			$v=$$v;
			$xxx=str_replace($k,$v,$xxx);
		}
	}
	return $xxx;
}
?>