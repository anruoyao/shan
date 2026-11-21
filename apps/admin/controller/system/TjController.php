<?php
namespace app\admin\controller\system;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;
class TjController extends Controller
{
	public function index(){
		global $dsql;
		$x=$dsql->one("select count(*) from ay_member",1);
		$mem_total=$x['count(*)'];
		$t=time();
		$today=date('Y-m-d',$t);
		$yesterday=date("Y-m-d",strtotime("-1 day"));
		$x=$dsql->one("select count(*) from ay_member where last_login_time='$today'",1);
		$mem_online=$x['count(*)'];
		$x=$dsql->one("select count(*) from ay_member where last_login_time='$today' and register_time like '%$today%'",1);
		$mem_online_new=$x['count(*)'];
		$mem_online_old=$mem_online-$mem_online_new;
		$x=$dsql->one("select count(*) from ay_member where register_time like '%$today%'",1);
		$mem_today=$x['count(*)'];
		$x=$dsql->one("select count(*) from ay_member where register_time like '%$yesterday%'",1);
		$mem_yesterday=$x['count(*)'];
		$this->assign('mem_total',$mem_total);
		$this->assign('mem_online',$mem_online);
		$this->assign('mem_online_new',$mem_online_new);
		$this->assign('mem_online_old',$mem_online_old);
		$this->assign('mem_today',$mem_today);
		$this->assign('mem_yesterday',$mem_yesterday);
		$x=$dsql->one("select all_ip,today_ip,yesterday_ip,all_pv,today_pv,yesterday_pv from ay_tj where id=1 limit 1",1);
		$all_ip=$x[all_ip];
		$today_ip=$x[today_ip];
		$yesterday_ip=$x[yesterday_ip];
		$all_pv=$x[all_pv];
		$today_pv=$x[today_pv];
		$yesterday_pv=$x[yesterday_pv];
		$this->assign('all_ip',$all_ip);
		$this->assign('today_ip',$today_ip);
		$this->assign('yesterday_ip',$yesterday_ip);
		$this->assign('all_pv',$all_pv);
		$this->assign('today_pv',$today_pv);
		$this->assign('yesterday_pv',$yesterday_pv);

		$this->display('system/tj.html');
    }
}