<?php
namespace app\admin\controller\system;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;
class AdController extends Controller
{
	public function index(){
		global $dsql;
		$pw=get('pw');
		$dd=$dsql->all("select * from ay_ad order by id desc",1,usedbname());
		foreach($dd as $xx){
			$id=$xx['id'];
			$adtype=$xx['adtype'];
			if(!$adtype){
				$adtype=2;
			}
			$adname=$xx['adname'];
			$adstatus=$xx['adstatus'];
			if($pw){
				if(!is_numeric($pw)){
					$pw=1;
				}
				if($pw!=$adtype){
					continue;
				}
			}
			if($adstatus=='1'){
				$status='<a href="?p=/Ad/status/id/'.$id.'/field/adstatus/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
    		}else{
				$status='<a href="?p=/Ad/status/id/'.$id.'/field/adstatus/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
    		}
			if($adtype=='1'){
				$tp='电脑版';
    		}else{
				$tp='手机版';
    		}
			$adlist.='
			<tr>
				<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$id.'"></td>
				<td>'.$id.'</td>
				<td>'.$tp.'</td>
				<td>'.$adname.'</td>
				<td>'.$status.'</td>
				<td>
					<a href="'.SITE_DIR.'/axd/index.php?view='.$id.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">查看</a>
					<a href="?p=/Ad/del/id/'.$id.'" onclick="return delad()" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
					<a href="?p=/Ad/edit/id/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
				</td>
			</tr>';
    	}
		$this->assign('pw',$pw);
		$this->assign('adlist',$adlist);
		$this->display('system/ad.html');
    }
	public function status(){
		global $dsql;
		if(! $id=get('id','int')){
			error('传递的参数值错误！',-1);
    	}
		if(($field=get('field','var')) && ! is_null($value=get('value','var'))){
			$x=$dsql->amd("update ay_ad set $field=$value where id=$id limit 1",usedbname());
			if($x){
				$this->log('编辑广告位' . $id . '成功！');
				location(-1);
    		}else{
				$this->log('编辑广告位' . $id . '失败！');
				error('编辑失败！',-1);
    		}
    	}else{
    		error('编辑的内容已经不存在！',-1);
    	}
    }
	public function add(){
		global $dsql;
		$adname=gpvar('adname');
		$adbody=gpvar('adbody',0,0);
		$adtype=gpvar('adtype');
		$adstatus=gpvar('adstatus');
		if(!$adname){
			error('传递的参数值错误！',-1);
    	}
		$create_time=get_datetime();
		$x1="adname,adbody,adtype,adstatus,create_time";
		$x2="'$adname','$adbody','$adtype','$adstatus','$create_time'";
		$x=$dsql->amd("insert into ay_ad ($x1) values ($x2)",usedbname());
		if($x){
			$this->log('新增广告位成功！');
			success('新增广告位成功！',-1);
    	}else{
			$this->log('新增广告位失败！');
			error('新增广告位失败！',-1);
    	}
    }
	public function del(){
		global $dsql;
		if(! $id=get('id','int')){
			error('传递的参数值错误！',-1);
    	}
		$x=$dsql->amd("delete from ay_ad where id=$id limit 1",usedbname());
		if($x){
			$this->log('删除广告位' . $id . '成功！');
			location(-1);
    	}else{
    		$this->log('删除广告位' . $id . '失败！');
    		error('删除失败！',-1);
    	}
     }
	public function edit(){
		global $dsql;
		if(! $id=get('id','int')){
			error('传递的参数值错误！',-1);
    	}
		if($_POST){
			$adname=gpvar('adname');
			$adbody=gpvar('adbody',0,0);
			$adbody=str_replace("'","\'",$adbody);
			$adtype=gpvar('adtype');
			$adstatus=gpvar('adstatus');
			$create_time=get_datetime();
			$gobackurl=gpvar('gobackurl');
			$x="adname='$adname',adbody='$adbody',adtype='$adtype',adstatus='$adstatus',create_time='$create_time'";
			$x=$dsql->amd("update ay_ad set $x where id=$id limit 1",usedbname());
			if($x){
				$this->log('编辑广告位成功！');
				if($gobackurl){
					$url=$gobackurl;
				}else{
					$url='?p=/Ad/index';
				}
				success('编辑广告位成功！',$url);
    		}else{
				$this->log('编辑广告位失败！');
				error('编辑广告位失败！',-1);
    		}
    	}
		$x=$dsql->one("select * from ay_ad where id=$id limit 1",0,usedbname());
		$this->assign('dtarr',$x);
		$this->display('system/adedit.html');
    }
	public function mod(){
		global $dsql;
        if(!!$submit=post('submit')){
        	switch ($submit) {
	            case 'adstatus_1':
		        	$list=post('list');
	                if($list){
	                    foreach($list as $k => $v){
	                    	if(!$v || !is_numeric($v)){
	                    		continue;
	                    	}
	                    	$dsql->amd("update ay_ad set adstatus=1 where id=$v limit 1");
	                    }
	                    success('设置成功！',-1);
	                }else{
	                    alert_back('请选择要设置的项目！');
	                }
	                break;
	            case 'adstatus_0':
		        	$list=post('list');
	                if($list){
	                    foreach($list as $k => $v){
	                    	if(!$v || !is_numeric($v)){
	                    		continue;
	                    	}
	                    	$dsql->amd("update ay_ad set adstatus=0 where id=$v limit 1");
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
                        	$o=$dsql->amd("delete from ay_ad where id=$v limit 1");
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
                case 'adbody':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	if($v==103){
                        		continue;
                        	}else{
                        		$dsql->amd("update ay_ad set adbody='' where id=$v limit 1");
                        	}
                        }
                        success('清空成功！',-1);
                    }else{
                        alert_back('请选择要清空的项目！');
                    }
                    break;
            }
        }
    }
}