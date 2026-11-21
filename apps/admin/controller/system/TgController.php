<?php
namespace app\admin\controller\system;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;
class TgController extends Controller
{
	public function index(){
		global $dsql;
		$OnePAGE_NUM=20;
		$page=gpvar('page');
		$x=$dsql->one("select count(*) from ay_tg",1);
		$total=$x["count(*)"];
		$pagenum=ceil($total/$OnePAGE_NUM);
		$page?'':$page='1';
		$page=min($pagenum,$page);!$page?$page=1:"";
		$prepg=$page-1;
		$nextpg=($page==$pagenum?0:$page+1);
		$offset=($page-1)*$OnePAGE_NUM;
		$dd=$dsql->all("select * from ay_tg order by id desc limit $offset,$OnePAGE_NUM",1);
		foreach($dd as $xx){
			$id=$xx['id'];
			$link=$xx['link'];
			$status=$xx['status'];
			$visits=$xx['visits'];
			$update_time=$xx['update_time'];
			if($status=='1'){
				$status='<a href="?p=/Tg/status/id/'.$id.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
    		}else{
				$status='<a href="?p=/Tg/status/id/'.$id.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
    		}
			$xxlist.='
			<tr>
				<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$id.'"></td>
				<td>'.$id.'</td>
				<td><a href="?p=/Tg/edit/id/'.$id.'">'.$link.'</a></td>
				<td>'.$status.'</td>
				<td>'.$visits.'</td>
				<td>'.$update_time.'</td>
				<td>
					<a href="'.$link.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">查看</a>
					<a href="?p=/Tg/del/id/'.$id.'" onclick="return delxx()" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
					<a href="?p=/Tg/edit/id/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
				</td>
			</tr>';
    	}
    	$pageurl='?p=/Tg/index&page=';
    	$pagebox=pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg);
		$this->assign('xxlist',$xxlist);
		$this->assign('pagebox',$pagebox);
		$this->display('system/tg.html');
    }
	public function jinbi(){
		global $dsql;
		$OnePAGE_NUM=20;
		$page=gpvar('page');
		$x=$dsql->one("select count(*) from ay_tg_jinbi",1);
		$total=$x["count(*)"];
		$pagenum=ceil($total/$OnePAGE_NUM);
		$page?'':$page='1';
		$page=min($pagenum,$page);!$page?$page=1:"";
		$prepg=$page-1;
		$nextpg=($page==$pagenum?0:$page+1);
		$offset=($page-1)*$OnePAGE_NUM;
		$dd=$dsql->all("select * from ay_tg_jinbi order by id desc limit $offset,$OnePAGE_NUM",1);
		foreach($dd as $xx){
			$tgid=$xx['tgid'];
			$uid=$xx['uid'];
			$jinbi=$xx['jinbi'];
			$uid_=$xx['uid_'];
			$je=$xx['je'];
			$update_time=$xx['update_time'];
			if(!$uid || !$tgid){
				continue;
			}
			$x=$dsql->one("select link from ay_tg where id=$tgid limit 1",1);
			$link=$x[link];
			if(!$link){
				$link_='推广链接已下线';
			}else{
				$link_='<a href="'.$link.'" title="'.$link.'" target="_blank">'.$link.'</a>';
			}
			$x=$dsql->one("select id,username,sign from ay_member where ucode=$uid limit 1",1);
			$id=$x[id];
			$username=$x[username];
			if(!$username){
				$username='<del style="color:#ccc">不存在</del>';
			}
			$sign=$x[sign];
			if($sign==3){
				$sign_='（已开放提现权限，<a href="?p=/Member/mod/id/'.$id.'/field/sign/value/0" style="color:red">关闭权限</a>）';
			}else{
				$sign_='（已关闭提现权限，<a href="?p=/Member/mod/id/'.$id.'/field/sign/value/3" style="color:green">开放权限</a>）';
			}
			$x=$dsql->one("select username from ay_member where ucode=$uid_ limit 1",1);
			$username_=$x[username];
			if(!$username_){
				$username_='<del style="color:#ccc">不存在</del>';
			}
			$xxlist.='
			<tr>
				<td>'.$link_.'</td>
				<td>'.$username.$sign_.'</td>
				<td style="color:red">+'.$jinbi.'</td>
				<td>'.$username_.'</td>
				<td>'.$je.'</td>
				<td>'.$update_time.'</td>
			</tr>';
    	}
    	$pageurl='?p=/Tg/jinbi&page=';
    	$pagebox=pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg);
		$this->assign('xxlist',$xxlist);
		$this->assign('pagebox',$pagebox);
		$this->display('system/tgjinbi.html');
    }
	public function status(){
		global $dsql;
		if(! $id=get('id','int')){
			error('传递的参数值错误！',-1);
    	}
		if(($field=get('field','var')) && ! is_null($value=get('value','var'))){
			$x=$dsql->amd("update ay_tg set $field=$value where id=$id limit 1");
			if($x){
				location(-1);
    		}else{
				error('编辑失败！',-1);
    		}
    	}else{
    		error('编辑的内容已经不存在！',-1);
    	}
    }
	public function add(){
		global $dsql;
		$link=gpvar('link');
		$status=gpvar('status',0,0);
		$visits=gpvar('visits');
		if(!$link){
			error('传递的参数值错误！',-1);
    	}
    	$update_time=get_datetime();
		$x1="link,status,visits,update_time";
		$x2="'$link','$status','$visits','$update_time'";
		$dsql->amd("insert into ay_tg ($x1) values ($x2)");
		success('新增推广成功！',-1);
    }
	public function del(){
		global $dsql;
		if(! $id=get('id','int')){
			error('传递的参数值错误！',-1);
    	}
		$x=$dsql->amd("delete from ay_tg where id=$id limit 1");
		if($x){
			location(-1);
    	}else{
    		error('删除失败！',-1);
    	}
     }
	public function edit(){
		global $dsql;
		if(! $id=get('id','int')){
			error('传递的参数值错误！',-1);
    	}
		if($_POST){
			$link=gpvar('link');
			$status=gpvar('status');
			$update_time=get_datetime();
			$x="link='$link',status='$status',update_time='$update_time'";
			$dsql->amd("update ay_tg set $x where id=$id limit 1");
			$url='?p=/Tg/index';
			success('编辑推广成功！',$url);
    	}
		$x=$dsql->one("select * from ay_tg where id=$id limit 1",0);
		$this->assign('dtarr',$x);
		$this->display('system/tgedit.html');
    }
	public function mod(){
		global $dsql;
        if(!!$submit=post('submit')){
        	switch ($submit) {
	            case 'status_1':
		        	$list=post('list');
	                if($list){
	                    foreach($list as $k => $v){
	                    	if(!$v || !is_numeric($v)){
	                    		continue;
	                    	}
	                    	$dsql->amd("update ay_tg set status=1 where id=$v limit 1");
	                    }
	                    success('设置成功！',-1);
	                }else{
	                    alert_back('请选择要设置的项目！');
	                }
	                break;
	            case 'status_0':
		        	$list=post('list');
	                if($list){
	                    foreach($list as $k => $v){
	                    	if(!$v || !is_numeric($v)){
	                    		continue;
	                    	}
	                    	$dsql->amd("update ay_tg set status=0 where id=$v limit 1");
	                    }
	                    success('设置成功！',-1);
	                }else{
	                    alert_back('请选择要设置的项目！');
	                }
	                break;
                case 'del':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$o=$dsql->amd("delete from ay_tg where id=$v limit 1");
                        }
                        if($o){
                        	success('删除成功！',-1);
                        }else{
                        	error('部分项目删除失败...',-1);
                        }
                    }else{
                        alert_back('请选择要删除的项目！');
                    }
                    break;
                case 'deljinbi':
	                $dsql->amd("truncate ay_tg_jinbi");
	                success('已清空所有佣金记录！',-1);
                    break;
            }
        }
    }
}
function pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg){
	!$pagenum?$prepg='':'';
	if($prepg){
		$x=str_replace('&page=','',$pageurl);
		if($prepg==1){
			$xurl=$x;
		}else{
			$xurl=$pageurl.$prepg;
		}
		$pre='<a href="'.$xurl.'">前一页</a>';
		$first='<a href="'.$x.'">首页</a>';
	}else{
		$pre='<a class="no">前一页</a>';
		$first='<a class="no">首页</a>';
	}
	if($nextpg){
		$next='<a href="'.$pageurl.$nextpg.'">后一页</a>';
		$last='<a href="'.$pageurl.$pagenum.'">尾页</a>';
	}else{
		$next='<a class="no">后一页</a>';
		$last='<a class="no">尾页</a>';
	}
	if($pagenum=='1'){
		$numnav='<a class="page-num page-num-current">1</a>';
	}else if($pagenum>1){
		if($page<3){
			for($i=1;$i<=(($pagenum>=3)?3:$pagenum);$i++){
				if($page==$i){
					$numnav.='<a class="page-num page-num-current">'.$i.'</a>';
				}else{
					$x=str_replace('&page=','',$pageurl);
					if($i!=1){
						$x=$pageurl.$i;
					}
					$numnav.='<a class="page-num" href="'.$x.'">'.$i.'</a>';
				}
			}
		}else{
			for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
				if($page==$i){
					$numnav.='<a class="page-num page-num-current">'.$i.'</a>';
				}else{
					$numnav.='<a class="page-num" href="'.$pageurl.$i.'">'.$i.'</a>';
				}
			}
		}
		$numnav.='<a class="page-num">...</a>';
		if($page!=$pagenum && $page!=($pagenum-1)){
			(($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
			$numnav.='<a class="page-num" href="'.$pageurl.$page_big.'">'.$page_big.'</a>';
		}
	}
	$xxx='
    <span class="page-status">共'.$total.'条 当前'.$page.'/'.$pagenum.'页</span>
    <span class="page-index">'.$first.'</span>
    <span class="page-pre">'.$pre.'</span>
    <span class="page-numbar">'.$numnav.'</span>
    <span class="page-next">'.$next.'</span>
    <span class="page-last">'.$last.'</span>';
	return $xxx;
}