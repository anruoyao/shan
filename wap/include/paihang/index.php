<?php
$sh_wap_rank_types = array(
	'sale' => '畅销',
	'month' => '月票',
	'vote' => '推荐',
	'urge' => '催更',
	'fav' => '收藏',
	'new' => '新书',
	'update' => '更新'
);
$sh_wap_rank_periods = array(
	'day' => array('label' => '日榜', 'days' => 1),
	'week' => array('label' => '周榜', 'days' => 7),
	'month' => array('label' => '月榜', 'days' => 30),
	'all' => array('label' => '总榜', 'days' => 0)
);
$my = isset($path[2]) ? $path[2] : '';
$page = isset($path[3]) ? $path[3] : '';
foreach (array('&', '=', '?') as $sh_wap_delimiter) {
	$my = explode($sh_wap_delimiter, $my, 2)[0];
	$page = explode($sh_wap_delimiter, $page, 2)[0];
}
if ($my === 'visits') $my = 'sale';
if (!isset($sh_wap_rank_types[$my])) $my = 'sale';
if (!isset($sh_wap_rank_periods[$page])) $page = 'week';

$sh_wap_rank_rows = array();
$sh_wap_rank_empty = '';
$sh_wap_rank_days = intval($sh_wap_rank_periods[$page]['days']);
$sh_wap_rank_label = $sh_wap_rank_types[$my];
$sh_wap_rank_note = '';

if ($my === 'month' || $my === 'urge') {
	$sh_wap_rank_empty = '本站暂未保存月票或催更统计数据';
} else if ($my === 'fav') {
	$sh_wap_fav_period = $sh_wap_rank_days > 0
		? " and s.create_time >= date_sub(now(), interval $sh_wap_rank_days day)"
		: '';
	$sh_wap_rank_rows = $dsql->all("select b.id,b.title,b.author,b.description,b.ico,b.update_time,count(distinct s.uid) as rank_metric
		from ay_member_stow s inner join ay_content b on b.id=s.id
		where b.status=1 $sh_wap_fav_period
		group by b.id order by rank_metric desc,b.id desc limit 50",1);
	if (!$sh_wap_rank_rows && $sh_wap_rank_days > 0) {
		$sh_wap_rank_rows = $dsql->all("select b.id,b.title,b.author,b.description,b.ico,b.update_time,count(distinct s.uid) as rank_metric
			from ay_member_stow s inner join ay_content b on b.id=s.id
			where b.status=1
			group by b.id order by rank_metric desc,b.id desc limit 50",1);
		$sh_wap_rank_note = '所选周期暂无新增收藏，已显示累计收藏榜';
	} else {
		$sh_wap_rank_note = $sh_wap_rank_days > 0 ? '按所选周期内新增收藏人数排序' : '按累计收藏人数排序';
	}
} else {
	$sh_wap_rank_order = array(
		'sale' => 'num_dy desc,visits desc,id desc',
		'vote' => 'likes desc,id desc',
		'new' => 'create_time desc,id desc',
		'update' => 'update_time desc,id desc'
	);
	$sh_wap_rank_field = $my === 'new' ? 'create_time' : 'update_time';
	$sh_wap_rank_filter_days = $sh_wap_rank_days;
	$sh_wap_rank_filter = $sh_wap_rank_filter_days > 0
		? " and $sh_wap_rank_field >= date_sub(now(), interval $sh_wap_rank_filter_days day)"
		: '';
	$sh_wap_rank_widened = false;
	if ($sh_wap_rank_filter_days > 0) {
		$sh_wap_rank_count = $dsql->one("select count(*) as c from ay_content where status=1 $sh_wap_rank_filter",1);
		if (intval($sh_wap_rank_count['c']) < 10) {
			$sh_wap_rank_fallback_days = array('day' => 7, 'week' => 30, 'month' => 90);
			$sh_wap_rank_filter_days = isset($sh_wap_rank_fallback_days[$page]) ? $sh_wap_rank_fallback_days[$page] : $sh_wap_rank_filter_days;
			$sh_wap_rank_filter = " and $sh_wap_rank_field >= date_sub(now(), interval $sh_wap_rank_filter_days day)";
			$sh_wap_rank_widened = true;
		}
	}
	$sh_wap_rank_rows = $dsql->all("select id,title,author,description,ico,update_time,create_time,num_dy,likes,subscode
		from ay_content where status=1 $sh_wap_rank_filter
		order by {$sh_wap_rank_order[$my]} limit 50",1);
	if ($my === 'sale' && $sh_wap_rank_days > 0) {
		$sh_wap_rank_note = $sh_wap_rank_widened ? '近期作品较少，已扩大时间范围，按累计订阅排序' : '周期内更新作品，按累计订阅排序';
	} else if ($my === 'vote' && $sh_wap_rank_days > 0) {
		$sh_wap_rank_note = $sh_wap_rank_widened ? '近期作品较少，已扩大时间范围，按累计推荐排序' : '周期内更新作品，按累计推荐排序';
	} else if ($my === 'new') {
		$sh_wap_rank_note = $sh_wap_rank_days > 0 ? ($sh_wap_rank_widened ? '近期新书较少，已扩大时间范围' : '按所选周期内上架时间排序') : '按上架时间排序';
	} else if ($my === 'update') {
		$sh_wap_rank_note = $sh_wap_rank_days > 0 ? ($sh_wap_rank_widened ? '近期更新较少，已扩大时间范围' : '按所选周期内更新时间排序') : '按更新时间排序';
	} else {
		$sh_wap_rank_note = '按累计订阅排序';
	}
}

if (!$sh_wap_rank_rows && !$sh_wap_rank_empty) {
	$sh_wap_rank_empty = '当前周期暂无作品';
}
$template = 'paihang.html';
require_once(dirname(__FILE__)."/../../template/".$template);
?>
