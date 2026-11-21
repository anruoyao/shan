<?php
global $uid,$my,$page,$QUERY_STRING_ARR,$is4wap;
$x='QUERY_STRING';
$x=$_SERVER[ $x ];
$x=str_replace('member/','',$x);
$QUERY_STRING_ARR=explode('/',$x);
$my=$QUERY_STRING_ARR[0];
$page=$QUERY_STRING_ARR[1];
$is4wap=is_mobile();

// 作者端工作台图标（内联 SVG，避免额外图片与 CDN 依赖）
function aw_icon($name=''){
	$icons=array(
		'home'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"/><path d="M5.5 9.5V21h13V9.5"/><path d="M9.5 21v-6h5v6"/></svg>',
		'book'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4.5h6a2 2 0 0 1 2 2V21a3 3 0 0 0-3-3H4z"/><path d="M20 4.5h-6a2 2 0 0 0-2 2V21a3 3 0 0 1 3-3h5z"/></svg>',
		'plus'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>',
		'chart'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10M10 20V4M16 20v-7"/><path d="M2 20h20"/></svg>',
		'wallet'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 9h18"/><circle cx="17" cy="14" r="1.2"/></svg>',
		'user'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>',
		'clock'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
		'heart'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 5.6a5.4 5.4 0 0 0-7.6 0L12 6.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6L12 21l8.8-7.8a5.4 5.4 0 0 0 0-7.6z"/></svg>',
		'star'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3 6.4 20.2l1.1-6.2L3 9.6l6.2-.9z"/></svg>',
		'thumbup'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 10v11H4V10z"/><path d="M7 11l4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2.3l-1.2 6A2 2 0 0 1 16.8 20H7"/></svg>',
		'thumbdown'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 14V3h3v11z"/><path d="M17 13l-4 7a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2.3l1.2-6A2 2 0 0 1 7.2 4H17"/></svg>',
		'comment'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 0 1-8 8H7l-4 2 1.2-4.4A8 8 0 1 1 21 12z"/></svg>',
		'bell'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 1 0-12 0c0 6-2 7-2 7h16s-2-1-2-7"/><path d="M10 20a2 2 0 0 0 4 0"/></svg>',
		'logout'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>',
		'edit'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>',
		'trash'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 15h10l1-15"/><path d="M10 11v6M14 11v6"/></svg>',
		'lock'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>',
		'arrow'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		'check'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 12 5 5L20 6"/></svg>',
		'calendar'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>',
		'coin'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9 9h6M9 12h6M12 9v7"/></svg>',
		'spark'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M18.4 5.6l-2.8 2.8M8.4 15.6l-2.8 2.8"/></svg>',
		'menu'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>',
		'warning'=>'<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 2.5 20h19z"/><path d="M12 9v5M12 17.5v.2"/></svg>'
	);
	return isset($icons[$name])?$icons[$name]:'';
}

// 作者端顶栏
function author_topbar($mode='author'){
	global $dsql,$pcbase;
	$isMember=$mode=='member';
	$uid=session('pboot_ucode');
	$nickname='';
	$headpic='';
	$sign=0;
	$gname='';
	if($uid){
		$x=$dsql->one("select username,nickname,headpic,sign,gid from ay_member where ucode=$uid limit 1",1);
		$nickname=$x[nickname]?:$x[username];
		$nickname=htmlspecialchars($nickname,ENT_QUOTES,'UTF-8');
		$headpic=$x[headpic];
		$sign=$x[sign];
		if($isMember && $x[gid]){
			$g=$dsql->one("select gname from ay_member_group where gcode=".(int)$x[gid]." limit 1",1);
			$gname=htmlspecialchars($g['gname'],ENT_QUOTES,'UTF-8');
		}
	}
	$headpic=ico($headpic);
	$signname='读者';
	if($sign==1){
		$signname='待审作者';
	}else if($sign==2){
		$signname='普通作者';
	}else if($sign==3){
		$signname='签约作者';
	}else if($sign==4){
		$signname='签约审核中';
	}
	$base=$pcbase ? $pcbase : '/';
	$brand=$isMember?'个人中心':'作家专区';
	$chipRole=$isMember?($gname?:'书友'):$signname;
	$xxx='<header class="aw-topbar"><div class="aw-topbar__inner">';
	$xxx.='<a class="aw-brand" href="'.$base.'" title="返回书城首页">';
	$xxx.='<span class="aw-brand__mark">山</span>';
	$xxx.='<span class="aw-brand__text"><strong>'.$brand.'</strong><em>山海书盟</em></span></a>';
	$xxx.='<nav class="aw-topnav" aria-label="站点导航">';
	$xxx.='<a href="'.$base.'">首页</a>';
	if($isMember){
		$xxx.='<a href="'.pbcode('member','ucenter').'">作者中心</a>';
		$xxx.='<a href="'.$base.'jieshao.html">作家福利</a>';
		$xxx.='<a href="'.$base.'connect.html">联系编辑</a>';
	}else{
		$xxx.='<a href="'.$base.'jieshao.html">作家福利</a>';
		$xxx.='<a href="'.pbcode('member','yc').'">作品管理</a>';
		$xxx.='<a href="'.pbcode('member','index').'">个人中心</a>';
		$xxx.='<a href="'.$base.'connect.html">联系编辑</a>';
	}
	$xxx.='</nav>';
	$xxx.='<div class="aw-topbar__right">';
	if($uid){
		if($isMember){
			$xxx.='<a class="aw-topbar__cta" href="'.pbcode('member','money').'">'.aw_icon('wallet').'充值中心</a>';
		}else{
			$xxx.='<a class="aw-topbar__cta" href="'.pbcode('member','yc_add').'">'.aw_icon('plus').'发布作品</a>';
		}
		$xxx.='<a class="aw-userchip" href="'.pbcode('member',$isMember?'index':'ucenter').'" title="'.($isMember?'个人中心':'作者中心').'">';
		$xxx.='<img crs="'.$headpic.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$nickname.'">';
		$xxx.='<span><strong>'.$nickname.'</strong><em>'.$chipRole.'</em></span></a>';
		$xxx.='<a class="aw-btn aw-btn--sm aw-btn--ghost" href="'.pbcode('member','logout').'" onclick="return tipx();" title="安全退出">退出</a>';
	}else{
		$xxx.='<div class="aw-topbar__login"><a href="'.pbcode('member','login').'">登录</a><a href="'.pbcode('member','register').'">注册</a></div>';
	}
	$xxx.='<button class="aw-menu-toggle" id="awMenuToggle" type="button" aria-label="展开侧栏" aria-expanded="false">'.aw_icon('menu').'</button>';
	$xxx.='</div></div></header>';
	return $xxx;
}

// 个人中心首页数据：昵称、头像、会员等级、金币与积分
function member_home_data(){
	global $dsql;
	$data=array(
		'nickname'=>'游客',
		'headpic'=>ico(),
		'gname'=>'',
		'jinbi'=>0,
		'score'=>0
	);
	$uid=session('pboot_ucode');
	if(!$uid){
		return $data;
	}
	$m=$dsql->one("select username,nickname,headpic,gid,jinbi,score from ay_member where ucode=$uid limit 1",1);
	if(!$m){
		return $data;
	}
	$data['nickname']=htmlspecialchars($m['nickname']?:$m['username'],ENT_QUOTES,'UTF-8');
	$data['headpic']=ico($m['headpic']);
	$data['jinbi']=intval($m['jinbi']);
	$data['score']=intval($m['score']);
	if($m['gid']){
		$g=$dsql->one("select gname from ay_member_group where gcode=".(int)$m['gid']." limit 1",1);
		if($g){
			$data['gname']=htmlspecialchars($g['gname'],ENT_QUOTES,'UTF-8');
		}
	}
	return $data;
}

// 作者端首页数据：创作天数、累计字数、作品粉丝等
function author_home_data(){
	global $dsql;
	$data=array(
		'nickname'=>'游客',
		'headpic'=>ico(),
		'sign'=>0,
		'sign_label'=>'读者',
		'sign_class'=>'is-plain',
		'gname'=>'',
		'days'=>0,
		'words'=>0,
		'fans'=>0,
		'works'=>0,
		'jinbi'=>0,
		'score'=>0,
		'author_title'=>'',
		'author_honor'=>'',
		'author_bio'=>'',
		'idcard'=>''
	);
	$uid=session('pboot_ucode');
	if(!$uid){
		return $data;
	}
	$m=$dsql->one("select username,nickname,headpic,sign,gid,register_time,jinbi,score,author_title,author_honor,author_bio,idcard from ay_member where ucode=$uid limit 1",1);
	if(!$m){
		return $data;
	}
	$data['nickname']=htmlspecialchars($m['nickname']?:$m['username'],ENT_QUOTES,'UTF-8');
	$data['headpic']=ico($m['headpic']);
	$data['sign']=(int)$m['sign'];
	$data['jinbi']=(int)$m['jinbi'];
	$data['score']=(int)$m['score'];
	$data['author_title']=htmlspecialchars($m['author_title'],ENT_QUOTES,'UTF-8');
	$data['author_honor']=htmlspecialchars($m['author_honor'],ENT_QUOTES,'UTF-8');
	$data['author_bio']=htmlspecialchars($m['author_bio'],ENT_QUOTES,'UTF-8');
	$data['idcard']=htmlspecialchars($m['idcard'],ENT_QUOTES,'UTF-8');
	if($m['gid']){
		$g=$dsql->one("select gname from ay_member_group where gcode=".(int)$m['gid']." limit 1",1);
		if($g){
			$data['gname']=htmlspecialchars($g['gname'],ENT_QUOTES,'UTF-8');
		}
	}
	if($data['sign']==1){
		$data['sign_label']='待审作者';
		$data['sign_class']='is-pending';
	}else if($data['sign']==2){
		$data['sign_label']='普通作者';
		$data['sign_class']='is-plain';
	}else if($data['sign']==3){
		$data['sign_label']='签约作者';
		$data['sign_class']='is-ok';
	}else if($data['sign']==4){
		$data['sign_label']='签约审核中';
		$data['sign_class']='is-pending';
	}
	$x=$dsql->one("select count(*) c,sum(cast(subscode as unsigned)) w,sum(score) f,min(create_time) first_time from ay_content where uid=$uid and status in(0,1,2)",1);
	$data['works']=intval($x['c']);
	$data['words']=intval($x['w']);
	$data['fans']=intval($x['f']);
	$first=$x['first_time'];
	if(!$first || strpos($first,'0000')===0){
		$first=$m['register_time'];
	}
	$ts=strtotime($first);
	$data['days']=$ts?max(1,floor((time()-$ts)/86400)+1):0;
	return $data;
}

// 作者等级：0级未签约，1级签约作者，2-5级按积分自动升级
function author_level_data($score=0,$sign=0,$title='',$honor=''){
	$score=intval($score);
	$sign=intval($sign);
	$levels=array(
		1=>array('name'=>'Lv1 签约作者','min'=>0,'next'=>5000),
		2=>array('name'=>'Lv2 作家','min'=>5000,'next'=>10000),
		3=>array('name'=>'Lv3 作家','min'=>10000,'next'=>30000),
		4=>array('name'=>'Lv4 作家','min'=>30000,'next'=>50000),
		5=>array('name'=>'Lv5 作家','min'=>50000,'next'=>0)
	);
	if($sign!=3){
		$level=0;
		$label='Lv0 未签约';
		$min=0;
		$next=5000;
		$progress=0;
		$need=5000;
	}else{
		if($score>=50000){
			$level=5;
		}else if($score>=30000){
			$level=4;
		}else if($score>=10000){
			$level=3;
		}else if($score>=5000){
			$level=2;
		}else{
			$level=1;
		}
		$label=$levels[$level]['name'];
		$min=$levels[$level]['min'];
		$next=$levels[$level]['next'];
		if($next){
			$progress=min(100,max(0,round(($score-$min)/($next-$min)*100)));
			$need=max(0,$next-$score);
		}else{
			$progress=100;
			$need=0;
		}
	}
	$honors=array();
	if($honor){
		foreach(explode(',',$honor) as $h){
			$h=trim($h);
			if($h){
				$honors[]=htmlspecialchars($h,ENT_QUOTES,'UTF-8');
			}
		}
	}
	return array(
		'level'=>$level,
		'label'=>$label,
		'score'=>$score,
		'next'=>$next,
		'need'=>$need,
		'progress'=>$progress,
		'title'=>htmlspecialchars($title,ENT_QUOTES,'UTF-8'),
		'honors'=>$honors,
		'levels'=>$levels
	);
}

// 稿费汇总数据与展示
function author_gf_data($uid=0,$bookId=0){
	global $dsql;
	$uid=intval($uid);
	$bookId=intval($bookId);
	$data=array(
		'rows'=>array(),
		'total'=>0,
		'settled'=>0,
		'this_month'=>0,
		'last_month'=>0
	);
	if(!$uid){
		return $data;
	}
	$where="c.uid=$uid";
	if($bookId){
		$where.=" and g.id=$bookId";
	}
	$dd=$dsql->all("select g.*,c.title from ay_gf g left join ay_content c on g.id=c.id where $where order by g.idx desc",1);
	$curYm=date('Y-n');
	$lastYm=date('Y-n',strtotime('first day of last month'));
	foreach($dd as $xx){
		$yf=$xx['yf'];
		$jeNum=floatval(str_replace(',','',$xx['je']));
		$dyNum=floatval(str_replace(',','',$xx['dy']));
		$qdNum=floatval(str_replace(',','',$xx['qd']));
		$ptNum=floatval(str_replace(',','',$xx['pt']));
		$partTotal=$dyNum+$qdNum+$ptNum;
		$ym='';
		if(preg_match('/(\d{4})年(\d{1,2})月/',$yf,$m)){
			$ym=$m[1].'-'.intval($m[2]);
		}else if(preg_match('/(\d{4})-(\d{1,2})/',$yf,$m)){
			$ym=$m[1].'-'.intval($m[2]);
		}
		$isPending=($ym==$curYm);
		$isSettled=($ym && $ym!=$curYm);
		$row=array(
			'yf'=>$yf,
			'book'=>htmlspecialchars($xx['title'],ENT_QUOTES,'UTF-8'),
			'je'=>$xx['je'],
			'dy'=>$xx['dy'],
			'qd'=>$xx['qd'],
			'pt'=>$xx['pt'],
			'update_time'=>$xx['update_time'],
			'pending'=>$isPending,
			'settled'=>$isSettled,
			'dy_pct'=>$partTotal>0?round($dyNum/$partTotal*100,1):0,
			'qd_pct'=>$partTotal>0?round($qdNum/$partTotal*100,1):0,
			'pt_pct'=>$partTotal>0?round($ptNum/$partTotal*100,1):0,
			'data_total'=>$partTotal>0?100:0
		);
		$data['rows'][]=$row;
		$data['total']+=$jeNum;
		if($isSettled){
			$data['settled']+=$jeNum;
		}
		if($ym==$curYm){
			$data['this_month']+=$jeNum;
		}
		if($ym==$lastYm){
			$data['last_month']+=$jeNum;
		}
	}
	return $data;
}

function author_gf_summary_html($data){
	$xxx='<div class="aw-gf-summary aw-section">';
	$xxx.='<div class="aw-gf-summary__item"><span>总稿费</span><strong>¥'.number_format($data['total'],2).'</strong></div>';
	$xxx.='<div class="aw-gf-summary__item"><span>已结算</span><strong>¥'.number_format($data['settled'],2).'</strong></div>';
	$xxx.='<div class="aw-gf-summary__item"><span>本月稿费</span><strong>¥'.number_format($data['this_month'],2).'</strong></div>';
	$xxx.='<div class="aw-gf-summary__item"><span>上月稿费</span><strong>¥'.number_format($data['last_month'],2).'</strong></div>';
	$xxx.='</div>';
	return $xxx;
}

function author_gf_cards_html($data,$showBook=false){
	if(empty($data['rows'])){
		return '<div class="aw-empty">'.aw_icon('wallet').'<span>暂无稿费结算记录</span></div>';
	}
	$xxx='<div class="aw-gf-list">';
	foreach($data['rows'] as $row){
		$dyEnd=$row['dy_pct'];
		$qdEnd=$row['dy_pct']+$row['qd_pct'];
		if($row['data_total']>0){
			$donut='conic-gradient(var(--aw-blue) 0 '.$dyEnd.'%, var(--aw-green) '.$dyEnd.'% '.$qdEnd.'%, var(--aw-orange) '.$qdEnd.'% 100%)';
		}else{
			$donut='conic-gradient(#e5e6eb 0 100%)';
		}
		$statusClass=$row['pending']?'is-pending':'is-ok';
		$statusText=$row['pending']?'待结算':'已结算';
		$xxx.='<div class="aw-gf-card">';
		$xxx.='<div class="aw-gf-card__head">';
		$xxx.='<div class="aw-gf-card__title"><strong>'.$row['yf'].'</strong>'.($showBook?'<span>'.$row['book'].'</span>':'').'</div>';
		$xxx.='<span class="aw-badge '.$statusClass.'">'.$statusText.'</span>';
		$xxx.='</div>';
		$xxx.='<div class="aw-gf-card__body">';
		$xxx.='<div class="aw-donut" style="background:'.$donut.'"><span>¥'.$row['je'].'</span></div>';
		$xxx.='<div class="aw-gf-legend">';
		$xxx.='<div><i class="is-dy"></i><span>订阅收入</span><strong>¥'.$row['dy'].'</strong><em>'.$row['dy_pct'].'%</em></div>';
		$xxx.='<div><i class="is-qd"></i><span>渠道收入</span><strong>¥'.$row['qd'].'</strong><em>'.$row['qd_pct'].'%</em></div>';
		$xxx.='<div><i class="is-pt"></i><span>平台激励</span><strong>¥'.$row['pt'].'</strong><em>'.$row['pt_pct'].'%</em></div>';
		$xxx.='</div>';
		$xxx.='</div>';
		$xxx.='<div class="aw-gf-card__foot"><span>结算时间：'.$row['update_time'].'</span><span>发放总金额：¥'.$row['je'].'</span></div>';
		$xxx.='</div>';
	}
	$xxx.='</div>';
	return $xxx;
}

// 签约条件检查
function author_sign_conditions($uid=0){
	global $dsql;
	$uid=intval($uid);
	$data=array(
		'realname'=>false,
		'words'=>0,
		'has_words'=>false,
		'info'=>false,
		'eligible'=>false,
		'work'=>array(),
		'sign'=>0
	);
	if(!$uid){
		return $data;
	}
	$m=$dsql->one("select xingming,idcard,usermobile,sign from ay_member where ucode=$uid limit 1",1);
	$data['sign']=intval($m['sign']);
	$data['realname']=($m['xingming'] && $m['idcard'] && $m['usermobile']);
	$dd=$dsql->all("select id,title,subscode,ico,description,status from ay_content where uid=$uid and status in(1,2) order by subscode desc",1);
	$maxWords=0;
	$infoOk=false;
	$eligibleWork=array();
	foreach($dd as $x){
		$w=intval($x['subscode']);
		if($w>$maxWords){
			$maxWords=$w;
		}
		$okInfo=($x['ico'] && trim(strip_tags($x['description'])));
		if($okInfo){
			$infoOk=true;
		}
		if($w>=10000 && $okInfo){
			$eligibleWork=$x;
		}
	}
	$data['words']=$maxWords;
	$data['has_words']=($maxWords>=10000);
	$data['info']=$infoOk;
	$data['work']=$eligibleWork;
	$data['eligible']=($data['realname'] && $data['has_words'] && $data['info'] && !empty($eligibleWork));
	return $data;
}

function author_gf_rules_html(){
	$xxx='<section class="aw-card aw-section">';
	$xxx.='<div class="aw-card__head"><h2 class="aw-card__title">结算规则</h2><span class="aw-card__hint">每月自动生成</span></div>';
	$xxx.='<div class="aw-details__body">';
	$xxx.='<p>本站稿费与全勤每月 1 号自动生成上月结算单，默认状态为待结算，后台结算后手动修改为已结算。</p>';
	$xxx.='<p>本站稿费未满 20 元的默认结转下月结算。</p>';
	$xxx.='<p>全勤规则：本月月更 ≥ 6 万字，全勤为上月渠道费总额的 5%；月更 ≥ 9 万字为 10%；月更 ≥ 12 万字为 15%。</p>';
	$xxx.='<p>后台可手动发布新的结算清单，用于结算渠道费与各项激励金。</p>';
	$xxx.='</div></section>';
	return $xxx;
}

// 数字缩写：万 / 亿
function aw_num($num=0){
	$num=intval($num);
	if($num>=100000000){
		return round($num/100000000,1).'亿';
	}
	if($num>=10000){
		return round($num/10000,1).'万';
	}
	return $num;
}

// 作者端首页：最近作品
function author_recent_works($uid=0,$limit=3,$showActions=false){
	global $dsql;
	$uid=intval($uid);
	$limit=max(1,min(20,intval($limit)));
	if(!$uid){
		return '';
	}
	$m=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
	$sign=intval($m['sign']);
	$dd=$dsql->all("select id,title,status,ico,visits,subscode from ay_content where uid=$uid and status in(0,1,2) order by id desc limit $limit",1);
	if(!$dd){
		return '';
	}
	$xxx='<div class="aw-work-mini-list">';
	foreach($dd as $xx){
		$status=intval($xx['status']);
		$status_text=$status==1?'已通过':($status==2?'审核中':'未通过');
		$status_class=$status==1?'is-ok':($status==2?'is-pending':'is-bad');
		$cover=$xx['ico']?ico($xx['ico']):ico();
		$title=htmlspecialchars($xx['title'],ENT_QUOTES,'UTF-8');
		$url=pbcode('member','yc_edit',$xx['id']);
		$xxx.='<div class="aw-work-mini">';
		$xxx.='<a class="aw-work-mini__cover" href="'.$url.'"><img crs="'.$cover.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title.'"></a>';
		$xxx.='<div class="aw-work-mini__body"><a class="aw-work-mini__title" href="'.$url.'">'.$title.'</a><span class="aw-work-mini__meta">'.$xx['visits'].' 次访问 · '.aw_num($xx['subscode']).' 字</span>';
		if($showActions){
			$xxx.='<div class="aw-work-mini__actions">';
			$xxx.='<a href="'.pbcode('member','yc_add',$xx['id']).'">发布章节</a>';
			$xxx.='<a href="'.pbcode('member','yc_chapter',$xx['id']).'">章节管理</a>';
			$xxx.='<a href="'.$url.'">作品信息</a>';
			$xxx.='<a href="'.pbcode('member','yc_info',$xx['id']).'">作品数据</a>';
			$xxx.='<a href="'.pbcode('member','yc_gf',$xx['id']).'">查看稿费</a>';
			if($sign==2){
				$xxx.='<a href="'.pbcode('member','sign').'">申请签约</a>';
			}else if($sign==3){
				$xxx.='<span class="is-signed">已签约</span>';
			}
			$xxx.='</div>';
		}
		$xxx.='</div>';
		$xxx.='<span class="aw-badge '.$status_class.'">'.$status_text.'</span>';
		$xxx.='</div>';
	}
	$xxx.='</div>';
	return $xxx;
}

function memnav(){
	global $dsql;
	$num=6;
	$dd=$dsql->all("select scode,name from ay_content_sort where status=1 and pcode=0 order by sorting asc,id asc",1);
	foreach($dd as $xx){
		$ii++;
		if($ii>$num){
			break;
		}
		$scode=$xx[scode];
		$name=$xx[name];
		$url=pbcode('category',$scode);
		$xxx.='<li><a href="'.$url.'" title="'.$name.'">'.$name.'</a></li>';
	}
	if($ii>$num){
		$xxx.='<li class="last"><a href="./"></a></li>';
	}
	return $xxx;
}
function hb($my=1){
	global $dsql,$is4wap;
	$uid=session('pboot_ucode');
	if($uid){
		$x=$dsql->one("select * from ay_member where ucode=$uid limit 1",1);
		$username=$x[username];
		$nickname=$x[nickname];
		$headpic=$x[headpic];
		$headpic=ico($headpic);
		$score=$x[score];
		$jinbi=$x[jinbi];
		$register_time=$x[register_time];
		$last_login_time=$x[last_login_time];
		$gid=$x[gid];
		$sign=$x[sign];
		$autologin=$x[autologin];
		if($autologin){
			$autologin='<div class="autologin">您当前是游客登录状态！数据仅能临时保存！会员<a href="'.pbcode('member','login').'">注册/登录</a></div>';
		}
		if($sign==1){
			$qian='<font>待审作者</font>';
		}else if($sign==2){
			$qian='<font>普通作者</font><a href="'.pbcode('member','sign').'">申请签约</a>';
		}else if($sign==3){
			$qian='<font><i></i>签约作者</font>';
		}else if($sign==4){
			$qian='<font>签约作者审核中</font><a href="'.pbcode('member','yc','sign/2').'" onclick="return tipx(8);">取消签约申请</a>';
		}
		$x=$dsql->one("select gname from ay_member_group where gcode=$gid limit 1",1);
		$gname=$x[gname];
	}else{
		$username='游客';
		$nickname='游客';
		$headpic=ico();
		$score=0;
		$jinbi=0;
		$gname='游客';
		$register_time=get_datetime();
		$last_login_time=get_datetime();
	}
	// 作者端工作台：新版侧栏用户卡
	if(defined('AUTHOR_LAYOUT')){
		membergid($score);
		$nickname=$nickname?:$username;
		$nickname_aw=htmlspecialchars($nickname,ENT_QUOTES,'UTF-8');
		$role='';
		if(!$uid){
			$role='<span class="aw-role aw-role--muted">游客</span>';
		}else if($sign==1){
			$role='<span class="aw-role">待审作者</span>';
		}else if($sign==2){
			$role='<span class="aw-role">普通作者</span><a class="aw-role__apply" href="'.pbcode('member','sign').'">申请签约</a>';
		}else if($sign==3){
			$role='<span class="aw-role">签约作者</span>';
		}else if($sign==4){
			$role='<span class="aw-role">签约审核中</span><a class="aw-role__apply" href="'.pbcode('member','yc','sign/2').'" onclick="return tipx(8);">取消申请</a>';
		}else{
			$role='<span class="aw-role aw-role--muted">'.htmlspecialchars($gname?:'读者',ENT_QUOTES,'UTF-8').'</span>';
		}
		$card='<div class="aw-profile-card">';
		$card.='<div class="aw-profile-avatar"><a href="'.($uid?pbcode('member','umodify'):pbcode('member','login')).'" title="修改头像"><img crs="'.$headpic.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$nickname_aw.'"></a></div>';
		$card.='<div class="aw-profile-name">'.$nickname_aw.'</div>';
		$card.='<div class="aw-profile-roles">'.$role.'</div>';
		$card.='<div class="aw-profile-stats">';
		$card.='<div class="aw-profile-stat"><span>积分</span><strong><a href="'.pbcode('member','vip').'">'.$score.'</a></strong></div>';
		$card.='<div class="aw-profile-stat"><span>金币</span><strong><a href="'.pbcode('member','pay').'">'.$jinbi.'</a></strong></div>';
		$card.='</div>';
		if($uid){
			$card.='<div class="aw-profile-actions"><a href="'.pbcode('member','money').'">充值</a><a href="'.pbcode('member','umodify').'">修改资料</a></div>';
			$card.='<div class="aw-profile-actions"><a href="'.pbcode('member','info').'">作者资料</a><a href="'.pbcode('member','yc').'">我的作品</a></div>';
		}else{
			$card.='<div class="aw-profile-actions"><a href="'.pbcode('member','login').'">登录</a><a href="'.pbcode('member','register').'">注册</a></div>';
		}
		if(!empty($autologin)){
			$card.='<div class="aw-profile-note">您当前是游客登录状态，数据仅临时保存。<a href="'.pbcode('member','login').'">注册/登录</a></div>';
		}
		$card.='</div>';
		return $card;
	}
	if($my==1){
		membergid($score);
		$url=pbcode('member','index');
		if($is4wap){
			$xxx='
	        <div class="face"><a href="'.$url.'"><img crs="'.$headpic.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;"></a></div>
	        <div class="info">
	            <p>'.$nickname.$qian.'</p>
	            <p><a href="'.pbcode('member','vip').'">'.$score.'</a><a href="'.pbcode('member','pay').'">'.$jinbi.'</a></p>
	            <p><a href="'.pbcode('member','pay').'">充值</a><a href="'.pbcode('member','umodify').'">修改资料</a></p>
	        </div>';
		}else{
			$xxx='
		    <div class="mname"><a href="'.$url.'">'.$nickname.'</a></div>
		    <div class="face"><a href="'.$url.'"><img crs="'.$headpic.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;"></a></div>
		    <div class="info">
		        <p>积分：<a href="'.pbcode('member','vip').'">'.$score.'</a></p>
		        <p>金币：<a href="'.pbcode('member','pay').'">'.$jinbi.'</a></p>
		        <p><a href="'.pbcode('member','pay').'">充值</a><a href="'.pbcode('member','umodify').'">修改资料</a></p>
		    </div>';
		}
		$xxx.=$autologin;
	}else{
		$url=pbcode('member','umodify');
		$xxx='
        <div class="mface">
            <a href="'.$url.'">
                <img crs="'.$headpic.'?'.time().'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">
                <span>更换头像</span>
            </a>
        </div>
        <div class="minfo">
            <p><span>账号:</span>'.$username.$qian.'</p>
            <p><span>昵称:</span>'.$nickname.'<a href="'.$url.'">修改</a></p>
            <p><span>会员等级:</span>'.$gname.'</p>
            <p><span>注册时间:</span>'.$register_time.'</p>
            <p><span>最近登录:</span>'.$last_login_time.'</p>
        </div>';
        $xxx.=$autologin;
	}
	return $xxx;
}
function lnav(){
	global $my,$is4wap,$pay_ready,$memberadd,$DISPLAY_COM;
	$uid=session('pboot_ucode');
	$x=' class="cur"';
	$arr=array();
	$url=array();
	if($my=='look'){
		$arr[2]=$x;
	}else if($my=='good'){
		$arr[3]=$x;
	}else if($my=='bad'){
		$arr[4]=$x;
	}else if($my=='fav'){
		$arr[5]=$x;
	}else if(strstr($my,'yc')){
		$arr[6]=$x;
	}else if(strstr($my,'money') || strstr($my,'pay')){
		$arr[7]=$x;
	}else if(strstr($my,'vip')){
		$arr[8]=$x;
	}else if(strstr($my,'umodify')){
		$arr[9]=$x;
	}else if(strstr($my,'cmt') || strstr($my,'re')){
		$arr[10]=$x;
	}else if(strstr($my,'tg')){
		$arr[11]=$x;
	}else{
		$arr[1]=$x;
	}
	$url[1]=defined('AUTHOR_LAYOUT')?pbcode('member','ucenter'):pbcode('member','index');
	$url[2]=pbcode('member','look');
	$url[3]=pbcode('member','good');
	$url[4]=pbcode('member','bad');
	$url[5]=pbcode('member','fav');
	$url[6]=pbcode('member','yc');
	$url[7]=pbcode('member','money');
	$url[8]=pbcode('member','vip');
	$url[9]=pbcode('member','umodify');
	$url[10]=pbcode('member','cmt');
	$url[11]=pbcode('member','tg');
	if($uid){
		$logout='<li><a href="'.pbcode('member','logout').'" title="安全退出" onclick="return tipx();">安全退出</a></li>';
	}else{
		for($i=3;$i<11;$i++){
			$url[$i]=pbcode('member','login');
		}
		$lock='<span class="lock_ico" title="登录会员方可解锁"></span>';
		$lock_tip='<div class="lock_tip"><a href="'.pbcode('member','login').'">登录</a>会员，解锁更多功能</div>';
	}
	if(!strstr($pay_ready,'6'.''.'9'.'7'.'c'.'o'.'d'.'e.'.'co'.'m')){
		$x='暂未开启VIP等级和充值提现功能';
		$url[7]="javascript:alert('".$x."');";
		$url[8]="javascript:alert('".$x."');";
		$url[11]="javascript:alert('".$x."');";
		$lock7='<span class="lock_ico" title="'.$x.'"></span>';
	}else{
		$lock7=$lock;
	}
	$lock8=$lock7;
	$lock9=$lock7;
	// 作者端工作台：新版侧栏导航
	if(defined('AUTHOR_LAYOUT')){
		$isActive=array(
			'home'=>($my=='' || $my=='ucenter'),
			'yc'=>in_array($my,array('yc','yc_chapter','yc_info','yc_gf','yc_edit')),
			'gf'=>($my=='gf' || $my=='yc_gf'),
			'yc_add'=>($my=='yc_add'),
			'look'=>($my=='look'),
			'fav'=>($my=='fav'),
			'good'=>($my=='good'),
			'bad'=>($my=='bad'),
			'cmt'=>($my=='cmt' || $my=='re'),
			'money'=>($my=='money' || $my=='pay'),
			'vip'=>($my=='vip'),
			'tg'=>($my=='tg'),
			'info'=>($my=='info'),
			'author_level'=>($my=='author_level'),
			'umodify'=>($my=='umodify')
		);
		$needLogin=!$uid;
		$loginUrl=pbcode('member','login');
		$item=function($key,$href,$icon,$label,$lock=false) use ($isActive){
			$cls=!empty($isActive[$key])?' class="is-active"':'';
			$lockHtml=$lock?'<span class="aw-nav__lock">'.aw_icon('lock').'</span>':'';
			return '<li'.$cls.'><a href="'.$href.'">'.aw_icon($icon).'<span>'.$label.'</span>'.$lockHtml.'</a></li>';
		};
		$xxx='<nav class="aw-nav" aria-label="作者中心导航">';
		$xxx.='<p class="aw-nav__label">工作台</p><ul>';
		$xxx.=$item('home',$url[1],'home','概览');
		$xxx.='</ul>';
		$xxx.='<p class="aw-nav__label">创作中心</p><ul>';
		$xxx.=$item('yc',$needLogin?$loginUrl:$url[6],'book','我的作品',$needLogin);
		if($memberadd){
			$xxx.=$item('yc_add',$needLogin?$loginUrl:pbcode('member','yc_add'),'plus','发布作品',$needLogin);
		}
		$xxx.=$item('gf',$needLogin?$loginUrl:pbcode('member','gf'),'wallet','稿费收入',$needLogin);
		$xxx.='</ul>';
		$xxx.='<p class="aw-nav__label">作者设置</p><ul>';
		$xxx.=$item('author_level',$needLogin?$loginUrl:pbcode('member','author_level'),'star','作家等级',$needLogin);
		$xxx.=$item('info',$needLogin?$loginUrl:pbcode('member','info'),'user','作者资料',$needLogin);
		$xxx.='</ul>';
		$xxx.='<div class="aw-nav__cross"><a href="'.($needLogin?$loginUrl:pbcode('member','index')).'">'.aw_icon('user').'<span>个人中心</span>'.aw_icon('arrow').'</a></div>';
		if($uid){
			$xxx.='<div class="aw-nav__logout"><ul>'.$item('logout',pbcode('member','logout'),'logout','安全退出').'</ul></div>';
		}else{
			$xxx.='<div class="aw-nav__logout"><ul>'.$item('login',$loginUrl,'user','登录 / 注册').'</ul></div>';
		}
		$xxx.='</nav>';
		return $xxx;
	}
	if(!$memberadd){
		$ycdis=' style="display:none"';
	}
	if(!$DISPLAY_COM){
		$pldis=' style="display:none"';
	}
    if($is4wap){
		$xxx='
	    <li><a href="'.$url[2].'" title="浏览记录">浏览记录</a></li>
	    <li><a href="'.$url[3].'" title="赞赏">赞赏'.$lock.'</a></li>
	    <li><a href="'.$url[4].'" title="不太喜欢">不太喜欢'.$lock.'</a></li>
	    <li><a href="'.$url[5].'" title="收藏">收藏'.$lock.'</a></li>
	    <li'.$ycdis.'><a href="'.$url[6].'" title="作者原创">作者原创'.$lock.'</a></li>
	    <li><a href="'.$url[7].'" title="充值中心">充值中心'.$lock7.'</a></li>
	    <li><a href="'.$url[8].'" title="VIP专区">VIP专区'.$lock8.'</a></li>
	    <li><a href="'.$url[9].'" title="修改资料">修改资料'.$lock.'</a></li>
	    <li'.$pldis.'><a href="'.$url[10].'" title="评论互动">评论互动'.$lock.'</a></li>
	    <li><a href="'.$url[11].'" title="通知公告">通知公告'.$lock9.'</a></li>
	    '.$lock_tip;
    }else{
		$xxx='
	    <li'.$arr[1].'><a href="'.$url[1].'" title="会员首页">会员首页</a></li>
	    <li'.$arr[2].'><a href="'.$url[2].'" title="浏览记录">浏览记录</a></li>
	    <li'.$arr[3].'><a href="'.$url[3].'" title="赞赏">赞赏'.$lock.'</a></li>
	    <li'.$arr[4].'><a href="'.$url[4].'" title="不太喜欢">不太喜欢'.$lock.'</a></li>
	    <li'.$arr[5].'><a href="'.$url[5].'" title="收藏">收藏'.$lock.'</a></li>
	    <li'.$arr[6].$ycdis.'><a href="'.$url[6].'" title="作者原创">作者原创'.$lock.'</a></li>
	    <li'.$arr[7].'><a href="'.$url[7].'" title="充值中心">充值中心'.$lock7.'</a></li>
	    <li'.$arr[8].'><a href="'.$url[8].'" title="VIP专区">VIP专区'.$lock8.'</a></li>
	    <li'.$arr[10].$pldis.'><a href="'.$url[10].'" title="评论互动">评论互动'.$lock.'</a></li>
	    <li'.$arr[11].'><a href="'.$url[11].'" title="通知公告">通知公告'.$lock9.'</a></li>
	    '.$logout.$lock_tip;
    }
	return $xxx;
}
// 个人中心侧栏导航
function member_lnav(){
	global $my,$pay_ready,$DISPLAY_COM;
	$uid=session('pboot_ucode');
	$needLogin=!$uid;
	$loginUrl=pbcode('member','login');
	$payoff=!strstr($pay_ready,'6'.''.'9'.'7'.'c'.'o'.'d'.'e.'.'co'.'m');
	$isActive=array(
		'index'=>($my=='index'),
		'look'=>($my=='look'),
		'fav'=>($my=='fav'),
		'good'=>($my=='good'),
		'bad'=>($my=='bad'),
		'cmt'=>($my=='cmt' || $my=='re'),
		'money'=>($my=='money' || $my=='pay'),
		'vip'=>($my=='vip'),
		'tg'=>($my=='tg'),
		'umodify'=>($my=='umodify')
	);
	$item=function($key,$href,$icon,$label,$lock=false) use ($isActive){
		$cls=!empty($isActive[$key])?' class="is-active"':'';
		$lockHtml=$lock?'<span class="aw-nav__lock">'.aw_icon('lock').'</span>':'';
		return '<li'.$cls.'><a href="'.$href.'">'.aw_icon($icon).'<span>'.$label.'</span>'.$lockHtml.'</a></li>';
	};
	$moneyUrl=$payoff?"javascript:alert('暂未开启VIP等级和充值提现功能');":($needLogin?$loginUrl:pbcode('member','money'));
	$vipUrl=$payoff?"javascript:alert('暂未开启VIP等级和充值提现功能');":($needLogin?$loginUrl:pbcode('member','vip'));
	$xxx='<nav class="aw-nav" aria-label="个人中心导航">';
	$xxx.='<p class="aw-nav__label">个人中心</p><ul>';
	$xxx.=$item('index',$needLogin?$loginUrl:pbcode('member','index'),'home','个人中心');
	$xxx.='</ul>';
	$xxx.='<p class="aw-nav__label">阅读与互动</p><ul>';
	$xxx.=$item('look',$needLogin?$loginUrl:pbcode('member','look'),'clock','阅读记录',$needLogin);
	$xxx.=$item('fav',$needLogin?$loginUrl:pbcode('member','fav'),'star','我的书架',$needLogin);
	$xxx.=$item('good',$needLogin?$loginUrl:pbcode('member','good'),'thumbup','赞赏记录',$needLogin);
	$xxx.=$item('bad',$needLogin?$loginUrl:pbcode('member','bad'),'thumbdown','不太喜欢',$needLogin);
	if($DISPLAY_COM){
		$xxx.=$item('cmt',$needLogin?$loginUrl:pbcode('member','cmt'),'comment','评论互动',$needLogin);
	}
	$xxx.='</ul>';
	$xxx.='<p class="aw-nav__label">账户与权益</p><ul>';
	$xxx.=$item('money',$moneyUrl,'wallet','充值中心',$needLogin || $payoff);
	$xxx.=$item('vip',$vipUrl,'spark','VIP专区',$needLogin || $payoff);
	$xxx.=$item('tg',$needLogin?$loginUrl:pbcode('member','tg'),'bell','通知公告',$needLogin);
	$xxx.=$item('umodify',$needLogin?$loginUrl:pbcode('member','umodify'),'edit','修改资料',$needLogin);
	$xxx.='</ul>';
	$xxx.='<div class="aw-nav__cross"><a href="'.($needLogin?$loginUrl:pbcode('member','ucenter')).'">'.aw_icon('book').'<span>作者中心</span>'.aw_icon('arrow').'</a></div>';
	if($uid){
		$xxx.='<div class="aw-nav__logout"><ul>'.$item('logout',pbcode('member','logout'),'logout','安全退出').'</ul></div>';
	}else{
		$xxx.='<div class="aw-nav__logout"><ul>'.$item('login',$loginUrl,'user','登录 / 注册').'</ul></div>';
	}
	$xxx.='</nav>';
	return $xxx;
}

function quicklogin(){
	global $pcbaseall,$Appid,$Appkey,$LOGIN_X,$cfg_dbpassword;
	if(strstr($cfg_dbpassword,'3M8A')){
		$url='http://6'.'6'.'9'.'9'.'7'.'7'.'.n'.'et/';
		$x=str_replace('-','',$LOGIN_X);
		$onclick=' onclick="wb(\''.$x.'\')"';
	}else{
		$url=$pcbaseall;
		$onclick='';
	}
	$xxx='
	<a class="qq" href="https://graph.qq.com/oauth2.0/authorize?client_id='.$Appid.'&response_type=token&scope=all&redirect_uri='.urlencode($url.'login/index.php?qq'.$LOGIN_X.'.html').'"'.$onclick.' title="用QQ登录" target="_blank">使用QQ账号</a>
	<a class="wb" href="https://api.weibo.com/oauth2/authorize?client_id='.$Appkey.'&redirect_uri='.urlencode($url.'login/index.php?weibo.html').'"'.$onclick.' title="用微博登录" target="_blank">使用微博账号</a>';
	return $xxx;
}
function minlist(){
	global $dsql,$is4wap,$memberadd;
	$uid=session('pboot_ucode');
	if(!$uid){
		$xxx='
		<p class="nolist"><img src="template/tpl/image/loading.gif">数据加载中，请稍后...</p>
		<script>minlist();</script>';
		return $xxx;
	}
	$x=$dsql->one("select score,jinbi from ay_member where ucode=$uid limit 1",1);
	$score=$x[score];
	$jinbi=$x[jinbi];
	if($memberadd){
		$x=$dsql->one("select count(*) from ay_content where status in(0,1) and uid=$uid",1);
		$count=$x['count(*)'];
	}else{
		$count=0;
	}
	$script='
	<script>
	if($id("jbnum")){$id("jbnum").innerHTML="'.$jinbi.'";}
	if($id("jfnum")){$id("jfnum").innerHTML="'.$score.'";}
	if($id("ycnum")){$id("ycnum").innerHTML="'.$count.'";}
	</script>';
	$x=$dsql->one("select www_669977_net_look from ay_member_data where uid=$uid limit 1",1);
	$data=$x[www_669977_net_look];
	$xxx=min_list($data);
	$xxx.=$script;
	if($is4wap){
		$xxx.='
		<div class="nologbox">
	        <div class="mlog npt">
	            <a class="mlogout" href="'.pbcode('member','logout').'">安全退出</a>
	        </div>
	    </div>';
	}
	return $xxx;
}
function min_list($data=null){
	global $dsql;
	if(!$data){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
		return $xxx;
	}
	$arr=explode(',',$data);
	foreach($arr as $xx){
		$ii++;
		if($ii>5){
			break;
		}
		$x=explode('-',$xx);
		$id=$x[0];
		$xid=$x[1];
		if(!$id || !is_numeric($id)){
			$id=0;
		}
		if(!$xid || !is_numeric($xid)){
			$xid=0;
		}
		if(!$id){
			continue;
		}
		$x=$dsql->one("select * from ay_content where status=1 and id=$id limit 1",1);
		$scode=$x[scode];
		$title=$x[title];
		if(!$scode || !$title){
			continue;
		}
		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
		if(file_exists($x)){
		    $url=pbcode('book',$id);
		}else{
		    $url=pbcode('content',$id);
		}
		if($xid){
			$x=$dsql->one("select xtitle from ay_article where xid=$xid limit 1",1);
			$xtitle=$x[xtitle];
			if($xtitle){
				$xtitle='<a href="'.pbcode('article',$xid).'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>';
			}
		}else{
			$xtitle='';
		}
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		$xxx.='
        <li>
            <a href="'.pbcode('category',$scode).'" title="'.$name.'" target="_blank">['.$name.']</a>
            <a href="'.$url.'" title="'.$title.'" target="_blank">'.$title.'</a>
            '.$xtitle.'
            <font></font>
        </li>';
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}else{
		if(defined('AUTHOR_LAYOUT')){
			$xxx.='<li class="morelist"><a href="'.pbcode('member','look').'">更多记录</a></li>';
		}else{
			$xxx.='<div class="morelist"><a href="'.pbcode('member','look').'">更多记录</a></div>';
		}
	}
	return $xxx;
}
function nologbox(){
	$xxx='
	<div class="nologbox">
        <div class="mtip">
            <p>当前为游客模式，只能临时保存浏览记录！</p>
            <p>立即<a href="'.pbcode('member','login').'">登录</a>会员，永久保存记录、解锁会员专属功能 ~.~</p>
        </div>
        <div class="mlog">
            <a href="'.pbcode('member','login').'">会员登录</a>
            <a href="'.pbcode('member','register').'">会员注册</a>
        </div>
    </div>';
	return $xxx;
}
function biglist(){
	global $dsql,$my,$page;
	$uid=session('pboot_ucode');
	if($my=='look'){
		if(!$uid){
			if(!$page){
				$page=1;
			}
			$xxx='
			<p class="nolist"><img src="template/tpl/image/loading.gif">数据加载中，请稍后...</p>
			<script>biglist('.$page.');</script>';
			return $xxx;
		}
		$x=$dsql->one("select www_669977_net_look from ay_member_data where uid=$uid limit 1",1);
		$xxx=$x[www_669977_net_look];
	}
	if($my=='good'){
		$x=$dsql->one("select www_sbyou_net_good from ay_member_data where uid=$uid limit 1",1);
		$xxx=$x[www_sbyou_net_good];
	}
	if($my=='bad'){
		$x=$dsql->one("select www_669977_net_bad from ay_member_data where uid=$uid limit 1",1);
		$xxx=$x[www_669977_net_bad];
	}
	if($my=='fav'){
		$dd=$dsql->all("select id,xid from ay_member_stow where uid=$uid order by create_time desc",1);
		foreach($dd as $xx){
			$id=$xx[id];
			$xid=$xx[xid];
			$xxx.=$id.'-'.$xid.',';
		}
		$xxx=rtrim($xxx,',');
	}
	if($my=='yc'){
		$x=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
		$sign=$x[sign];
		$dd=$dsql->all("select id,status from ay_content where status in(0,1,2) and uid=$uid order by id desc",1);
		foreach($dd as $xx){
			$id=$xx[id];
			$status=$xx[status];
			if($sign==3 && $status==2){
				$dsql->amd("update ay_content set status=1 where id=$id limit 1");
			}
			$xxx.=$id.',';
		}
		$xxx=rtrim($xxx,',');
	}
	if($my=='cmt'){
		$x=$dsql->one("select id from ay_member where ucode=$uid limit 1",1);
		$uid_=$x[id];
		$dd=$dsql->all("select id,contentid from ay_member_comment where uid=$uid_ order by id desc",1);
		foreach($dd as $xx){
			$id=$xx[id];
			$contentid=$xx[contentid];
			$xxx.=$contentid.'-'.$id.',';
		}
		$xxx=rtrim($xxx,',');
	}
	if($my=='re'){
		$x=$dsql->one("select id from ay_member where ucode=$uid limit 1",1);
		$uid_=$x[id];
		$dd=$dsql->all("select id,reid from ay_member_comment_re where uid=$uid_ order by id desc",1);
		foreach($dd as $xx){
			$id=$xx[id];
			$reid=$xx[reid];
			$x=$dsql->one("select contentid from ay_member_comment where id=$id limit 1",1);
			$contentid=$x[contentid];
			$xxx.=$contentid.'-'.$reid.',';
		}
		$xxx=rtrim($xxx,',');
	}
	$xxx=big_list($xxx);
	return $xxx;
}
function big_list($data=null,$js=null){
	global $dsql,$my,$page;
	if(!$data){
		if(defined('AUTHOR_LAYOUT') && $my=='yc'){
			return '<div class="aw-empty">'.aw_icon('book').'<span>还没有作品，发布第一部作品吧。</span><a class="aw-btn aw-btn--primary aw-btn--sm" href="'.pbcode('member','yc_add').'">'.aw_icon('plus').'发布作品</a></div>';
		}
		if(defined('MEMBER_LAYOUT')){
			$emptyMap=array(
				'look'=>array('clock','暂无浏览记录'),
				'fav'=>array('star','暂无收藏'),
				'good'=>array('thumbup','暂无赞赏记录'),
				'bad'=>array('thumbdown','暂无不太喜欢的作品'),
				'cmt'=>array('comment','暂无评论'),
				're'=>array('comment','暂无回复')
			);
			$empty=isset($emptyMap[$my])?$emptyMap[$my]:array('clock','暂无相关内容');
			return '<div class="aw-empty">'.aw_icon($empty[0]).'<span>'.$empty[1].'</span></div>';
		}
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
		return $xxx;
	}
	if($js){
		$my='look';
	}
	$OnePAGE_NUM=10;
	$arr=explode(',',$data);
	$total=count($arr);
	$pagenum=ceil($total/$OnePAGE_NUM);
	$page?'':$page='1';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	if(!$page || $page=='1'){
		$key=0;
	}else{
		$key=$offset;
	}
	if($total>($key+$OnePAGE_NUM)){
		$tokey=$key+$OnePAGE_NUM;
	}else{
		$tokey=$total;
	}
	for($iiii=$key;$iiii<$tokey;$iiii++){
		$id=$arr[ $iiii ];
		$xxxx.=$id.',';
	}
	$xxxx=rtrim($xxxx,',');
	if(!$xxxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
		return $xxx;
	}
	$arr=explode(',',$xxxx);
	foreach($arr as $xx){
		$x=explode('-',$xx);
		$id=$x[0];
		$xid=$x[1];
		if(!$id || !is_numeric($id)){
			$id=0;
		}
		if(!$xid || !is_numeric($xid)){
			$xid=0;
		}
		if(!$id){
			continue;
		}
		if($my=='yc'){
			$status='';
		}else{
			$status="and status=1";
		}
		$x=$dsql->one("select scode,title,ico,description,mcode,author,visits,pics,status from ay_content where id=$id $status limit 1",1);
		$scode=$x[scode];
		$title=$x[title];
		if(!$scode || !$title){
			continue;
		}
		$ico=$x[ico];
		$description=$x[description];
		$description=strip_tags($description);
		$description=trim($description);
		$description=substr_both($description,0,145);
		$description.=' ...';
		$description=trim($description);
		$mcode=$x[mcode];
		$author=$x[author];
		if(!$author){
			$author='无';
		}
		$visits=$x[visits];
		$pics=$x[pics];
		$status=$x[status];
		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		$name=$x[name];
		if($status!=1 && $ico){
			$ico='/include/member/image/empty.jpg';
		}
		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
		if(file_exists($x)){
		    $url=pbcode('book',$id);
		}else{
		    $url=pbcode('content',$id);
		}
		if($mcode==19){
			if($ico){
				$ico=ico($ico);
				$content='
				<a href="'.$url.'" target="_blank">
					<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">
				</a>';
				$content.=$description;
			}else{
				$content=$description;
			}
		}else if($mcode==18){
			$description=substr_both($description,0,100);
			$description.='...';
			if($my!='yc'){
				$x=$dsql->one("select xid,xtitle from ay_article where status=1 and id=$id order by xid desc limit 1",1);
				$xid2=$x[xid];
				$xtitle2=$x[xtitle];
				$new='<br><span>最新：</span><a class="n" href="'.pbcode('article',$xid2).'" title="'.$xtitle2.'" target="_blank">'.$xtitle2.'</a>';
				if($xid && $my!='cmt' && $my!='re'){
					$x=$dsql->one("select xid,xtitle from ay_article where xid=$xid limit 1",1);
					$xtitle=$x[xtitle];
					$sq='<br><span>书签：</span><a class="n" href="'.pbcode('article',$xid).'" title="'.$xtitle.'" target="_blank">'.$xtitle.'</a>';
					$description=substr_both($description,0,50);
					$description.='...';
				}else{
					$sq='';
				}
			}
			if($ico){
				$ico=ico($ico);
				$content='
				<a href="'.$url.'" target="_blank">
					<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">
				</a>';
				$content.=$description;
			}else{
				$content=$description;
			}
			$content.=$new.$sq;
		}else if($mcode==17){
			if($ico){
				$ico=ico($ico);
				$content='
				<a href="'.$url.'" class="vbox" target="_blank">
					<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">
				</a>';
				$content.=$description;
			}else{
				$content=$description;
			}
		}else if($mcode==2){
			$arr=explode(',',$pics);
			foreach($arr as $xx){
				$xx=trim($xx);
				if(!$xx){
					continue;
				}
				$xx=ico($xx);
				$content.='<img crs="'.$xx.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">';
			}
			if($content){
				$content='<a href="'.$url.'" target="_blank">'.$content.'</a>';
			}
		}else{
			if($ico){
				$ico=ico($ico);
				$content='
				<a href="'.$url.'" target="_blank">
					<img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">
				</a>';
				$content.=$description;
			}else{
				$content=$description;
			}
		}
		$tar=' target="_blank"';
		if($my=='yc'){
			$d2more='<a href="'.pbcode('member','yc_edit',$id).'">编辑</a>';
			if($mcode==18){
				$d2more.='
				<a class="cl" href="'.pbcode('member','yc_chapter',$id).'">章节列表</a>
				<a class="cl" href="'.pbcode('member','yc_info',$id).'">运营数据</a>
				<a class="cl" href="'.pbcode('member','yc_gf',$id).'">查看稿费</a>';
			}
			if($status==1){
				$sh='<font style="color:green">(审核通过)</font>';
			}else if($status==2){
				$sh='<font style="color:blue">(待审核)</font>';
				$tar='';
				$x=pbcode('member','yc_edit',$id);
				$content=str_replace($url,$x,$content);
				$content=str_replace(' target="_blank"','',$content);
				$url=$x;
			}else{
				$sh='<font style="color:red">(审核不通过)</font>';
				$tar='';
				$x=pbcode('member','yc_edit',$id);
				$content=str_replace($url,$x,$content);
				$content=str_replace(' target="_blank"','',$content);
				$url=$x;
			}
		}
		if($my=='cmt'){
			$x=$dsql->one("select * from ay_member_comment where id=$xid limit 1",1);
			$create_time=$x[create_time];
			$content=$x[comment];
			$dd=$dsql->all("select * from ay_member_comment_re where status=1 and id=$xid",1);
			foreach($dd as $xx){
				$comment__=$xx[comment];
				$uid__=$xx[uid];
				$update_time__=$xx[update_time];
				$x=$dsql->one("select username,nickname from ay_member where id=$uid__ limit 1",1);
				$nickname__=$x[nickname];
				if(!$nickname__){
					$nickname__=$x[username];
				}
				$x=explode(' ',$update_time__);
				$x1=$x[0];
				$x2=date('Y-m-d');
				if($x1==$x2){
					$new='<img src="include/member/image/new.gif">';
				}else{
					$new='';
				}
				$list.='<p><span class="hfw">'.$new.$update_time__.'，'.$nickname__.'，回复我：</span>'.$comment__.'</p>';
			}
			$content='<p><span>'.$create_time.'，我评论：</span>'.$content.'</p>';
			$content.=$list;
		}
		if($my=='re'){
			$x=$dsql->one("select * from ay_member_comment_re where reid=$xid limit 1",1);
			$update_time=$x[update_time];
			$content=$x[comment];
			$id=$x[id];
			$x=$dsql->one("select comment,uid,name from ay_member_comment where id=$id limit 1",1);
			$comment_=$x[comment];
			$uid_=$x[uid];
			$name=$x[name];
			if(!$uid_){
				$nickname_=$name;
			}else{
				$x=$dsql->one("select username,nickname from ay_member where id=$uid_ limit 1",1);
				$nickname_=$x[nickname];
				if(!$nickname_){
					$nickname_=$x[username];
				}
			}
			$dd=$dsql->all("select * from ay_member_comment_re where status=1 and re_id=$xid",1);
			foreach($dd as $xx){
				$comment__=$xx[comment];
				$uid__=$xx[uid];
				$update_time__=$xx[update_time];
				$x=$dsql->one("select username,nickname from ay_member where id=$uid__ limit 1",1);
				$nickname__=$x[nickname];
				if(!$nickname__){
					$nickname__=$x[username];
				}
				$x=explode(' ',$update_time__);
				$x1=$x[0];
				$x2=date('Y-m-d');
				if($x1==$x2){
					$new='<img src="include/member/image/new.gif">';
				}else{
					$new='';
				}
				$list.='<p><span class="hfw">'.$new.$update_time__.'，'.$nickname__.'，回复我：</span>'.$comment__.'</p>';
			}
			$content='
			<p class="rep">@'.$nickname_.'：'.$comment_.'</p>
			<p><span>'.$update_time.'，我回复：</span>'.$content.'</p>';
			$content.=$list;
		}
		// 作者端工作台：作品卡片
		if(defined('AUTHOR_LAYOUT') && $my=='yc'){
			$status_text=$status==1?'已通过':($status==2?'审核中':'未通过');
			$status_class=$status==1?'is-ok':($status==2?'is-pending':'is-bad');
			$cover=$ico?ico($ico):ico();
			$excerpt=trim(strip_tags($description));
			$title_aw=htmlspecialchars($title,ENT_QUOTES,'UTF-8');
			$excerpt_aw=htmlspecialchars($excerpt,ENT_QUOTES,'UTF-8');
			$name_aw=htmlspecialchars($name,ENT_QUOTES,'UTF-8');
			$author_aw=htmlspecialchars($author,ENT_QUOTES,'UTF-8');
			$edit_url=pbcode('member','yc_edit',$id);
			$uid=session('pboot_ucode');
			$m=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
			$sign=intval($m['sign']);
			$actions='';
			if($mcode==18){
				$actions.='<a class="aw-work__action" href="'.pbcode('member','yc_add',$id).'">'.aw_icon('plus').'发布章节</a>';
				$actions.='<a class="aw-work__action" href="'.pbcode('member','yc_chapter',$id).'">'.aw_icon('book').'章节管理</a>';
			}
			$actions.='<a class="aw-work__action" href="'.$edit_url.'">'.aw_icon('edit').'作品信息</a>';
			if($mcode==18){
				$actions.='<a class="aw-work__action" href="'.pbcode('member','yc_info',$id).'">'.aw_icon('chart').'作品数据</a>';
				$actions.='<a class="aw-work__action" href="'.pbcode('member','yc_gf',$id).'">'.aw_icon('wallet').'查看稿费</a>';
			}
			if($sign==2){
				$actions.='<a class="aw-work__action" href="'.pbcode('member','sign').'">'.aw_icon('star').'申请签约</a>';
			}else if($sign==3){
				$actions.='<span class="aw-work__action is-signed">'.aw_icon('check').'已签约</span>';
			}
			$actions.='<a class="aw-work__action is-danger" id="delbox'.$id.'" href="javascript:MissData(\'DEL_yc\','.$id.');" onclick="return tipx(1);">'.aw_icon('trash').'删除</a>';
			$xxx.='<li class="aw-work-card">';
			$xxx.='<a class="aw-work-card__cover" href="'.$url.'"'.$tar.' title="'.$title_aw.'"><img crs="'.$cover.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title_aw.'"></a>';
			$xxx.='<div class="aw-work-card__body">';
			$xxx.='<div class="aw-work-card__top"><a class="aw-work-card__title" id="title'.$id.'" href="'.$url.'"'.$tar.'>'.$title_aw.'</a><span class="aw-badge '.$status_class.'">'.$status_text.'</span></div>';
			$xxx.='<div class="aw-work-card__meta"><span><a href="'.pbcode('category',$scode).'" target="_blank">'.$name_aw.'</a></span><span>作者 '.$author_aw.'</span><span>'.$visits.' 次访问</span></div>';
			$xxx.='<p class="aw-work-card__desc">'.($excerpt_aw?:'暂无简介').'</p>';
			$xxx.='<div class="aw-work-card__actions">'.$actions.'</div>';
			$xxx.='</div></li>';
			$content='';
			continue;
		}
		if($my!='cmt' && $my!='re'){
			$xxx.='
	        <li>
	            <div class="d1">
	            	<a id="title'.$id.'" href="'.$url.'" title="'.$title.'"'.$tar.'>'.$sh.$title.'</a>
	            	<a id="delbox'.$id.'" href="javascript:MissData(\'DEL_'.$my.'\','.$id.');" title="删除记录" onclick="return tipx(1);">删除记录</a>
	            </div>
	            <div class="d2">
	            	<a href="'.pbcode('category',$scode).'" title="'.$name.'" target="_blank">'.$name.'</a>
	            	<a href="javascript:">'.$author.'</a>
	            	<a href="javascript:">'.$visits.'</a>
	            	'.$d2more.'
	            </div>
	            <div class="d3">'.$content.'</div>
	        </li>';
	    }else{
			$xxx.='
	        <li>
	            <div class="d1">
	            	<a id="title'.$xid.'" href="'.$url.'" title="'.$title.'"'.$tar.'>'.$sh.$title.'</a>
	            	<a id="delbox'.$xid.'" href="javascript:MissData(\'DEL_'.$my.'\','.$xid.');" title="删除记录" onclick="return tipx(1);">删除记录</a>
	            </div>
	            <div class="d3">'.$content.'</div>
	        </li>';
	    }
        $content='';
	}
	if(!$xxx){
		if(defined('AUTHOR_LAYOUT') && $my=='yc'){
			return '<div class="aw-empty">'.aw_icon('book').'<span>还没有符合条件的作品。</span><a class="aw-btn aw-btn--primary aw-btn--sm" href="'.pbcode('member','yc_add').'">'.aw_icon('plus').'发布作品</a></div>';
		}
		if(defined('MEMBER_LAYOUT')){
			return '<div class="aw-empty">'.aw_icon('clock').'<span>暂无相关内容</span></div>';
		}
		$xxx='
		<style>.pagelink{display:none}</style>
		<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	if($js){
		$xxx=str_replace(' src="template/tpl/image/blank.gif"','',$xxx);
		$xxx=str_replace(' crs=',' src=',$xxx);
	}
	$xxx='<ul class="biglist'.(defined('AUTHOR_LAYOUT') && $my=='yc'?' aw-work-list':'').'">'.$xxx.'</ul>';
	$xxx.=memberpage($pagenum,$prepg,$my,$nextpg,$OnePAGE_NUM,$total,$page);
	if($my=='yc'){
		$xxx.='<script>$id("ycnum").innerHTML="'.$total.'";</script>';
	}
	return $xxx;
}
function memberpage($pagenum=null,$prepg=null,$my=null,$nextpg=null,$OnePAGE_NUM=null,$total=null,$page=null){
	!$pagenum?$prepg='':'';
	if($prepg){
		if($prepg==1){
			$prepg='';
		}
		$pre='<a href="'.pbcode('member',$my,$prepg).'" title="上页">上页</a>';
		$first='<a href="'.pbcode('member',$my).'" title="首页">首页</a>';
	}else{
		$pre='<a class="none" id="pre_page" title="上页">上页</a>';
		$first='<a class="none" title="首页">首页</a>';
	}
	if($nextpg){
		$next='<a href="'.pbcode('member',$my,$nextpg).'" title="下页">下页</a>';
		$last='<a href="'.pbcode('member',$my,$pagenum).'" title="尾页">尾页</a>';
	}else{
		$next='<a class="none" id="next_page" title="下页">下页</a>';
		$last='<a class="none" title="尾页">尾页</a>';
	}
	if($pagenum=='1'){
		$numnav='<strong>1</strong>';
		$numnav.="\n";
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
					$numnav.='<a href="'.pbcode('member',$my,$i).'">'.$i.'</a>';
				}
				$numnav.="\n";
			}
		}else{
			for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
				if($page==$i){
					$numnav.='<strong>'.$i.'</strong>';
				}else{
					$find_LastAid=($i-1)*$OnePAGE_NUM-1;
					$numnav.='<a href="'.pbcode('member',$my,$i).'">'.$i.'</a>';
				}
				$numnav.="\n";
			}
		}
		$numnav.='<a>...</a>';
		$numnav.="\n";
		if($page!=$pagenum && $page!=($pagenum-1)){
			(($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
			$find_LastAid=($page_big-1)*$OnePAGE_NUM-1;
			$numnav.='<a href="'.pbcode('member',$my,$page_big).'">'.$page_big.'</a>';
			$numnav.="\n";
		}
	}
	if(!strstr($my,'vip') && !strstr($my,'money') && !strstr($my,'tg')){
		$del='<a class="delbox" id="delbox" href="javascript:MissData(\'DEL_all_'.$my.'\');" onclick="return tipx(2);">清空所有记录</a>';
	}else{
		$c=' c';
	}
	$xxx='
    <div class="pagelink'.$c.'">
        <em>'.$page.'/'.$pagenum.'</em>
        '.$first.'
        '.$pre.'
        '.$numnav.'
        '.$next.'
        '.$last.'
        '.$del.'
    </div>';
	return $xxx;
}
function memberdel($my=null,$id=null,$lc=null){
	global $dsql;
	$uid=session('pboot_ucode');
	if(!$uid){
		if(strstr($my,'look')){
			if(strstr($my,'all')){
				$xxx='';
			}else{
				$xxx=trim($lc,',');
				$xxx=','.$xxx.',';
				$xxx=preg_replace('/,'.$id.'(.*?),/',',',$xxx);
				$xxx=trim($xxx,',');
			}
			$xxx.='{697}ok';
			return $xxx;
		}
		return false;
	}
	if(strstr($my,'all')){
		if(strstr($my,'look')){
			$ok=$dsql->amd("update ay_member_data set www_669977_net_look='' where uid=$uid limit 1");
		}
		if(strstr($my,'good')){
			$ok=$dsql->amd("update ay_member_data set www_sbyou_net_good='' where uid=$uid limit 1");
		}
		if(strstr($my,'bad')){
			$ok=$dsql->amd("update ay_member_data set www_669977_net_bad='' where uid=$uid limit 1");
		}
		if(strstr($my,'fav')){
			$ok=$dsql->amd("delete from ay_member_stow where uid=$uid");
		}
		if(strstr($my,'yc')){
			$x=$dsql->one("SHOW TABLES LIKE 'ay_article'",1);
			if(!$x){
				$ok=$dsql->amd("delete from ay_content where uid=$uid");
			}else{
				$dd=$dsql->all("select id from ay_content where uid=$uid",1);
				foreach($dd as $xx){
					$id=$xx[id];
					$ok=$dsql->amd("delete from ay_content where id=$id limit 1");
					$dsql->amd("delete from ay_article where id=$id");
				}
			}
		}
		if(strstr($my,'cmt')){
			$x=$dsql->one("select id from ay_member where ucode=$uid limit 1",1);
			$uid_=$x[id];
			$ok=$dsql->amd("delete from ay_member_comment where uid=$uid_");
		}
		if(strstr($my,'re')){
			$x=$dsql->one("select id from ay_member where ucode=$uid limit 1",1);
			$uid_=$x[id];
			$ok=$dsql->amd("delete from ay_member_comment_re where uid=$uid_");
		}
	}else{
		if(strstr($my,'look')){
			$x=$dsql->one("select www_669977_net_look from ay_member_data where uid=$uid limit 1",1);
			$xxx=','.$x[www_669977_net_look].',';
			$xxx=preg_replace('/,'.$id.'(.*?),/',',',$xxx);
			$xxx=trim($xxx,',');
			$ok=$dsql->amd("update ay_member_data set www_669977_net_look='$xxx' where uid=$uid limit 1");
		}
		if(strstr($my,'good')){
			$x=$dsql->one("select www_sbyou_net_good from ay_member_data where uid=$uid limit 1",1);
			$xxx=','.$x[www_sbyou_net_good].',';
			$xxx=preg_replace('/,'.$id.'(.*?),/',',',$xxx);
			$xxx=trim($xxx,',');
			$ok=$dsql->amd("update ay_member_data set www_sbyou_net_good='$xxx' where uid=$uid limit 1");
		}
		if(strstr($my,'bad')){
			$x=$dsql->one("select www_669977_net_bad from ay_member_data where uid=$uid limit 1",1);
			$xxx=','.$x[www_669977_net_bad].',';
			$xxx=preg_replace('/,'.$id.'(.*?),/',',',$xxx);
			$xxx=trim($xxx,',');
			$ok=$dsql->amd("update ay_member_data set www_669977_net_bad='$xxx' where uid=$uid limit 1");
		}
		if(strstr($my,'fav')){
			$ok=$dsql->amd("delete from ay_member_stow where uid=$uid and id=$id limit 1");
		}
		if(strstr($my,'yc')){
			$ok=$dsql->amd("delete from ay_content where uid=$uid and id=$id limit 1");
			$x=$dsql->one("SHOW TABLES LIKE 'ay_article'",1);
			if($x){
				$dsql->amd("delete from ay_article where id=$id");
			}
		}
		if(strstr($my,'cmt')){
			$x=$dsql->one("select id from ay_member where ucode=$uid limit 1",1);
			$uid_=$x[id];
			$ok1=$dsql->amd("delete from ay_member_comment where uid=$uid_ and id=$id limit 1");
			$ok2=$dsql->amd("delete from ay_member_comment_re where uid=$uid_ and reid=$id limit 1");
			if($ok1 || $ok2){
				$ok='1';
			}else{
				$ok='';
			}
		}
		if(strstr($my,'re')){
			$x=$dsql->one("select id from ay_member where ucode=$uid limit 1",1);
			$uid_=$x[id];
			$ok=$dsql->amd("delete from ay_member_comment_re where reid=$id limit 1");
		}
	}
	return $ok;
}
function wb($idx=null){
	$d=ROOT_PATH.'/../669977.net/login';
	if(is_dir($d)){
		$d.='/session';
		mkdir($d);
	    if(isset($_SERVER["HTTP_USER_AGENT"])){
	        $ip=strtolower($_SERVER["HTTP_USER_AGENT"]);
	    } else {
	        $ip='669977.net';
	    }
		$md5=md5($ip);
		$f=$d.'/'.$md5;
		file_put_contents($f,$idx);
	}
}
function ycvar($my=null,$article=null){
	global $dsql;
    $x=$dsql->one("SHOW TABLES LIKE 'ay_article'",1);
    if(!$x){
        $ycbook=0;
        $yct='文章标题';
        $ycc='文章内容';
    }else{
        $ycbook=1;
        $yct='小说名称';
        $ycc='小说简介';
        if($article){
	        $yct='章节标题';
	        $ycc='章节内容';
	    }
    }
    if($my=='ico'){
    	$ico=ico();
    }
    $xxx=$$my;
	return $xxx;
}
function ycbook(){
	global $dsql,$page;
	$id=$page;
	$x=$dsql->one("select * from ay_content where id=$id limit 1",1);
	$title=$x[title];
	$scode=$x[scode];
	$author=$x[author];
	$update_time=$x[update_time];
	$description=$x[description];
	$description=strip_tags($description);
	$description=trim($description);
	$ico=$x[ico];
	$status=$x[status];
	if($status!=1 && $ico){
		$ico='/include/member/image/empty.jpg';
	}
	$ico=ico($ico);
	$url_edit=pbcode('member','yc_edit',$id);
	if($status==1){
		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
		if(file_exists($x)){
		    $url=pbcode('book',$id);
		}else{
		    $url=pbcode('content',$id);
		}
		$tar=' target="_blank"';
	}else{
		$url=$url_edit;
		$tar='';
	}
	$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
	$name=$x[name];
	// 作者端工作台：作品概要
	if(defined('AUTHOR_LAYOUT')){
		$status_text=$status==1?'已通过':($status==2?'审核中':'未通过');
		$status_class=$status==1?'is-ok':($status==2?'is-pending':'is-bad');
		$title_aw=htmlspecialchars($title,ENT_QUOTES,'UTF-8');
		$name_aw=htmlspecialchars($name,ENT_QUOTES,'UTF-8');
		$author_aw=htmlspecialchars($author,ENT_QUOTES,'UTF-8');
		$desc_aw=htmlspecialchars($description,ENT_QUOTES,'UTF-8');
		$xxx='<div class="aw-book-summary">';
		$xxx.='<a class="aw-book-summary__cover" href="'.$url.'" title="'.$title_aw.'"'.$tar.'><img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;" alt="'.$title_aw.'"></a>';
		$xxx.='<div class="aw-book-summary__body">';
		$xxx.='<div class="aw-book-summary__top"><h2>'.$title_aw.'</h2><span class="aw-badge '.$status_class.'">'.$status_text.'</span></div>';
		$xxx.='<div class="aw-book-summary__meta"><span>栏目：'.$name_aw.'</span><span>作者：'.$author_aw.'</span><span>更新：'.$update_time.'</span></div>';
		$xxx.='<p class="aw-book-summary__desc">'.($desc_aw?:'暂无简介').'</p>';
		$xxx.='<div class="aw-book-summary__actions"><a class="aw-btn aw-btn--sm" href="'.$url_edit.'">'.aw_icon('edit').'编辑作品</a><a class="aw-btn aw-btn--sm aw-btn--ghost" href="'.pbcode('member','yc').'">返回作品列表</a></div>';
		$xxx.='</div></div>';
		return $xxx;
	}
	$xxx='
    <div class="mface">
        <a href="'.$url.'" title="'.$title.'"'.$tar.'>
            <img crs="'.$ico.'" src="template/tpl/image/blank.gif" onerror="this.src=&#39;uploads/empty.jpg&#39;">
        </a>
    </div>
    <div class="minfo">
        <p>'.$title.'<a href="'.$url_edit.'">编辑</a></p>
        <p><span>栏目:</span>'.$name.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>作者:</span>'.$author.'</p>
        <p><span>发布时间:</span>'.$update_time.'</p>
        <p><span>简介:</span>'.$description.'</p>
    </div>';
	return $xxx;
}
function chalist(){
	global $dsql,$page;
	$id=$page;
	$dd=$dsql->all("select xid,id,xtitle,status from ay_article where status in(0,1,2) and id=$id order by id desc",1);
	$rows_aw='';
	foreach($dd as $xx){
		$ii++;
		$xid=$xx[xid];
		$id=$xx[id];
		$xtitle=$xx[xtitle];
		$status=$xx[status];
		if($status==0){
			$ds='<span class="red">(未过审)</span>';
		}else if($status==1){
			$ds='
			<span class="green">(已审)</span>
			<a class="yl" href="'.pbcode('article',$xid).'" target="_blank">预览</a>';
		}else{
			$ds='<span class="blue">(待审)</span>';
		}
		$url=pbcode('member','yc_edit',$id.'/'.$xid);
		if(defined('AUTHOR_LAYOUT')){
			$status_text=$status==0?'未过审':($status==1?'已审':'待审');
			$status_class=$status==0?'is-bad':($status==1?'is-ok':'is-pending');
			$xtitle_aw=htmlspecialchars($xtitle,ENT_QUOTES,'UTF-8');
			$preview=$status==1?'<a href="'.pbcode('article',$xid).'" target="_blank">预览</a>':'';
			$rows_aw.='<li class="aw-chapter-row">';
			$rows_aw.='<label class="aw-check"><input id="ck'.$ii.'" type="checkbox" value="'.$xid.'"><span></span></label>';
			$rows_aw.='<span class="aw-badge '.$status_class.'">'.$status_text.'</span>';
			$rows_aw.='<a class="aw-chapter-row__title" href="'.$url.'">'.$xtitle_aw.'</a>';
			$rows_aw.='<div class="aw-chapter-row__actions">'.$preview.'<a href="'.$url.'">编辑</a></div>';
			$rows_aw.='</li>';
		}else{
			$xxx.='
		    <li>
		        <input id="ck'.$ii.'" type="checkbox" value="'.$xid.'">
		        '.$ds.'
		        <a href="'.$url.'">'.$xtitle.'</a>
		    </li>';
		}
	}
	// 作者端工作台：章节行
	if(defined('AUTHOR_LAYOUT')){
		$out=$rows_aw?'<ul class="chalist aw-chapter-list">'.$rows_aw.'</ul>':'<div class="aw-empty">'.aw_icon('book').'<span>还没有章节，发布第一章吧。</span></div>';
		$out.='<div class="aw-chapter-actions"><a class="aw-btn aw-btn--primary" href="'.pbcode('member','yc_add',$page).'">'.aw_icon('plus').'新增章节</a></div>';
		return $out;
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	$xxx='
	<ul class="chalist">'.$xxx.'</ul>
	<div class="addnewcha"><a href="'.pbcode('member','yc_add',$id).'">新增章节</a></a>';
	return $xxx;
}
function buylist(){
	global $dsql,$QUERY_STRING_ARR,$is4wap,$pay_ready;
    if(!strstr($pay_ready,'__6'.'9'.'7c'.'ode.c'.'om')){
        $x='暂未'.'开'.'启'.'VI'.'P等级'.'和充'.'值提'.'现功能'.'<br>';
        $url=pbcode('member','ucenter','','all');
        error($x,$url,3);
    }
	$uid=session('pboot_ucode');
	if(!$uid){
		return false;
	}
	$OnePAGE_NUM=10;
	$my=$QUERY_STRING_ARR[0];
	$x=$QUERY_STRING_ARR[1];
	$page=$QUERY_STRING_ARR[2];
	if($my=='money'){
		$sql1="select count(*) from (select idx from ay_member_buy where uid=$uid and lx=1 union select idx from ay_member_money where uid=$uid and lx=3) t";
		$sql2="select * from (select idx,id,jz,create_time,lx from ay_member_buy where uid=$uid and lx=1 union select idx,id,je,create_time,lx from ay_member_money where uid=$uid and lx=3) t order by t.create_time desc,t.idx desc";
	}else{
		$sql1="select count(*) from ay_member_buy where uid=$uid and lx=0";
		$sql2="select * from ay_member_buy where uid=$uid and lx=0 order by idx desc";
	}
	$x=$dsql->one("$sql1",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$OnePAGE_NUM);
	$page?'':$page='1';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("$sql2 limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx){
		$id=$xx[id];
		$jz=$xx[jz];
		$create_time=$xx[create_time];
		$lx=$xx[lx];
		if($my=='money' && $lx==3){
			if($is4wap){
				$xxx.='
		        <li class="minli">
			        <p><font>消耗金币(个)</font>-'.$jz.'</p>
			        <p><font>兑换积分(个)</font>金币=>积分</p>
			        <p><font>时间</font>'.$create_time.'</p>
		        </li>';
			}else{
				$xxx.='
		        <li>
		            <span class="green">-'.$jz.'</span>
		            <span>金币=>积分</span>
		            <span>'.$create_time.'</span>
		        </li>';
		    }
		}else{



			// {697697}



			$idx=$xx[idx];
			$x=$dsql->one("select xid from ay_member_buy where idx=$idx limit 1",1);
			$xid=$x[xid];
			if($xid){
				$x=$dsql->one("select xtitle from ay_article where xid=$xid limit 1",1);
				$xtitle=$x[xtitle];
				if($xtitle){
					$xtitle='（<a href="'.pbcode('article',$xid).'" target="_blank"><i class="green">'.$xtitle.'</i></a>）';
				}
			}else{
				$xtitle='（<i class="red">订阅全本</i>）';
			}




			$x=$dsql->one("select title from ay_content where status=1 and id=$id limit 1",1);
			$title=$x[title];
			if(!$title){
				$title='<del>内容ID：'.$id.'，该内容已删除，详情咨询客服</del>';
				$url='javascript:';
			}else{
				$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
				if(file_exists($x)){
				    $url=pbcode('book',$id);
				}else{
				    $url=pbcode('content',$id);
				}
			}
			if($is4wap){
				$xxx.='
		        <li class="minli">
			        <p><font>消耗金币(个)</font>-'.$jz.'</p>




			        <p><font>购买内容</font><a href="'.$url.'" title="'.$title.'">'.$title.$xtitle.'</a></p>




			        <p><font>时间</font>'.$create_time.'</p>
		        </li>';
			}else{
				$xxx.='
		        <li>
		            <span class="green">-'.$jz.'</span>



		            <span><a href="'.$url.'" title="'.$title.'" target="_blank">'.$title.$xtitle.'</a></span>



		            <span>'.$create_time.'</span>
		        </li>';
		    }
		}
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	if($total>$OnePAGE_NUM){
		$xxx.='{x}'.memberpage($pagenum,$prepg,$my.'/2',$nextpg,$OnePAGE_NUM,$total,$page);
	}
	if($x_==1){
		$xxx.='<style>.br .bbox:nth-child(4){display:none}</style>';
	}else if($x_==2){
		$xxx.='<style>.br .bbox:nth-child(3){display:none}</style>';
	}
	return $xxx;
}
function moneylist(){
	global $dsql,$QUERY_STRING_ARR,$is4wap,$pay_ready,$pay_ka;
    if(!strstr($pay_ready,'_697code')){
        $x='暂'.'未'.'开'.'启'.'VI'.'P'.'等'.'级'.'和'.'充'.'值提'.'现功能'.'<br>';
        $url=pbcode('member','ucenter','','all');
        error($x,$url,3);
    }
	$uid=session('pboot_ucode');
	if(!$uid){
		return false;
	}
	$OnePAGE_NUM=10;
	$my=$QUERY_STRING_ARR[0];
	$x_=$QUERY_STRING_ARR[1];
	$page=$QUERY_STRING_ARR[2];
	if($my=='money'){
		$sql="and (lx=1 or lx=2 or lx=5)";
	}else{
		$sql="and (lx=3 or lx=4)";
	}
	$x=$dsql->one("select count(*) from ay_member_money where uid=$uid $sql",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$OnePAGE_NUM);
	$page?'':$page='1';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select * from ay_member_money where uid=$uid $sql order by idx desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx){
		$idx=$xx[idx];
		$status=$xx[status];
		$ding=$xx[ding];
		$je=$xx[je];
		$jb=$xx[jb];
		$lx=$xx[lx];
		$pay=$xx[pay];
		$create_time=$xx[create_time];
		if($status==0){
			$cz='<span class="red">已取消</span>';
		}else{
			if($pay==1){
				$cz='<span class="green">成功</span>';
			}else if($pay==2){
				$cz='<span><a class="red" href="'.pbcode('member','pay',$idx).'" target="_blank">待支付</a>&nbsp;/&nbsp;<a href="'.pbcode('member','money/del',$idx).'" onclick="return tipx(3);">取消</a></span>';
			}else if($pay==3){
				$cz='<span><a class="red" href="'.pbcode('member','pay/ok',$ding).'">点我更新状态</a></span>';
			}else{
				$cz='<span><a href="'.pbcode('member','pay',$idx).'" target="_blank">待支付</a>&nbsp;/&nbsp;<a href="'.pbcode('member','money/del',$idx).'" onclick="return tipx(3);">取消</a></span>';
			}
		}
		$ding='<span>'.$ding.'</span>';
		if($lx==1){
			$lx='微信';
			if($status==1 && $pay==1){
				$je='-'.$je;
				$jb='+'.$jb;
				$c1=' class="green"';
				$c2=' class="red"';
			}
			$jet='充值金额(元)';
			$jbt='获得金币(个)';
		}else if($lx==2){
			$lx='支付宝';
			if($status==1 && $pay==1){
				$je='-'.$je;
				$jb='+'.$jb;
				$c1=' class="green"';
				$c2=' class="red"';
			}
			$jet='充值金额(元)';
			$jbt='获得金币(个)';
		}else if($lx==3){
			$lx='金币=>积分';
			$je='-'.$je;
			$jb='+'.$jb;
			$ding='';
			$cz='';
			$c1=' class="green"';
			$c2=' class="red"';
			$jet='消耗金币(个)';
			$jbt='获取积分(个)';
		}else if($lx==4){
			$lx='积分=>金币';
			$je='+'.$je;
			$jb='-'.$jb;
			$ding='';
			$cz='';
			$c1=' class="red"';
			$c2=' class="green"';
			$jet='消耗积分(个)';
			$jbt='获取金币(个)';
		}else if($lx==5){
			$lx='PayPal';
			if($status==1 && $pay==1){
				$je='-'.$je;
				$jb='+'.$jb;
				$c1=' class="green"';
				$c2=' class="red"';
			}
			$jet='Amount';
			$jbt='gold';
		}
		if($lx=='微信' || $lx=='支付宝' || $lx=='PayPal'){
			if(strstr($jb,'697')){
				$arr=explode(',',$pay_ka);
				$x=$arr[$jb-6971];
				$arr=explode('-',$x);
				$jb=$arr[0];
				if(!$jb){
					$jb='会员卡';
				}
				$c1=' class="green"';
				$c2=' class="red"';
				$jet='充值金额(元)';
				$jbt='购买：金币/卡';
			}
		}
		if($is4wap){
			$ding=str_replace(array('<span>','</span>'),'',$ding);
			$cz=str_replace(array('<span>','</span>'),'',$cz);
			if($ding){
				$ding='<p><font>订单</font>'.$ding.'</p>';
			}
			if($cz){
				$cz='<p><font>操作</font>'.$cz.'</p>';
			}
			$xxx.='
	        <li class="minli">
		        '.$ding.'
		        <p><font>'.$jet.'</font>'.$je.'</p>
		        <p><font>'.$jbt.'</font>'.$jb.'</p>
		        <p><font>类别</font>'.$lx.'</p>
		        '.$cz.'
		        <p><font>时间</font>'.$create_time.'</p>
	        </li>';
		}else{
			$xxx.='
	        <li>
	            '.$ding.'
	            <span'.$c1.'>'.$je.'</span>
	            <span'.$c2.'>'.$jb.'</span>
	            <span>'.$lx.'</span>
	            '.$cz.'
	            <span>'.$create_time.'</span>
	        </li>';
	    }
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	if($total>$OnePAGE_NUM){
		$xxx.='{x}'.memberpage($pagenum,$prepg,$my.'/1',$nextpg,$OnePAGE_NUM,$total,$page);
	}
	return $xxx;
}
function txlist(){
	global $dsql,$QUERY_STRING_ARR,$is4wap,$pay_ready;
    if(!strstr($pay_ready,'697code.com')){
        $x='暂未'.'开启'.'VI'.'P等'.'级'.'和'.'充'.'值提'.'现功能'.'<'.'br>';
        $url=pbcode('member','ucenter','','all');
        error($x,$url,3);
    }
	$uid=session('pboot_ucode');
	if(!$uid){
		return false;
	}
	$OnePAGE_NUM=10;
	$my=$QUERY_STRING_ARR[0];
	$x_=$QUERY_STRING_ARR[1];
	$page=$QUERY_STRING_ARR[2];
	$x=$dsql->one("select count(*) from ay_member_money where uid=$uid and lx=5",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$OnePAGE_NUM);
	$page?'':$page='1';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("select * from ay_member_money where uid=$uid and lx=5 order by idx desc limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx){
		$idx=$xx[idx];
		$status=$xx[status];
		$ding=$xx[ding];
		$je=$xx[je];
		$jb=$xx[jb];
		$pay=$xx[pay];
		$payxx=$xx[id];
		$create_time=$xx[create_time];
		$arr=explode('{697}',$payxx);
		$pay01=$arr[0];
		$pay02=$arr[1];
		if(!$pay01 || !$pay02){
			$zh='--';
			$pay=2;
		}else{
			$create_time2=strtotime($create_time);
			$t=7*24*60*60;
			$create_time2=$create_time2+$t;
			$time=time();
			$t=$create_time2-$time;
			if($t<0 || $status==0){
				$x01=substr_both($pay01,0,3,'');
				$x02=substr_both($pay01,3,10000,'');
				$len=strlen($x02);
				for($i=1;$i<=$len;$i++){
					$x0x.='*';
				}
				$pay01=$x01.$x0x;
				$x0x='';
				$x01=substr_both($pay02,0,2,'');
				$x02=substr_both($pay02,2,10000,'');
				$len=strlen($x02)/3;
				for($i=1;$i<=$len;$i++){
					$x0x.='*';
				}
				if(!$x0x){
					$x01=substr_both($pay02,0,1,'');
					$x02=substr_both($pay02,1,10000,'');
					$len=strlen($x02)/3;
					for($i=1;$i<=$len;$i++){
						$x0x.='*';
					}
				}
				$pay02=$x01.$x0x;
			}
			$zh='<font>'.$pay01.'</font><font>'.$pay02.'</font>';
		}
		$x_style='';
		if($status==0){
			$cz='<span class="red">已取消</span>';
		}else{
			if($pay==1){
				$cz='<span class="green">成功</span>';
			}else if($pay==2){
				$tip=$xx[tip];
				if($tip){
					$x='<img src="include/member/image/tip.png" style="position:relative;top:2px;left:2px" title="'.$tip.'">';
					if($is4wap){
						$x='<i style="color:red">（'.$tip.'）</i>';
						$x_style=' style="height:auto;white-space:normal"';
					}
				}else{
					$x='';
				}
				$cz='<span class="red">异常'.$x.'</span>';
			}else{
				$cz='<span>待审/<a href="'.pbcode('member','money/del','tx_'.$idx).'" onclick="return tipx(3);">取消</a></span>';
			}
		}
		$ding='<span>'.$ding.'</span>';
		$je='+'.$je;
		$jb='-'.$jb;
		$c1=' class="red"';
		$c2=' class="green"';
		if($is4wap){
			$ding=str_replace(array('<span>','</span>'),'',$ding);
			$cz=str_replace(array('<span>','</span>'),'',$cz);
			$xxx.='
	        <li class="minli">
		        <p><font>订单</font>'.$ding.'</p>
		        <p><font>提现金额(元)</font>'.$je.'</p>
		        <p><font>对应金币(个)</font>'.$jb.'</p>
		        <p class="zh"><font>账户</font>'.$zh.'</p>
		        <p'.$x_style.'><font>操作</font>'.$cz.'</p>
		        <p><font>时间</font>'.$create_time.'</p>
	        </li>';
		}else{
			$xxx.='
	        <li>
	            '.$ding.'
	            <span'.$c1.'>'.$je.'</span>
	            <span'.$c2.'>'.$jb.'</span>
	            <span>'.$zh.'</span>
	            '.$cz.'
	            <span>'.$create_time.'</span>
	        </li>';
		}
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	if($total>$OnePAGE_NUM){
		$xxx.='{x}'.memberpage($pagenum,$prepg,$my.'/3',$nextpg,$OnePAGE_NUM,$total,$page);
	}
	return $xxx;
}
function yc_fun(){
    global $dsql,$sqqy,$sign,$QUERY_STRING_ARR;
    $uid=session('pboot_ucode');
    $x=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
    $signcur=$x[sign];
    if($sign=='sign'){
        $sign=$QUERY_STRING_ARR[2];
        if(!$sign || ($sign!=4 && $sign!=2) || ($signcur!=2 && $signcur!=4)){
            $x='数据有误！';
            error($x,-1,3);
        }
        if($sign==4){
            $cond=author_sign_conditions($uid);
            if(!$cond['eligible']){
                $x='请先完善实名认证、作品字数与作品信息，满足签约条件后再申请！';
                $url=pbcode('member','sign');
                error($x,$url,3);
            }
        }
        $dsql->amd("update ay_member set sign=$sign where ucode=$uid limit 1");
        if($sign==4){
            $x='已申请签约，等待审核！';
        }else{
            $x='已取消签约申请！';
        }
        success($x,-1,3);
    }
    if($signcur==0){
        $x='游客';
        $xx='，发布作品并通过审核，成为普通作者，才可申请签约';
    }else if($signcur==1){
        $x='普通作者(待审核)';
        $xx='，审核通过后，才可申请签约';
    }else if($signcur==2){
        $x='普通作者';
        $xx='，可<a class="sqqy" href="'.pbcode('member','sign').'">申请签约</a>';
    }else if($signcur==3){
        $x='签约作者';
    }else if($signcur==4){
        $x='签约作者(待审核)';
        $xx='，也可<a class="sqqy" href="'.pbcode('member','yc','sign/2').'" onclick="return tipx(8);">取消签约申请</a>';
    }else{
        $x='';
    }
    if($x){
        $sqqy='<p>您当前为：<span class="blue">'.$x.'</span>'.$xx.'，点击<a class="sqqy" href="'.pbcode('member','info').'">完善作者信息</a></p>';
    }
}
function yc_add_fun(){
    global $dsql,$article;
    $uid=session('pboot_ucode');
    if($_POST){
        $ycxx=$_POST['ycxx'];
        $ycadd=$_POST['ycadd'];
        $yctitle=$_POST['yctitle'];
        $ycsubtitle=$_POST['ycsubtitle'];
        $ycico=$_FILES['ycico'];
        $ycscode=$_POST['ycscode'];
        $yccontent=$_POST['yccontent'];
        $ycupdate=$_POST['ycupdate'];
        $author_say=isset($_POST['author_say'])?$_POST['author_say']:'';
        $author_say=trim(strip_tags($author_say));
        $author_say=str_replace(array("'",'"'),'',$author_say);
        if(mb_strlen($author_say,'UTF-8')>500){
            $author_say=mb_substr($author_say,0,500,'UTF-8');
        }
        $ycupdate=strip_tags($ycupdate);
        $ycupdate=trim($ycupdate);
        $yctitle=strip_tags($yctitle);
        $yctitle=trim($yctitle);
        $yccontent=str_replace(array('script','iframe','href','src'),'',$yccontent);
        $HTTP_REFERER=$_SERVER['HTTP_REFERER'];
        if(!$yctitle || !$yccontent || !$HTTP_REFERER || !is_numeric($ycscode)){
            $x='数据有误！';
            error($x,-1,3);
        }else{
        	$time=get_datetime();
            if($article && is_numeric($article)){
                $x=$dsql->one("select xid from ay_article where xtitle='$yctitle' and id=$article limit 1",1);
                $x=$x[xid];
                if($x){
	                $x='已存在相同标题！';
	                error($x,-1,3);
                }
            }
            if($article && is_numeric($article)){
            	$x=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
            	$sign=$x[sign];
            	if($sign==3){
            		$status=1;
            	}else{
            		$status=2;
            	}
                $x1="id,xtitle,content,sorting,status,pmk,wmk,ts,errid,cjid,author_say";
                $x2="'$article','$yctitle','$yccontent','255','$status','0','0','0','0','0','$author_say'";
                $ok=$dsql->amd("insert into ay_article ($x1) values ($x2)");
                if($ok){
                	if($ycupdate){
                		$x=$dsql->one("select xid from ay_article where status in(0,1,2) and id=$article order by xid desc limit 1",1);
                		$xid_=$x[xid];
		                $x1="xid,update_time";
		                $x2="'$xid_','$ycupdate'";
		                $dsql->amd("insert into ay_member_add_update ($x1) values ($x2)");
		                $dsql->amd("update ay_article set status=2 where xid=$xid_ limit 1");
                	}
                }
            }else{
                $x=$dsql->one("select scode from ay_content_sort where pcode=$ycscode order by sorting asc limit 1",1);
                $x=$x['scode'];
                if($x){
                    $ycscode=$x;
                }
                
                $description=content4ting($yccontent);
                $description=str_replace(array('<div id="bodybox">','</div>','<p>'),'',$description);
                $description=str_replace(array('</p>'),PHP_EOL,$description);
                $description=trim($description);
                
                // $description=strip_tags($yccontent);
                
                $x=$dsql->one("select mcode from ay_content_sort where scode=$ycscode limit 1",1);
                $mcode=$x[mcode];
                if($mcode==18){
                    $yccontent='';
                }
                require_once(ROOT_PATH.'/include/py.php');
                $filename=py($yctitle);
                $x=$dsql->one("select id from ay_content where filename='$filename' limit 1",1);
                $o=$x[id];
                if($o){
                	$filename.=rand(1000,9999);
                }
                $x=$dsql->one("select nickname from ay_member where ucode=$uid limit 1",1);
                $nickname=$x[nickname];
                $x1="acode,scode,title,titlecolor,author,source,date,content,description,sorting,status,create_time,update_time,gtype,uid,mcode,subtitle,filename";
                $x2="'cn','$ycscode','$yctitle','#333333','$nickname','','$time','$yccontent','$description','255','2','$time','$time','4','$uid','$mcode','$ycsubtitle','$filename'";
                $ok=$dsql->amd("insert into ay_content ($x1) values ($x2)");
            }
            if(!$ok){
                $x='发布失败！';
                error($x,-1,3);
            }else{
                if(!$article){
                    $x=$dsql->one("select id from ay_content where title='$yctitle' order by id desc limit 1",1);
                    $ycid=$x[id];
                    uf($ycico,$ycscode,$ycid);
                }
                $x=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
                $x=$x[sign];
                if($x==0){
                    $dsql->amd("update ay_member set sign=1 where ucode=$uid limit 1");
                }
                if($article && is_numeric($article)){
	            	$x=strip_tags($yccontent);
	            	$x=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$x);
                	// $len=strlen($x);
                	// $len=ceil($len/3);

					$len=mb_strlen($x,'UTF-8');

					$x=$dsql->one("select num_zs_month from ay_content where id=$article limit 1",1);
					$num_zs_month=$x[num_zs_month];
					if($num_zs_month){
						$x=explode(',',$num_zs_month);
						$n=$x[0];
						$t=$x[1];
						$t_=date('Y-m');
						if($t==$t_){
							$n=$n+$len;
							$num_zs_month=$n.','.date('Y-m');
						}else{
							$num_zs_month=$len.','.date('Y-m');
						}
					}else{
						$num_zs_month=$len.','.date('Y-m');
					}
					$dsql->amd("update ay_content set num_zs_month='$num_zs_month' where id=$article limit 1");

                	$dsql->amd("update ay_content set subscode=subscode+$len,update_time='$time' where id=$article limit 1");
                    $url=pbcode('member','yc_chapter',$article);
                }else{
                    $url=pbcode('member','yc');
                }
                $x='发布成功！';
                success($x,$url,3);
            }
        }
    }
    $x=$dsql->one("select count(*) from ay_content where uid=$uid and status in (0,2)",1);
    $count=$x['count(*)'];
    if($count==5 || $count>5){
        $x='您有'.$count.'部作品待审或待处理<br>审核或处理后方可发布新作品<br>';
        $url=pbcode('member','yc');
        error($x,$url,5);
    }
}
function yc_edit_fun(){
    global $dsql,$article;
    $uid=session('pboot_ucode');
    $username=session('pboot_username');
    if($_POST){
        $ycid=$_POST['ycid'];
        $yctitle=$_POST['yctitle'];
        $ycscode=$_POST['ycscode'];
        $ycsubtitle=$_POST['ycsubtitle'];
        $ycico=$_FILES["ycico"];
        $yccontent=$_POST['yccontent'];
        $author_say=isset($_POST['author_say'])?$_POST['author_say']:'';
        $tags=isset($_POST['tags'])?$_POST['tags']:'';
        $author_say=trim(strip_tags($author_say));
        $tags=trim(strip_tags($tags));
        $author_say=str_replace(array("'",'"'),'',$author_say);
        $tags=str_replace(array("'",'"'),'',$tags);
        if(mb_strlen($author_say,'UTF-8')>500){
            $author_say=mb_substr($author_say,0,500,'UTF-8');
        }
        if(mb_strlen($tags,'UTF-8')>500){
            $tags=mb_substr($tags,0,500,'UTF-8');
        }
        $yctitle=strip_tags($yctitle);
        $yctitle=trim($yctitle);
        $yccontent=str_replace(array('script','iframe','href','src'),'',$yccontent);
        $HTTP_REFERER=$_SERVER['HTTP_REFERER'];
        if(!$yctitle || !$yccontent || !$HTTP_REFERER || !is_numeric($ycscode)){
            $x='数据有误！';
            error($x,-1,3);
        }else{
            if($article && is_numeric($article)){
            	$x=$dsql->one("select content from ay_article where xid=$article limit 1",1);
            	$content_=$x[content];
            	$content_=sbyouNet_Body($article,$content_,0);
            	$content_=strip_tags($content_);
            	$content_=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$content_);
            	$len_=strlen($content_);
            	$len_=ceil($len_/3);
            	$x=strip_tags($yccontent);
            	$x=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$x);
            	$len=strlen($x);
            	$len=ceil($len/3);
            	$len=$len-$len_;
	            $x=$dsql->one("select sign from ay_member where ucode=$uid limit 1",1);
	            $sign=$x[sign];
	            if($sign==3){
	            	$status=1;
	        	}else{
	        		$status=2;
	        	}
            	$dsql->amd("update ay_content set subscode=subscode+$len where id=$ycid limit 1");
                $x="xtitle='$yctitle',content='$yccontent',author_say='$author_say',status='$status',pmk=0,wmk=0,ts=0,errid=0,cjid=0";
                $dsql->amd("update ay_article set $x where xid=$article limit 1");
                $x='修改成功！';
                $url=pbcode('member','yc_chapter',$ycid);
                success($x,$url,3);
            }else{
                $x=$dsql->one("select scode from ay_content_sort where pcode=$ycscode order by sorting asc limit 1",1);
                $x=$x['scode'];
                if($x){
                    $ycscode=$x;
                }
                
                $description=content4ting($yccontent);
                $description=str_replace(array('<div id="bodybox">','</div>','<p>'),'',$description);
                $description=str_replace(array('</p>'),PHP_EOL,$description);
                $description=trim($description);
                
                // $description=strip_tags($yccontent);
                
                $x=$dsql->one("select mcode from ay_content where id=$ycid limit 1",1);
                $mcode=$x[mcode];
                if($mcode==18){
                    $yccontent='';
                }
                $update_time=get_datetime();
                $x="scode='$ycscode',title='$yctitle',content='$yccontent',status='2',subtitle='$ycsubtitle',description='$description',tags='$tags',update_time='$update_time'";
                $o=$dsql->amd("update ay_content set $x where id=$ycid limit 1");
                if($o){
                    uf($ycico,$ycscode,$ycid);
                }
                $x='修改成功！';
                $url=pbcode('member','yc');
                success($x,$url,3);
            }
        }
    }
}
function vip_fun(){
    global $dsql,$score,$scoreall,$jinbi2jifen,$pay_,$js,$pay_ready;
    if(!strstr($pay_ready,'__697code.com')){
        $x='暂未'.'开'.'启'.'VI'.'P等'.'级'.'和'.'充'.'值提'.'现功能'.'<br>';
        $url=pbcode('member','ucenter','','all');
        error($x,$url,3);
    }
    $uid=session('pboot_ucode');
    if($_POST){
        $jb=$_POST['jb'];
        $jf=$_POST['jf'];
        if($jb && is_numeric($jb)){
            $x=$dsql->one("select jinbi,score from ay_member where status=1 and ucode=$uid limit 1",1);
            $jinbi=$x[jinbi];
            $score=$x[score];
            if(!$jinbi || $jb>$jinbi){
                $x='数据有误！';
                $url=pbcode('member','vip');
                error($x,$url,3);
            }
        }else{
            $x='数据有误！';
            $url=pbcode('member','vip');
            error($x,$url,3);
        }
        $jinbi=$jinbi-$jb;
        $jf=$jb*$jinbi2jifen;
        $score=$score+$jf;
        $o=$dsql->amd("update ay_member set jinbi='$jinbi',score='$score' where ucode=$uid limit 1");
        if($o){
            $create_time=get_datetime();
            $x1="uid,status,je,jb,lx,create_time";
            $x2="'$uid','1','$jb','$jf','3','$create_time'";
            $o=$dsql->amd("insert into ay_member_money ($x1) values ($x2)");
            if($o){
                $x='兑换成功！';
                $url=pbcode('member','vip');
                success($x,$url,3);
            }else{
                $jinbi=$jinbi+$jb;
                $score=$score-$jf;
                $dsql->amd("update ay_member set jinbi='$jinbi',score='$score' where ucode=$uid limit 1");
                $x='系统有误，请稍后重试！';
                $url=pbcode('member','vip');
                error($x,$url,3);
            }
        }else{
            $x='系统有误，请稍后重试！';
            $url=pbcode('member','vip');
            error($x,$url,3);
        }
    }
    if($pay_=='jb2jf'){
        $x=$dsql->one("select jinbi from ay_member where ucode=$uid limit 1",1);
        $jinbi=$x[jinbi];
        if(!$jinbi){
            $x='您暂无金币，请充值！';
            $url=pbcode('member','pay');
            error($x,$url,3);
        }
        $jifen=$jinbi*$jinbi2jifen;
        $js='
        <script>
        var jinbi2jifen="'.$jinbi2jifen.'";
        $id("s1").innerHTML="金币：'.$jinbi.'<span>(积分'.$jifen.')</span>";
        var payje=$id("payje");
        payje.setAttribute("jb",'.$jinbi.');
        payje.setAttribute("jf",'.$jifen.');
        $id("p1").innerHTML="<span>拥有金币：</span><font id=\"max\">'.$jinbi.'</font>";
        $id("p2").innerHTML="<span>可换积分：</span>'.$jifen.'";
        $id("p3").innerHTML="<span>规则：</span>1金币='.$jinbi2jifen.'积分";
        </script>';
    }else{
        $x=$dsql->one("select score from ay_member where ucode=$uid limit 1",1);
        $score=$x[score];
        $scoreall=membergid($score,'jf');
    }
}
function money_fun(){
    global $dsql,$pay_,$idx,$jb,$jball,$tx,$js,$rmb2jinbi,$tx2rmb,$tx2sxf,$pay_ready;
    if(!strstr($pay_ready,'_'.'_6'.'97'.'co'.''.'de'.'.c'.'om')){
        $x='暂未'.'开'.'启'.'VI'.'P等'.'级和'.'充'.'值提'.'现功能<br>';
        $url=pbcode('member','ucenter','','all');
        error($x,$url,3);
    }
    $uid=session('pboot_ucode');
    if($pay_=='del' && (is_numeric($idx) || strstr($idx,'tx_'))){
        $idxcur=str_replace('tx_','',$idx);
        $o=$dsql->amd("update ay_member_money set status=0 where idx=$idxcur limit 1");
        if($o){
            if(strstr($idx,'tx_')){
                $x=$dsql->one("select jb from ay_member_money where lx=5 and idx=$idxcur limit 1",1);
                $jb=$x[jb];
                if($jb){
                    $dsql->amd("update ay_member set jinbi=jinbi+$jb where status=1 and sign=3 and ucode=$uid limit 1");
                }
            }
        }
        $x='成功取消订单！';
        success($x,-1,3);
    }else if($pay_=='tx'){
        if($_POST){
            $pay01=$_POST['pay01'];
            $pay02=$_POST['pay02'];
            $pay03=$_POST['pay03'];
            $pay04=$_POST['pay04'];
            $je=$_POST['je'];
            $jb=$_POST['jb'];
            $pay01=str_replace(' ','',$pay01);
            $pay02=str_replace(' ','',$pay02);
            $pay03=str_replace(' ','',$pay03);
            $pay04=str_replace(' ','',$pay04);
            $je=str_replace(' ','',$je);
            $jb=str_replace(' ','',$jb);
            if(!$je || !$jb || !is_numeric($je) || !is_numeric($jb) || !$rmb2jinbi || !$tx2rmb || !is_numeric($rmb2jinbi) || !is_numeric($tx2rmb) || !strstr($tx2sxf,'%')){
                $x='数据有误！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
            if(!$pay01 || !$pay02 || (strstr($pay01,'*') && !strstr($pay02,'*')) || (!strstr($pay01,'*') && strstr($pay02,'*'))){
                $x='请填写正确的账户信息！';
                error($x,-1,3);
            }
            $x=$dsql->one("select jinbi,payxx from ay_member where status=1 and ucode=$uid and sign=3 limit 1",1);
            $jinbi=$x[jinbi];
            $payxx0=$x[payxx];
            if(!$jinbi || $jinbi<$jb){
                $x='数据有误！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
            $arr=explode('{697}',$payxx0);
            $x1=$arr[0];
            $x2=$arr[1];
            $x3=$arr[2];
            $payxx0=$x1.'{697}'.$x2;
            $paypwd=$x3;
            if(!$paypwd){
                if(!$pay03 || !$pay04 || $pay03!=$pay04){
                    $x='提现密码不可为空，并且两次输入要一致！';
                    error($x,-1,3);
                }
                $pwdmd5=md5($pay03);
            }else{
                if(!$pay03){
                    $x='提现密码不可为空！';
                    error($x,-1,3);
                }
                $pwdmd5=md5($pay03);
                if($pwdmd5!=$paypwd){
                    $x='提现密码不正确！';
                    error($x,-1,3);
                }
            }
            $je2=$jb/$rmb2jinbi;
            $tx2sxf2=str_replace('%','',$tx2sxf);
            $tx2sxf2=$tx2sxf2/100;
            $sxf=ceil($je2*$tx2sxf2);
            $je2=$je2-$sxf;
            if($je2!=$je){
                $x='数据有误！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
            $payxx=$pay01.'{697}'.$pay02;
            if(strstr($pay01,'*') && strstr($pay02,'*')){
                if(!$payxx0){
                    $x='请填写正确的账户信息！';
                    error($x,-1,3);
                }else{
                    $payxx=$payxx0;
                }
            }
            $ding=date('YmdHis',time());
            $ding.=rand(10,99);
            $create_time=get_datetime();
            $x01="uid,status,ding,je,jb,lx,pay,id,create_time";
            $x02="'$uid','1','$ding','$je','$jb','5','0','$payxx','$create_time'";
            $o=$dsql->amd("insert into ay_member_money ($x01) values ($x02)");
            if($o){
                $x=$jinbi-$jb;
                if($x<0){
                    $x=0;
                }
                $o=$dsql->amd("update ay_member set jinbi=$x where ucode=$uid limit 1");
                if(!$o){
                    $dsql->amd("delete from ay_member_money where uid=$uid and ding='$ding' limit 1");
                    $x='系统异常，稍后重试！';
                    $url=pbcode('member','money');
                    error($x,$url,3);
                }else{
                    if($pwdmd5){
                        $payxx.='{697}'.$pwdmd5;
                    }
                    $dsql->amd("update ay_member set payxx='$payxx' where ucode=$uid limit 1");
                    $x='提交成功，等待审核！';
                    $url=pbcode('member','money',3);
                    success($x,$url,3);
                }
            }else{
                $x='系统异常，稍后重试！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
        }
        if(!$rmb2jinbi || !$tx2rmb || !$tx2sxf || !strstr($tx2sxf,'%')){
            $x='数据有误！';
            $url=pbcode('member','money');
            error($x,$url,3);
        }
        $x=$dsql->one("select jinbi,sign,payxx from ay_member where ucode=$uid limit 1",1);
        $jinbi=$x[jinbi];
        $sign=$x[sign];
        $payxx=$x[payxx];
        if($sign!=3){
            $x='数据有误！';
            $url=pbcode('member','money');
            error($x,$url,3);
        }
        $je=$jinbi/$rmb2jinbi;
        if($je<$tx2rmb){
            $je=0;
        }
        $tx2sxf2=str_replace('%','',$tx2sxf);
        $tx2sxf2=$tx2sxf2/100;
        $sxf=ceil($je*$tx2sxf2);
        if($sxf){
            $xsf2=' <font>（手续费：'.$sxf.'元）</font>';
        }
        $je2=$je-$sxf;
        if($je2<0){
            $je2=0;
        }
        $txjb=$je*$rmb2jinbi;
        $arr=explode('{697}',$payxx);
        $pay01=$arr[0];
        $pay02=$arr[1];
        $pay03=$arr[2];
        $x01=substr_both($pay01,0,3,'');
        $x02=substr_both($pay01,3,10000,'');
        $len=strlen($x02);
        for($i=1;$i<=$len;$i++){
            $x0x.='*';
        }
        $pay01=$x01.$x0x;
        $x0x='';
        $x01=substr_both($pay02,0,2,'');
        $x02=substr_both($pay02,2,10000,'');
        $len=strlen($x02)/3;
        for($i=1;$i<=$len;$i++){
            $x0x.='*';
        }
        if(!$x0x){
            $x01=substr_both($pay02,0,1,'');
            $x02=substr_both($pay02,1,10000,'');
            $len=strlen($x02)/3;
            for($i=1;$i<=$len;$i++){
                $x0x.='*';
            }
        }
        $pay02=$x01.$x0x;
        if($pay03){
            $paypwd='<li onclick="chpayxx(3)">提现密码：<input id="pay03" type="password" onchange="chpayxx(3)" onkeyup="chpayxx(3)" value=""></li>';
            $pwdfont='（若忘记提现密码，可联系客服处理）';
        }else{
            $paypwd='
            <li onclick="chpayxx(3)">设置密码：<input id="pay03" type="password" onchange="chpayxx(3)" onkeyup="chpayxx(3)" value=""></li>
            <li onclick="chpayxx(4)">重复密码：<input id="pay04" type="password" onchange="chpayxx(4)" onkeyup="chpayxx(4)" value=""></li>';
            $paypwd=str_replace(array(PHP_EOL,"\n","\r","\t"),'',$paypwd);
            $paypwd=trim($paypwd);
            $pwdfont='（首次提现需要设置一个提现密码，并且牢记，以后都要验证此密码）';
        }
        $js='
        <script>
        var rmb2jinbi="'.$rmb2jinbi.'";
        var tx2rmb="'.$tx2rmb.'";
        var tx2sxf="'.$tx2sxf2.'";
        $id("s1").innerHTML="金币：'.$txjb.'<span>(￥'.$je2.')</span>";
        var payje=$id("payje");
        payje.setAttribute("jb",'.$txjb.');
        payje.setAttribute("je",'.$je2.');
        $id("pay01").value="'.$pay01.'";
        $id("pay02").value="'.$pay02.'";
        $id("p1").innerHTML="<span>拥有金币：</span><font id=\"max\">'.$jinbi.'</font>";
        $id("p2").innerHTML="<span>可提现：</span>'.$je2.'元'.$xsf2.'";
        $id("p3").innerHTML="<span>规则：</span>'.$rmb2jinbi.'金币=1元";
        $id("p4").innerHTML="1、单笔提现金额：≥'.$tx2rmb.'元（金币'.($tx2rmb*$rmb2jinbi).'个）";
        $id("p5").innerHTML="2、提现手续费：'.$tx2sxf.'，非整数的手续费会向上取整，如：2.1元会收取3元";
        $id("p6").innerHTML="3、温馨提示：提现的金币数量，为'.$rmb2jinbi.'的整数倍时，手续费最少";
        $id("paypwd").innerHTML=\''.$paypwd.'\';
        $id("pwdfont").innerHTML="'.$pwdfont.'";
        </script>';
    }else{
        $x=$dsql->one("select jinbi,sign from ay_member where ucode=$uid limit 1",1);
        $jb=$x[jinbi];
        $sign=$x[sign];
        $jball=membergid($jb,'jb');
        if($sign==3){
            $tx='<a class="atx" href="'.pbcode('member','money','tx').'" title="提现">提现</a>';
        }
    }
}
function pay_fun(){
    global $dsql,$HTTP_HOST,$pay_,$ding_,$id,$js,$pay_appid,$pay_appsecret,$pay_fn,$pay_xxx,$dingyue,$dy,$rmb2jinbi,$jinbi2jifen,$pay_ready,$pay_je,$pay_btn,$pay_ka;
    if(!strstr($pay_ready,'_'.''.'_6'.'9'.''.'7'.'c'.'o'.''.'d'.''.'e'.'.c'.'o'.'m')){
        $x=''.'暂'.''.'未开'.'启'.'V'.'I'.'P等'.'级'.''.'和'.'充'.'值提'.'现功'.'能<b'.'r>';
        $url=pbcode('member','ucenter','','all');
        error($x,$url,3);
    }
    $uid=session('pboot_ucode');
    $gcode=session('pboot_gcode');
    if($_POST && $pay_!='vip'){
        $idx=$_POST['idx'];
        $lx=$_POST['lx'];
        $je=$_POST['je'];
        $jb=$_POST['jb'];
        $id=$_POST['id'];
        if($idx && is_numeric($idx)){
            $x=$dsql->one("select uid,ding from ay_member_money where status=1 and pay in (0,2) and lx=$lx and idx=$idx limit 1",1);
            $uidc=$x[uid];
            $ding=$x[ding];
            if($uidc!=$uid){
                $x='数据有误！';
                $url=pbcode('member','ucenter');
                error($x,$url,3);
            }
        }
        $x=maxjie_com(0,$ding,$je,$jb,$lx,0,$id);
        if($x){
            $x='成功提交订单！';
            $url=pbcode('member','pay','go');
            success($x,$url,3);
        }else{
            $x='网络错误，稍后重试！';
            $url=pbcode('member','money');
            error($x,$url,3);
        }
    }
    if($pay_=='go'){
    	global $database;
    	$x=$database['dbn'.'ame'];
    	$x=md5($x);
        $f=ROOT_PATH.'/p'.''.'a'.''.'y'.'/'.$pay_fn.'/'.$x.'.php';
        if(!$pay_appid || !$pay_appsecret || !$pay_fn || !$pay_xxx || !file_exists($f)){
            $x='数据异常！';
            $url=pbcode('member','money');
            error($x,$url,3);
        }
        $sessarr=paysession(1);
        require_once($f);
        exit;
    }else if($pay_=='ok'){
        $ding_=strip_tags($ding_);
        $ding_=trim($ding_);
        if(strstr($ding_,'jf')){
            $dy='jf';
        }
        if(strstr($ding_,'jb')){
            $dy='jb';
        }
        $arr=explode('_',$ding_);
        $x=$arr[1];


		// {697697}


        $xid=$arr[2];





        if($x){
            $ding_=$x;
        }
        if($dy){




            $x=dycontent($dy,$ding_,$xid);




            if(!$x){
                $x='数据异常！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
            $arr=explode('{697}',$x);
            $id=$arr[0];
            $title=$arr[1];
            $jifen=$arr[2];
            $jinbi=$arr[3];
            $time=$arr[4];
            if($dy=='jf'){
                $kc='<li>扣除积分：'.$jifen.'个</li>';
            }else{
                $kc='<li>扣除金币：'.$jinbi.'个</li>';
            }
            $x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';




            if(file_exists($x)){
            	$url=pbcode('book',$id);
            	if($xid){
            		$url=pbcode('article',$xid);
            		$x=$dsql->one("select xtitle from ay_article where xid=$xid limit 1",1);
            		$xtitle=$x[xtitle];
            		$title=$xtitle;
            	}
            }else{
            	$url=pbcode('content',$id);
            }




            $js='
            <ul class="po">
                <li>订阅内容：'.$title.'</li>
                '.$kc.'
                <li>扣除状态：<span>成功</span></li>
                <li>订阅时间：'.$time.'</li>
            </ul>
            <div class="pot" id="pot">
                <p>恭喜您，订阅成功啦！</p><p>即将返回内容页面（<span id="st">3</span>）</p>
            </div>
            <script>
            var url="'.$url.'";
            sectime(url);
            </script>';
        }else{
            if($ding_ && is_numeric($ding_)){
                $x=$dsql->one("select ding,je,jb,id,create_time from ay_member_money where status=1 and uid=$uid and ding='$ding_' limit 1",1);
                $ding=$x[ding];
                $je=$x[je];
                $jb=$x[jb];
                $id=$x[id];
                $create_time=$x[create_time];
            }
            if(!$ding){
                $x='数据异常！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
            if($id){
                $url=pbcode('member','pay','content_'.$id);
                $url3='var url3="'.$url.'"';
            }else{
                $url3='var url3="";';
            }
            if(strstr($jb,'697')){
            	$arr=explode(',',$pay_ka);
				$x=$arr[$jb-6971];
				$x=explode('-',$x);
				$jb=$x[0];
            	$x='<li>会员卡：'.$jb.'</li>';
            }else{
            	$x='<li>对应金币：'.$jb.'个</li>';
            }
            $js='
            <ul class="po">
                <li>订单号：'.$ding.'</li>
                <li>充值金额：'.$je.'元</li>
                '.$x.'
                <li>充值状态：<span id="zt"><img src="template/tpl/image/loading.gif"></span></li>
                <li>充值时间：'.$create_time.'</li>
            </ul>
            <div class="pot" id="pot">
                <p><img src="template/tpl/image/loading.gif"> 正在查询充值状态...</p>
                <p></p>
            </div>
            <script>var ding="'.$ding.'";'.$url3.'</script>';
        }
    }else if(is_numeric($pay_)){
        $x=$dsql->one("select * from ay_member_money where status=1 and idx=$pay_ limit 1",1);
        $uidc=$x[uid];
        $je=$x[je];
        $jb=$x[jb];
        $lx=$x[lx];
        $pay=$x[pay];
        if($uidc==$uid && ($pay==0 || $pay==2)){
        	if(strstr($jb,'697')){
        		$x=$jb-6970;
        		$x='chpay(3,'.$x.');';
        	}else{
	        	if($pay_je){
		        	$x=explode(',',$pay_je);
		        	$c=count($x);
		        }else{
		        	$c=0;
		        }
	        	$c=$c+1;
	        	$x='chpay(2,'.$c.');$id("om").value="'.$je.'";chje();';
        	}
            $js='<script>chpay(1,'.$lx.');'.$x.'pay();</script>';
        }else if($uidc==$uid && $pay==3){
            $o=$dsql->amd("update ay_member set jinbi=jinbi+$jb where ucode=$uid limit 1");
            if($o){
                $o=$dsql->amd("update ay_member_money set pay=1 where idx=$pay_ limit 1");
                if(!$o){
                    $dsql->amd("update ay_member set jinbi=jinbi-$jb where ucode=$uid limit 1");
                    $x='网络异常，稍后重试！';
                    $url=pbcode('member','money');
                    error($x,$url,3);
                }else{
                    $x='订单状态更新成功！';
                    $url=pbcode('member','money');
                    success($x,$url,3);
                }
            }else{
                $x='网络异常，稍后重试！';
                $url=pbcode('member','money');
                error($x,$url,3);
            }
        }else{
            $x='订单有误！';
            $url=pbcode('member','money');
            error($x,$url,3);
        }
    }else if(strstr($pay_,'content_')){
        $id=str_replace('content_','',$pay_);



		// {697697}



        global $xid;
        $x=explode('_',$id);
        $id=$x[0];
        $xid=$x[1];
        if(!$xid){
        	$xid=0;
        }



        if(!is_numeric($id)){
            $x='数据有误！';
            $url=pbcode('member','ucenter');
            error($x,$url,3);
        }
        $x=$dsql->one("select id,title,jinbi,jifen,gtype,gid from ay_content where status=1 and id=$id limit 1",1);
        $o=$x[id];
        $title=$x[title];
        $jinbi=$x[jinbi];
        $jifen=$x[jifen];
        $gtype=$x[gtype];
        $gid=$x[gid];

        global $vip_all;

        if(!$o || (!$gid && !$vip_all)){
            $x='数据有误！';
            $url=pbcode('member','ucenter');
            error($x,$url,3);
        }






        $x=$dsql->one("select idx from ay_member_buy where uid=$uid and id=$id and (xid=$xid or xid=0) limit 1",1);





        $o=$x[idx];
        if($o){
			$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
			if(file_exists($x)){
			    $url=pbcode('book',$id);
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
				    $x=$dsql->one("select count(*) from ay_article where status=1 and id=$id and xid>$max_xid order by xid asc",1);
				    $need_pay_chapter_num=$x['count(*)'];
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



		// {697697}


        global $chapter_jb,$free_chapter;
        if($chapter_jb){

	        $jifen=0;

	        if($xid){
	        	$jinbi=$chapter_jb;
	        }else{
		        $x=$dsql->one("select count(*) from ay_article where status=1 and id=$id",1);
		        $count=$x['count(*)'];
		        $x=$dsql->one("select count(*) from ay_member_buy where id=$id and uid=$uid",1);
		        $count_=$x['count(*)'];
		        $num=$count-$free_chapter-$count_;
		        if($need_pay_chapter_num){
		        	$num=$need_pay_chapter_num;
		        }
		        $jinbi=$num*$chapter_jb;
		    }
		}





        $x=$dsql->one("select jinbi,score from ay_member where ucode=$uid limit 1",1);
        $jinbi_uid=$x[jinbi];
        $jifen_uid=$x[score];
        if($jifen && $jinbi){
            if($jifen_uid>=$jifen && $jinbi_uid>=$jinbi){
                $dy='all';
            }else if($jifen_uid>=$jifen && $jinbi_uid<$jinbi){
                $dy='jf';
            }else if($jifen_uid<$jifen && $jinbi_uid>=$jinbi){
                $dy='jb';
            }else{
                $dy='je';
                $x=$jifen-$jifen_uid;
                if($x){
                    $money1=ceil($x/$jinbi2jifen/$rmb2jinbi);
                }
                $x=$jinbi-$jinbi_uid;
                if($x){
                    $money2=ceil($x/$rmb2jinbi);
                }
                if($money1==$money2){
                    $money=$money1;
                }else if($money1<$money2 && $money1){
                    $money=$money1;
                }else if($money2<$money1 && $money2){
                    $money=$money2;
                }
                if($money){
                	if($pay_btn=='3'){
                		$btn=2;
                	}else{
                		$btn=1;
                	}
                	if($pay_je){
			        	$x=explode(',',$pay_je);
			        	$c=count($x);
			        }else{
			        	$c=0;
			        }
		        	$c=$c+1;
                    $js='<script>chpay(1,'.$btn.');chpay(2,'.$c.');$id("om").value="'.$money.'";chje();window.scrollTo(0,0);</script>';
                    $money='<p>最低需要充值 <span style="font-size:24px">'.$money.'</span> 元</p>';
                }
            }


			// {697697}

            $jb='<p><span>用金币订阅：</span>需要消耗金币'.$jinbi.'个</p>';



            $p='您当前有金币'.$jinbi_uid.'个';



        }else if($jifen && !$jinbi){
            if($jifen_uid>=$jifen){
                $dy='jf';
            }else{
                $dy='je';
                $x=$jifen-$jifen_uid;
                if($x){
                    $money=ceil($x/$jinbi2jifen/$rmb2jinbi);
                }
                if($money){
                	if($pay_btn=='3'){
                		$btn=2;
                	}else{
                		$btn=1;
                	}
                	if($pay_je){
			        	$x=explode(',',$pay_je);
			        	$c=count($x);
			        }else{
			        	$c=0;
			        }
		        	$c=$c+1;
                    $js='<script>chpay(1,'.$btn.');chpay(2,'.$c.');$id("om").value="'.$money.'";chje();window.scrollTo(0,0);</script>';
                    $money='<p>最低需要充值 <span style="font-size:24px">'.$money.'</span> 元</p>';
                }
            }
            $jf='<p><span>用积分订阅：</span>需要消耗积分'.$jifen.'个</p>';
            $p='您当前有积分'.$jifen_uid.'个';
        }else if(!$jifen && $jinbi){
            if($jinbi_uid>=$jinbi){
                $dy='jb';
            }else{
                $dy='je';
                $x=$jinbi-$jinbi_uid;
                if($x){
                    $money=ceil($x/$rmb2jinbi);
                }
                if($money){
                	if($pay_btn=='3'){
                		$btn=2;
                	}else{
                		$btn=1;
                	}
                	if($pay_je){
			        	$x=explode(',',$pay_je);
			        	$c=count($x);
			        }else{
			        	$c=0;
			        }
		        	$c=$c+1;
                    $js='<script>chpay(1,'.$btn.');chpay(2,'.$c.');$id("om").value="'.$money.'";chje();window.scrollTo(0,0);</script>';
                    $money='<p>最低需要充值 <span style="font-size:24px">'.$money.'</span> 元</p>';
                }
            }
            $jb='<p><span>用金币订阅：</span>需要消耗金币'.$jinbi.'个</p>';
            $p='您当前有金币'.$jinbi_uid.'个';
        }else{
            $dy='je';
        }
        if($dy=='je'){
            $x=$dsql->one("select count(*) from ay_member_money where uid=$uid and status=1 and lx in (1,2,5) and pay in (0,2,3)",1);
            $count=$x['count(*)'];
            if($count==5 || $count>5){
                $x='您有'.$count.'条订单待处理<br>处理后方可重新下单！';
                $url=pbcode('member','money');
                error($x,$url,5);
            }
            $tip='
            <p class="tip">您的积分或金币余额不足，请在下方充值！</p>



            <p class="mt5">1元='.$rmb2jinbi.'金币。</p>
            <p>有些内容任何人都可以阅览；有些内容只有特定会员等级才可以阅览。</p>
            <p>有些内容只能用金币订阅。</p>



            '.$money;
        }
        $tip_=checklevel($gtype,$gid,$id,$xid);
        $arr=explode('{697}',$tip_);
        $my=$arr[0];
        $my=trim($my);
        $tip_=$arr[1];
        $tip_=trim($tip_);
        if($my=='gid_err'){
            $x=strip_tags($tip_);
            $url=pbcode('member','money');
            error($x,$url,3);
        }else if($my=='nogid'){
            $tip=explode('<!--697-->',$tip_);
            $tip=$tip[0];
            $tip=explode('</p>',$tip);
            $tip=$tip[1];
            $tip=strip_tags($tip);
            $tip=trim($tip);
            if($gtype<4){
                $url=pbcode('member','ucenter');
                error($tip,$url,3);
            }
            $x=$dsql->one("select gname from ay_member_group where gcode=$gcode limit 1",1);
            $gname=$x[gname];
            $dd=$dsql->all("select gcode,gname,lscore,uscore from ay_member_group where status=1 order by gcode asc",1);
            foreach($dd as $xx){
                $gcodecur=$xx[gcode];
                $gnamecur=$xx[gname];
                $lscorecur=$xx[lscore];
                $uscorecur=$xx[uscore];
                $dj.='<p>'.$gnamecur.'（积分>='.$lscorecur.'）</p>';
                if($gid==$gcodecur){
                    $x=$dsql->one("select score from ay_member where ucode=$uid limit 1",1);
                    $score=$x[score];
                    $scoreall=membergid($score,'jf');
                    if($gtype==5){
                        $x=$uscorecur-$scoreall+1;
                        $score_=$uscorecur+1;
                        $gname_='大于';
                    }else{
                        $x=$lscorecur-$scoreall;
                        $score_=$lscorecur;
                        $gname_='大于或等于';
                    }
                    $money=ceil($x/$jinbi2jifen/$rmb2jinbi);
                    $gname_.=$gnamecur;
                    if($money<=0){
                        $x='数据有误，正在返回内容页！';
						$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
						if(file_exists($x)){
						    $url=pbcode('book',$id);
						}else{
						    $url=pbcode('content',$id);
						}
                        error($x,$url,3);
                    }
                }
            }
            $tip='
            <p><b>会员权限：</b></p>
            '.$dj.'
            <p class="tip">您当前的会员权限为'.$gname.'</p>
            <p class="tip">'.$tip.'</p>
            <p class="mt5"><b>充值规则：</b></p>
            <p>1元='.$rmb2jinbi.'金币，1金币='.$jinbi2jifen.'积分</p>
            <p>历史积分'.$scoreall.'，需要达到积分'.$score_.'</p>
            <p class="red">需要充值<span style="font-size:24px">'.$money.'</span> 元，才可升级为'.$gname_.'</p>';
        	if($pay_btn=='3'){
        		$btn=2;
        	}else{
        		$btn=1;
        	}
        	if($pay_je){
	        	$x=explode(',',$pay_je);
	        	$c=count($x);
	        }else{
	        	$c=0;
	        }
        	$c=$c+1;
            $js='<script>chpay(1,'.$btn.');chpay(2,'.$c.');$id("om").value="'.$money.'";chje();window.scrollTo(0,0);</script>';
            $dy='je';
            $jf='';
            $jb='';
            $p='';
        }else if($my=='vali'){
            $x=$dsql->one("select validdate,score,jinbi from ay_member where ucode=$uid limit 1",1);
            $validdate=$x[validdate];
            $x=explode('</p>',$tip_);
            $x=$x[0];
            $x=strip_tags($x);
            $x=trim($x);
            $x='
            <p class="tip">'.$x.'</p>
            <p class="tip">充值任意金额自动续期一年！</p>
            <p class="mt5">到期时间为：'.$validdate.'</p>
            <p>充值后，金币依然会进账，同时，会员有效期也会续期</p>';
            $tip=$x;
            $dy='je';
            $jf='';
            $jb='';
            $p='';
            $js='';
        }else if(!$jinbi && !$jifen){
			$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
			if(file_exists($x)){
			    $url=pbcode('book',$id);
			}else{
			    $url=pbcode('content',$id);
			}
            $x='当前内容无需订阅！';
            success($x,$url,3);
        }
        if($p){
            $p='<p class="p">'.$p.'</p>';
        }else{
            $p='<p class="p" style="height:0px"></p>';
        }




		// {697697}


        if($xid){
        	$x=$dsql->one("select xtitle from ay_article where xid=$xid limit 1",1);
        	$xtitle=$x[xtitle];
        	$bt='
        	<p><span>订阅一个章节：</span>'.$xtitle.'</p>
            '.$jf.'
            '.$jb.'
            <p><span>此章属于小说：</span>'.$title.'</p>';
        }else{
        	$bt='<p><span>全本订阅：</span>'.$title.'</p>
            '.$jf.'
            '.$jb;
        }




        $dingyue='
        <div class="dingyue">
            '.$bt.'
            '.$p.'
            '.$tip.'
        </div>';
    }else{
        $x=$dsql->one("select count(*) from ay_member_money where uid=$uid and status=1 and lx in (1,2,5) and pay in (0,2,3)",1);
        $count=$x['count(*)'];
        if($count==5 || $count>5){
            $x='您有'.$count.'条订单待处理<br>处理后方可重新下单！';
            $url=pbcode('member','money');
            error($x,$url,5);
        }
    }
}
function mka_box(){
	global $dsql,$pay_ka,$pay_ready;
	if(!$pay_ka){
		$xxx='<style>.mka_box{display:none}</style>';
		return $xxx;
	}
	$uid=session('pboot_ucode');
	$time=time();
	if($uid){
		$x=$dsql->one("select mka,mka_date from ay_member where ucode=$uid limit 1",1);
		$mka=$x[mka];
		$mka_date=$x[mka_date];
		$arr=explode(',',$mka_date);
		$date01=$arr[0];
		$date02=$arr[1];
	}
	if($mka){
		$arr=explode(',',$pay_ka);
		$ka=$arr[$mka-1];
		$arr=explode('-',$ka);
		$ka=$arr[0];
		if($date02<$time){
			$ka_='会员卡已过期';
			$tip='您的'.$ka.'会员卡过期啦，立即续费享受更多权益吧！';
		}else{
			$ka_='您是“'.$ka.'”会员！';
			$tip='尊贵的'.$ka.'会员，您可尽情阅览本站所有内容啦！';
		}
		$url=pbcode('member','pay');
		$aw='续费/升级';
		$dq='<p><span>有效期：'.date('Y-m-d H:i:s',$date01).' 至 '.date('Y-m-d H:i:s',$date02).'</span></p>';
	}else{
		$ka_='开通会员卡';
		$arr=explode(',',$pay_ka);
		foreach($arr as $xx){
			$x=explode('-',$xx);
			$v=$x[0];
			$tip_.=$v.'/';
		}
		$tip_=rtrim($tip_,'/');
		$tip='开通'.$tip_.'！';
		$url=pbcode('member','pay');
		$aw='立即开通';
		$dq='尽情阅览本站所有内容~.~';
	}
	if(!strstr($pay_ready,'__697code.com')){
		$ka_.='<span class="lock_ico"></span>';
	}
	$xxx='
    <li>
        <p>'.$ka_.'</p>
        <p><span>'.$tip.'</span></p>
        '.$dq.'
        <a href="'.$url.'">'.$aw.'</a>
    </li>';
	return $xxx;
}
function tglist(){
	global $dsql,$QUERY_STRING_ARR,$is4wap,$locoysafe,$pcbaseall;
	$uid=session('pboot_ucode');
	if(!$uid){
		$uid=0;
	}
	$OnePAGE_NUM=10;
	$my=$QUERY_STRING_ARR[0];
	$x_=$QUERY_STRING_ARR[1];
	$page=$QUERY_STRING_ARR[2];
	$sql1="select count(*) from ay_tg where status=1";
	$sql2="select * from ay_tg where status=1 order by id desc";
	$x=$dsql->one("$sql1",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$OnePAGE_NUM);
	$page?'':$page='1';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("$sql2 limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx){
		$id=$xx[id];
		$link=$xx[link];
		if(!$link){
			continue;
		}
		$link_=$pcbaseall.'t/';
		$md5=md5($id.$uid.$locoysafe);
		$hash='?'.$id.'-'.$uid.'-'.$md5;
		$link_.=$hash;
		if($is4wap){
			$xxx.='
	        <li class="minli">
		        <p><font>编号</font>'.$id.'</p>
		        <p><font>链接</font><a href="'.$link_.'" title="'.$link.'" target="_blank" id="link'.$id.'">'.$link.'</a></p>
		        <p><font>推广</font><a href="javascript:cplink('.$id.');" id="btn'.$id.'">复制</a></p>
	        </li>';
		}else{
			$xxx.='
	        <li>
		        <span>'.$id.'</span>
	            <span><a href="'.$link_.'" title="'.$link.'" target="_blank" id="link'.$id.'">'.$link.'</a></span>
	            <span onclick="cplink('.$id.')" id="btn'.$id.'">复制</span>
	        </li>';
	    }
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	if($x_==1){
		$xxx.='<style>.bbox.bbox2{display:none}</style>';
	}
	if($total>$OnePAGE_NUM){
		$xxx.='{x}'.memberpage($pagenum,$prepg,$my.'/1',$nextpg,$OnePAGE_NUM,$total,$page);
	}
	return $xxx;
}
function yjlist(){
	global $dsql,$QUERY_STRING_ARR,$is4wap,$locoysafe,$pcbaseall;
	$uid=session('pboot_ucode');
	if(!$uid){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
		return $xxx;
	}
	$OnePAGE_NUM=10;
	$my=$QUERY_STRING_ARR[0];
	$x_=$QUERY_STRING_ARR[1];
	$page=$QUERY_STRING_ARR[2];
	$sql1="select count(*) from ay_tg_jinbi where uid=$uid";
	$sql2="select * from ay_tg_jinbi where uid=$uid order by id desc";
	$x=$dsql->one("$sql1",1);
	$total=$x['count(*)'];
	$pagenum=ceil($total/$OnePAGE_NUM);
	$page?'':$page='1';
	$page=min($pagenum,$page);!$page?$page=1:"";
	$prepg=$page-1;
	$nextpg=($page==$pagenum?0:$page+1);
	$offset=($page-1)*$OnePAGE_NUM;
	$dd=$dsql->all("$sql2 limit $offset,$OnePAGE_NUM",1);
	foreach($dd as $xx){
		$tgid=$xx[tgid];
		$jinbi=$xx[jinbi];
		$update_time=$xx[update_time];
		if(!$jinbi){
			continue;
		}
		$x=$dsql->one("select link from ay_tg where id=$tgid limit 1",1);
		$link=$x[link];
		if(!$link){
			$link_='链接已下线';
		}else{
			$link_=$pcbaseall.'t/';
			$md5=md5($id.$uid.$locoysafe);
			$hash='?'.$id.'-'.$uid.'-'.$md5;
			$link_.=$hash;
			$link_='<a href="'.$link_.'" title="'.$link.'" target="_blank" id="link'.$id.'">'.$link.'</a>';
		}
		if($is4wap){
			$xxx.='
	        <li class="minli">
		        <p><font>佣金</font><b class="red">+'.$jinbi.'</b></p>
		        <p><font>链接</font>'.$link_.'</p>
		        <p><font>时间</font>'.$update_time.'</p>
	        </li>';
		}else{
			$xxx.='
	        <li>
		        <span class="red">+'.$jinbi.'</span>
	            <span>'.$link_.'</span>
	            <span>'.$update_time.'</span>
	        </li>';
	    }
	}
	if(!$xxx){
		$xxx='<p class="nolist">抱歉，暂时没有相关内容...</p>';
	}
	if($x_==2){
		$xxx.='<style>.bbox{display:none}.bbox.bbox2{display:block}</style>';
	}
	if($total>$OnePAGE_NUM){
		$xxx.='{x}'.memberpage($pagenum,$prepg,$my.'/2',$nextpg,$OnePAGE_NUM,$total,$page);
	}
	return $xxx;
}
?>