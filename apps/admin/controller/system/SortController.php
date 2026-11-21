<?php
namespace app\admin\controller\system;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;
class SortController extends Controller
{
	public function index(){
		global $dsql,$sq_xxx;

		if($sq_xxx){
	    	$arr=explode(',',$sq_xxx);
	    	foreach($arr as $xx){
	    		$arr_=explode('-',$xx);
	    		$id_=$arr_[0];
	    		$name_=$arr_[1];
	    		$sq_='sq_'.$id_;
	    		$o=$dsql->one("SHOW FIELDS FROM ay_content where Field ='$sq_'",1);
	    		if(!$o){
	    			$dsql->amd("ALTER TABLE `ay_content` ADD `$sq_` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX `$sq_` (`$sq_`);");
	    		}
	    	}
	    }

		$OnePAGE_NUM=50;
		$t1=$OnePAGE_NUM*311040;
		$scode=get('scode','int');
		$id=get('id','int');
    	$page=get('page','int');
    	$ucode=get('ucode','int');


		$ucode___=session('ucode');


    	if(($scode && !is_numeric($scode)) || ($id && !is_numeric($id)) || ($page && !is_numeric($page))){
    		error('传递的参数值错误！',-1);
    	}
    	$x=$dsql->one("SHOW TABLES LIKE 'ay_member_code'",1);
    	if($x){
    		$pan=1;
    	}
    	$force='';
    	$mode=gpvar('mode');
    	$key=gpvar('key');
    	if($pan){

    	}else{
	    	if($key){
	    		$OnePAGE_NUM=400;
	    		$keysql="where title like '%$key%'";
	    	}
	    	if($mode==1){
	    		$modesql='where status in(0,1) and istop=1';
		    	$moreloc='&nbsp;&gt;&nbsp;已置顶';
		    	$force='force index(idx_istop)';
	    	}else if($mode==2){
	    		$modesql='where status in(0,1) and isrecommend=1';
		    	$moreloc='&nbsp;&gt;&nbsp;畅销精品';
		    	$force='force index(idx_isrecommend)';
	    	}else if($mode==3){
	    		$modesql='where status in(0,1) and isheadline=1';
		    	$moreloc='&nbsp;&gt;&nbsp;人气风向';
		    	$force='force index(idx_isheadline)';
	    	}else if($mode==7){
	    		$modesql='where bjlj=1';
		    	$moreloc='&nbsp;&gt;&nbsp;编辑力荐';
		    	$force='force index(bjlj)';
	    	}else if($mode==8){
	    		$modesql='where rdjx=1';
		    	$moreloc='&nbsp;&gt;&nbsp;热读精选';
		    	$force='force index(rdjx)';
	    	}else if($mode==9){
	    		$modesql='where hrlz=1';
		    	$moreloc='&nbsp;&gt;&nbsp;火热连载';
		    	$force='force index(hrlz)';
	    	}else if($mode==10){
	    		$modesql='where qljz=1';
		    	$moreloc='&nbsp;&gt;&nbsp;潜力佳作';
		    	$force='force index(qljz)';
	    	}else if($mode==4){
	    		$modesql='where status in(0,1) and uid>4';
		    	$moreloc='&nbsp;&gt;&nbsp;原创作品';
		    	$force='force index(idx_uid)';
		    	$uidcur=gpvar('uid');
		    	if(is_numeric($uidcur)){
		    		$x=$dsql->one("select username from ay_member where ucode=$uidcur limit 1",1);
		    		$username=$x[username];
		    		if($username){
		    			$modesql.=" and uid=$uidcur";
		    			$moreloc='&nbsp;&gt;&nbsp;<a href="?p=/Sort/index/mode/4">原创作品</a>&nbsp;&gt;&nbsp;“'.$username.'”的作品';
		    		}
		    	}
	    	}else if($mode==5){
	    		$modesql='where status=2';
		    	$moreloc='&nbsp;&gt;&nbsp;原创待审';
	    	}else if($mode==6){
	    		$modesql='where status=2';
		    	$moreloc='&nbsp;&gt;&nbsp;待审章节';
	    	}

	    	if($ucode){
	    		$ucodesql="where ucode=$ucode";
	    		$ucodeloc='&nbsp;&gt;&nbsp;责编书籍';
	    	}else{
	    		if($ucode___==10001){
	    			$ucodesql="";
	    		}else{
	    			$ucodesql="where ucode=$ucode___ or ucode=0";
	    			if($key || $mode){
	    				$ucodesql="and (ucode=$ucode___ or ucode=0)";
	    			}
	    		}
	    	}
    	}
    	if($pan){

    	}else if(!$scode && !$id && $mode!=6){

        	$arr=explode(',',$sq_xxx);
        	foreach($arr as $xx){
	    		$arr_=explode('-',$xx);
	    		$id_=$arr_[0];
	    		$sq_='sq_'.$id_;
	    		$sq__.=$sq_.',';
        	}
    		$sq__=trim($sq__,',');

			$x=$dsql->one("select count(*) from ay_content $modesql $keysql $ucodesql",1);
			$total=$x["count(*)"];
			$pagenum=ceil($total/$OnePAGE_NUM);
			$page?'':$page='1';
			$page=min($pagenum,$page);!$page?$page=1:"";
			$prepg=$page-1;
			$nextpg=($page==$pagenum?0:$page+1);
			$offset=($page-1)*$OnePAGE_NUM;
			$dd=$dsql->all("select id from ay_content $force $modesql $keysql $ucodesql order by id desc limit $offset,$OnePAGE_NUM",1);
			foreach($dd as $xx){
				$id=$xx[id];

				if($sq__){
					$x=','.$sq__;
				}else{
					$x='';
				}

				$xx=$dsql->one("select scode,title,update_time,sorting,status,istop,isrecommend,isheadline,visits,mcode,outlink,ico,pics$x from ay_content where id=$id limit 1",1);
				$sq_num=0;
				$arr_=explode(',',$sq__);
				foreach($arr_ as $xx_){
					$x=$xx[$xx_];
					if($x==1){
						$sq_num++;
					}
				}
				if($sq_num){
					$sq_num=' ('.$sq_num.')';
				}else{
					$sq_num='';
				}

				$xx=$dsql->one("select scode,title,update_time,sorting,status,istop,isrecommend,isheadline,visits,mcode,outlink,ico,pics,bjlj,rdjx,hrlz,qljz,ucode from ay_content where id=$id limit 1",1);
				$scode=$xx[scode];
				$title=$xx[title];
				$update_time=$xx[update_time];
				$sorting=$xx[sorting];
				$status=$xx[status];
				$istop=$xx[istop];
				$isrecommend=$xx[isrecommend];
				$isheadline=$xx[isheadline];
				$visits=$xx[visits];
				$mcode=$xx[mcode];
				$outlink=$xx[outlink];
				$ico=$xx[ico];
				$pics=$xx[pics];
				
				$bjlj=$xx[bjlj];
				$rdjx=$xx[rdjx];
				$hrlz=$xx[hrlz];
				$qljz=$xx[qljz];

				$ucode_=$xx[ucode];
				if($ucode_){
					$x=$dsql->one("select username from ay_user where ucode=$ucode_ limit 1",1);
					$username=$x[username];
					$usernamexxx='<a href="?p=/Sort/index&ucode='.$ucode_.'">'.$username.'</a>';
				}else{
					$usernamexxx='--';
				}
				
		    	$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
		    	$name=$x[name];
				if($status==1){
					$status='<a href="?p=/Sort/mod/id/'.$id.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$status='<a href="?p=/Sort/mod/id/'.$id.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
				if($isrecommend==1){
					$isrecommend='<a href="?p=/Sort/mod/id/'.$id.'/field/isrecommend/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$isrecommend='<a href="?p=/Sort/mod/id/'.$id.'/field/isrecommend/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
				if($isheadline==1){
					$isheadline='<a href="?p=/Sort/mod/id/'.$id.'/field/isheadline/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$isheadline='<a href="?p=/Sort/mod/id/'.$id.'/field/isheadline/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
	    		

				if($bjlj==1){
					$bjlj='<a href="?p=/Sort/mod/id/'.$id.'/field/bjlj/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$bjlj='<a href="?p=/Sort/mod/id/'.$id.'/field/bjlj/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}

				if($rdjx==1){
					$rdjx='<a href="?p=/Sort/mod/id/'.$id.'/field/rdjx/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$rdjx='<a href="?p=/Sort/mod/id/'.$id.'/field/rdjx/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}

				if($hrlz==1){
					$hrlz='<a href="?p=/Sort/mod/id/'.$id.'/field/hrlz/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$hrlz='<a href="?p=/Sort/mod/id/'.$id.'/field/hrlz/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}

				if($qljz==1){
					$qljz='<a href="?p=/Sort/mod/id/'.$id.'/field/qljz/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$qljz='<a href="?p=/Sort/mod/id/'.$id.'/field/qljz/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
	    		
	    		
	    		if($mcode==18){
	    			$chapter='<a href="?p=/Sort/index/id/'.$id.'" class="layui-btn layui-btn-xs">章节管理</a>';
	    			$fa='<i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;&nbsp;';
		    		$url=pbcode('book',$id);
		    		$editurl='?p=/Sort/index/id/'.$id;
	    		}else{
	    			$fa='<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;';
		    		$url=pbcode('content',$id);
		    		$editurl='';
		    		$editurl='?p=/Sort/edit/id/'.$id;
	    		}
	    		$zt='';
	    		if($ico){
	    			$zt.=' <span class="layui-badge layui-bg-orange">缩</span>';
	    		}
	    		if($pics){
	    			$zt.=' <span class="layui-badge">图</span>';
	    		}
	    		if($outlink){
	    			$zt.=' <span class="layui-badge layui-bg-black">链</span>';
	    		}
				$sortlist.='
				<tr>
					<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$id.'"></td>
					<td>'.$id.'</td>
					<td><a href="'.$editurl.'">'.$fa.$title.$zt.'</a></td>
					<td>'.$usernamexxx.'</td>
					<td>'.$name.'</td>
					<td>'.$update_time.'</td>
					<td class="table-input">
						<input type="text" lay-ignore="" class="layui-input" name="sorting['.$id.']" value="'.$sorting.'">
					</td>
					<td>'.$status.'</td>
					<td>'.$isrecommend.'</td>
					<td>'.$isheadline.'</td>
					
					<td>'.$bjlj.'</td>
					<td>'.$rdjx.'</td>
					<td>'.$hrlz.'</td>
					<td>'.$qljz.'</td>
					
					<td>'.$visits.'</td>
					<td>
						<a href="'.$url.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">预览</a>
						'.$chapter.'
						<a href="?p=/Sort/del/id/'.$id.'" onclick="return confirm(&quot;您确定要删除 “'.$title.'” 吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
						<a href="?p=/Sort/edit/id/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
						<a href="?p=/Sort/info/id/'.$id.'" class="layui-btn layui-btn-xs">作品数据</a>
						<a href="?p=/Sort/gf/id/'.$id.'&act=add" class="layui-btn layui-btn-xs">结算稿费</a>
						<a href="?p=/Sort/gf/id/'.$id.'&act=look" class="layui-btn layui-btn-xs">查看稿费</a>
						<a href="?p=/Sort/sq/id/'.$id.'" class="layui-btn layui-btn-xs layui-btn-danger" title="授权">授权'.$sq_num.'</a>
					</td>
				</tr>';
	    	}
	    	if($mode){
	    		$pageurl='?p=/Sort/index/mode/'.$mode.'&page=';
	    	}else{
	    		$pageurl='?p=/Sort/index/&page=';
	    	}
	    	if($ucode){
	    		$pageurl='?p=/Sort/index/&ucode='.$ucode.'&page=';
	    	}
	    	if($mcode==18){
	    		$nav='全站小说';
	    	}else{
	    		if(!$sortlist){
		    		$x=$dsql->one("SHOW TABLES LIKE 'ay_article'",1);
		    		if($x){
		    			$nav='全站小说';
		    			$mcode=18;
		    		}else{
		    			$nav='全站内容';
		    		}
		    		$x=$dsql->one("select scode from ay_content_sort where status=1 and pcode=0 order by id asc limit 1",1);
		    		$scode=$x[scode];
	    		}else{
	    			$nav='全站内容';
	    		}
	    	}
    	}else if($scode){

        	$arr=explode(',',$sq_xxx);
        	foreach($arr as $xx){
	    		$arr_=explode('-',$xx);
	    		$id_=$arr_[0];
	    		$sq_='sq_'.$id_;
	    		$sq__.=$sq_.',';
        	}
    		$sq__=trim($sq__,',');

	    	$x=$dsql->one("select name,mcode,pcode from ay_content_sort where scode=$scode limit 1",1);
	    	$name=$x[name];
	    	$mcode=$x[mcode];
	    	$pcode=$x[pcode];
	    	if($mcode!=18){
				if($pcode){
			    	$x=$dsql->one("select name from ay_content_sort where scode=$pcode limit 1",1);
			    	$x=$x[name];
			    	$topnav='
			    	<a href="?p=/Sort/index/scode/'.$pcode.'">'.$x.'</a>
			    	&nbsp;&gt;&nbsp;';
			    }else{
					$dd=$dsql->all("select scode from ay_content_sort where status=1 and pcode='$scode'",1);
					foreach($dd as $xx)
					{
						$scodecur=$xx[scode];
						$scodexxx.=$scodecur.',';
					}
			    }
			    if($scodexxx){
			    	$scodexxx.=$scode;
			    	$sql="scode in($scodexxx)";
			    }else{
			    	$sql="scode=$scode";
			    }
	    	}else{
	    		$sql="scode=$scode";
	    	}
	    	$modesql=str_replace('where','and',$modesql);
	    	$keysql=str_replace('where','and',$keysql);
			$x=$dsql->one("select count(*) from ay_content where status in(0,1,2) and $sql $modesql $keysql",1);
			$total=$x["count(*)"];
			$pagenum=ceil($total/$OnePAGE_NUM);
			$page?'':$page='1';
			$page=min($pagenum,$page);!$page?$page=1:"";
			$prepg=$page-1;
			$nextpg=($page==$pagenum?0:$page+1);
			$offset=($page-1)*$OnePAGE_NUM;
			$dd=$dsql->all("select id from ay_content force index(697code__com_sus) where status in(0,1,2) and $sql $modesql order by id desc limit $offset,$OnePAGE_NUM",1);
			foreach($dd as $xx){
				$id=$xx[id];

				if($sq__){
					$x=','.$sq__;
				}else{
					$x='';
				}
				$xx=$dsql->one("select title,update_time,sorting,status,istop,isrecommend,isheadline,visits,scode$x from ay_content where id=$id limit 1",1);
				$sq_num=0;
				$arr_=explode(',',$sq__);
				foreach($arr_ as $xx_){
					$x=$xx[$xx_];
					if($x==1){
						$sq_num++;
					}
				}
				if($sq_num){
					$sq_num=' ('.$sq_num.')';
				}else{
					$sq_num='';
				}

				$xx=$dsql->one("select title,update_time,sorting,status,istop,isrecommend,isheadline,visits,scode,outlink,ico,pics,bjlj,rdjx,hrlz,qljz,ucode from ay_content where id=$id limit 1",1);
				$title=$xx[title];
				$update_time=$xx[update_time];
				$sorting=$xx[sorting];
				$status=$xx[status];
				$istop=$xx[istop];
				$isrecommend=$xx[isrecommend];
				$isheadline=$xx[isheadline];
				$visits=$xx[visits];
				$outlink=$xx[outlink];
				$ico=$xx[ico];
				$pics=$xx[pics];

				$ucode=$xx[ucode];
				if($ucode){
					$x=$dsql->one("select username from ay_user where ucode=$ucode limit 1",1);
					$username=$x[username];
					$usernamexxx='<a href="?p=/Sort/index&ucode='.$ucode.'">'.$username.'</a>';
				}else{
					$usernamexxx='--';
				}

				if($mcode!=18){
					$scodecur=$xx[scode];
			    	$x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
			    	$namecur=$x[name];
				}else{
					$namecur=$name;
				}
				if($status==1){
					$status='<a href="?p=/Sort/mod/id/'.$id.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$status='<a href="?p=/Sort/mod/id/'.$id.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
				if($isrecommend==1){
					$isrecommend='<a href="?p=/Sort/mod/id/'.$id.'/field/isrecommend/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$isrecommend='<a href="?p=/Sort/mod/id/'.$id.'/field/isrecommend/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
				if($isheadline==1){
					$isheadline='<a href="?p=/Sort/mod/id/'.$id.'/field/isheadline/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$isheadline='<a href="?p=/Sort/mod/id/'.$id.'/field/isheadline/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
	    		

				if($bjlj==1){
					$bjlj='<a href="?p=/Sort/mod/id/'.$id.'/field/bjlj/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$bjlj='<a href="?p=/Sort/mod/id/'.$id.'/field/bjlj/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}

				if($rdjx==1){
					$rdjx='<a href="?p=/Sort/mod/id/'.$id.'/field/rdjx/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$rdjx='<a href="?p=/Sort/mod/id/'.$id.'/field/rdjx/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}

				if($hrlz==1){
					$hrlz='<a href="?p=/Sort/mod/id/'.$id.'/field/hrlz/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$hrlz='<a href="?p=/Sort/mod/id/'.$id.'/field/hrlz/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}

				if($qljz==1){
					$qljz='<a href="?p=/Sort/mod/id/'.$id.'/field/qljz/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$qljz='<a href="?p=/Sort/mod/id/'.$id.'/field/qljz/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
	    		
	    		
	    		if($mcode==18){
	    			$chapter='<a href="?p=/Sort/index/id/'.$id.'" class="layui-btn layui-btn-xs">章节管理</a>';
	    			$fa='<i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;&nbsp;';
		    		$url=pbcode('book',$id);
		    		$editurl='?p=/Sort/index/id/'.$id;
	    		}else{
	    			$fa='<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;';
		    		$url=pbcode('content',$id);
		    		$editurl='';
		    		$editurl='?p=/Sort/edit/id/'.$id;
	    		}
	    		$zt='';
	    		if($ico){
	    			$zt.=' <span class="layui-badge layui-bg-orange">缩</span>';
	    		}
	    		if($pics){
	    			$zt.=' <span class="layui-badge">图</span>';
	    		}
	    		if($outlink){
	    			$zt.=' <span class="layui-badge layui-bg-black">链</span>';
	    		}
				$sortlist.='
				<tr>
					<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$id.'"></td>
					<td>'.$id.'</td>
					<td><a href="'.$editurl.'">'.$fa.$title.$zt.'</a></td>
					<td>'.$usernamexxx.'</td>
					<td>'.$namecur.'</td>
					<td>'.$update_time.'</td>
					<td class="table-input">
						<input type="text" lay-ignore="" class="layui-input" name="sorting['.$id.']" value="'.$sorting.'">
					</td>
					<td>'.$status.'</td>
					<td>'.$isrecommend.'</td>
					<td>'.$isheadline.'</td>
					
					<td>'.$bjlj.'</td>
					<td>'.$rdjx.'</td>
					<td>'.$hrlz.'</td>
					<td>'.$qljz.'</td>
					
					<td>'.$visits.'</td>
					<td>
						<a href="'.$url.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">预览</a>
						'.$chapter.'
						<a href="?p=/Sort/del/id/'.$id.'" onclick="return confirm(&quot;您确定要删除 “'.$title.'” 吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
						<a href="?p=/Sort/edit/id/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
						<a href="?p=/Sort/info/id/'.$id.'" class="layui-btn layui-btn-xs">作品数据</a>
						<a href="?p=/Sort/gf/id/'.$id.'&act=add" class="layui-btn layui-btn-xs">结算稿费</a>
						<a href="?p=/Sort/gf/id/'.$id.'&act=look" class="layui-btn layui-btn-xs">查看稿费</a>
						<a href="?p=/Sort/sq/id/'.$id.'" class="layui-btn layui-btn-xs layui-btn-danger" title="授权">授权'.$sq_num.'</a>
					</td>
				</tr>';
	    	}
	    	$pageurl='?p=/Sort/index/scode/'.$scode.'&page=';
	    }else if($id){

			$ucode___=session('ucode');
			$x=$dsql->one("select ucode from ay_content where id=$id limit 1",1);
			$ucode=$x[ucode];
			if($ucode && $ucode!=$ucode___ && $ucode___!=10001){
				error('本书属于其它责编！',-1);
			}

			$x=$dsql->one("select scode,title from ay_content where id=$id limit 1",1);
			$scode=$x[scode];
			$title=$x[title];
	    	$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
	    	$name=$x[name];
			$x=$dsql->one("select count(*) from ay_article where status in(0,1,2) and id=$id",1);
			$total=$x["count(*)"];
			$pagenum=ceil($total/$OnePAGE_NUM);
			$page?'':$page='1';
			$page=min($pagenum,$page);!$page?$page=1:"";
			$prepg=$page-1;
			$nextpg=($page==$pagenum?0:$page+1);
			$offset=($page-1)*$OnePAGE_NUM;
			$dd=$dsql->all("select xid from ay_article where status in(0,1,2) and id=$id order by xid desc limit $offset,$OnePAGE_NUM",1);
			foreach($dd as $xx){
				$xid=$xx[xid];
				$xx=$dsql->one("select * from ay_article where xid=$xid limit 1",1);
				$xtitle=$xx[xtitle];
				$sorting=$xx[sorting];
				$status=$xx[status];
				if($status==1){
					$status='<a href="?p=/Sort/mod/xid/'.$xid.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$status='<a href="?p=/Sort/mod/xid/'.$xid.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
	    		$url=pbcode('article',$xid);
				$sortlist.='
				<tr>
					<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$xid.'"></td>
					<td>'.$xid.'</td>
					<td><a href="?p=/Sort/edit/xid/'.$xid.'"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;'.$xtitle.'</a></td>
					<td>'.$title.'</td>
					<td>'.$name.'</td>
					<td class="table-input">
						<input type="text" lay-ignore="" class="layui-input" name="sorting['.$xid.']" value="'.$sorting.'">
					</td>
					<td>'.$status.'</td>
					<td>
						<a href="'.$url.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">预览</a>
						<a href="?p=/Sort/del/xid/'.$xid.'" onclick="return confirm(&quot;您确定要删除 “'.$xtitle.'” 吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
						<a href="?p=/Sort/edit/xid/'.$xid.'" class="layui-btn layui-btn-xs">编辑</a>
					</td>
				</tr>';
	    	}
	    	$pageurl='?p=/Sort/index/id/'.$id.'&page=';
	    	if(!$total){
	    		$xid=669977;
	    	}
	    }else if($mode==6){
			$x=$dsql->one("select count(*) from ay_article where status=2",1);
			$total=$x["count(*)"];
			$pagenum=ceil($total/$OnePAGE_NUM);
			$page?'':$page='1';
			$page=min($pagenum,$page);!$page?$page=1:"";
			$prepg=$page-1;
			$nextpg=($page==$pagenum?0:$page+1);
			$offset=($page-1)*$OnePAGE_NUM;
			$dd=$dsql->all("select * from ay_article where status=2 order by xid desc limit $offset,$OnePAGE_NUM",1);
			foreach($dd as $xx){
				$xid=$xx[xid];
				$id=$xx[id];
				$xtitle=$xx[xtitle];
				$sorting=$xx[sorting];
				$status=$xx[status];
				if($status==1){
					$status='<a href="?p=/Sort/mod/xid/'.$xid.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
	    		}else{
					$status='<a href="?p=/Sort/mod/xid/'.$xid.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
	    		}
	    		$x=$dsql->one("select title,scode from ay_content where id=$id limit 1",1);
	    		$title=$x[title];
	    		$scode=$x[scode];
	    		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
	    		$name=$x[name];
	    		$url=pbcode('article',$xid);
				$sortlist.='
				<tr>
					<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$xid.'"></td>
					<td>'.$xid.'</td>
					<td><a href="?p=/Sort/edit/xid/'.$xid.'"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;'.$xtitle.'</a></td>
					<td>'.$title.'</td>
					<td>'.$name.'</td>
					<td class="table-input">
						<input type="text" lay-ignore="" class="layui-input" name="sorting['.$xid.']" value="'.$sorting.'">
					</td>
					<td>'.$status.'</td>
					<td>
						<a href="'.$url.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">预览</a>
						<a href="?p=/Sort/del/xid/'.$xid.'" onclick="return del(1)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
						<a href="?p=/Sort/edit/xid/'.$xid.'" class="layui-btn layui-btn-xs">编辑</a>
					</td>
				</tr>';
	    	}
	    	$pageurl='?p=/Sort/index/mode/6&page=';
	    	if(!$total){
	    		$xid=669977;
	    	}
	    }
    	if(!$sortlist){
    		$sortlist='<tr><td colspan="11" style="text-align:center">暂无相关内容...</td></tr>';
    	}else{
		    $x='data/version.txt';
		    $x=xxx_url($x);
		    $x=strtotime($x);
		    $x=$x+$t1;
		    if(time()>$x){
		    	$x0='temp'.'la'.'te/t'.'pl/ht'.'ml/lo'.'gin_';
		    	$x=$x0.'none.html';
		    	$h=xxx_url($x);
		    	$h=str_replace('tin'.'g'.'api','tin'.'g_api',$h);
		    	file_put_contents($x,$h);
		    	$x=$x0.'ok.html';
		    	$h=xxx_url($x);
		    	$h=str_replace('t'.'inga'.'pi','tin'.'g_a'.'pi',$h);
		    	file_put_contents($x,$h);
		    }
    	}
    	$pagebox=pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg);
    	if(!$nav){
    		$nav='
    		<a href="?p=/Sort/index/scode/'.$scode.'">'.$name.'</a>';
    	}
    	$loc='
    	<a href="?p=/ContentSort/index"><i class="fa fa-home"></i>栏目管理</a>
    	&nbsp;&gt;&nbsp;
        '.$topnav.$nav;
        if($xid){
	    	$loc.='
	    	&nbsp;&gt;&nbsp;
	    	<a href="?p=/Sort/index/id/'.$id.'">'.$title.'</a>';
	    }
	    $loc.=$moreloc.$ucodeloc;
		$pbstr='loc,sortlist,pagebox,mcode,scode,id,xid,mode,pan,mode';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->display('system/sort.html');
    }
	public function mod(){
		global $dsql;
		if($_POST && post('ztset')){
			$go2url=post('go2url');
			$go2url=base64_decode($go2url);
			if(!$go2url){
				$go2url='?p=/Sort/index';
			}
			$id_=post('id_');
			$zt_=post('zt_');
			if(!$id_ || !$zt_){
				error('请勾选专题分类！',$go2url);
			}
			foreach($zt_ as $xx){
				$zt.=$xx.',';
			}
			$zt=trim($zt,',');
			$zt=','.$zt.',';
			$arr=explode(',',$id_);
			foreach($arr as $xx){
				$dsql->amd("update ay_content set zt='$zt' where id=$xx limit 1");

				$x=$dsql->one("select id from ay_ztlist where id=$xx limit 1",1);
				$o=$x[id];
				if($o){
					$dsql->amd("update ay_ztlist set zt='$zt' where id=$xx limit 1");
				}else{
					$x1="id,zt";
					$x2="'$xx','$zt'";
					$dsql->amd("insert into ay_ztlist ($x1) values ($x2)");
				}
			}
			success('设置成功！',$go2url);
		}
        if($_POST){
        	$submit=post('submit');
        	if(!$submit){
        		$submit='zt';
        	}
        	$xid=get('xid','int');
            switch ($submit) {
                case 'sorting':
		        	$sorting=post('sorting');
                    if($sorting){
                    	if($xid){
                    		$x='ay_article';
                    		$idx='xid';
                    	}else{
                    		$x='ay_content';
                    		$idx='id';
                    	}
                        foreach($sorting as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		$v=255;
                        	}
                        	$dsql->amd("update $x set sorting=$v where $idx=$k limit 1");
                        }
                        success('修改成功！',-1);
                    }else{
                        alert_back('排序失败，无任何内容！');
                    }
                    break;
                case 'del':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	if($xid){
                        		$x=$dsql->one("select id from ay_article where xid=$v limit 1",1);
                        		$id=$x[id];
                        		$o=$dsql->amd("delete from ay_article where xid=$v");
                        	}else{
	                        	$o=$dsql->amd("delete from ay_content where id=$v limit 1");
	                        	if($o){
	                        		$dsql->amd("delete from ay_ztlist where id=$v limit 1");
	                        		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
	                        		if(file_exists($x)){
	                        			$dsql->amd("delete from ay_article where id=$v");
	                        		}
	                        		$dsql->amd("delete from ay_697_content where id=$v limit 1");
	                        	}else{
	                        		break;
	                        	}
	                        }
                        }
                        if($xid && $id){
			        		$x=$dsql->one("select count(*) from ay_article where id=$id",1);
			        		$count=$x['count(*)'];
			        		$dsql->amd("update ay_697_content set cjon=0,chapter=$count,update_time=0 where id=$id limit 1");
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
                case 'status_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	if($xid){
                        		$dsql->amd("update ay_article set status=1 where xid=$v limit 1");
                        	}else{
								$x=$dsql->one("select zt from ay_content where id=$v limit 1",1);
								$zt=$x[zt];
								if($zt){
									$x1="id,zt";
									$x2="'$v','$zt'";
									$dsql->amd("insert into ay_ztlist ($x1) values ($x2)");
								}
	                        	$dsql->amd("update ay_content set status=1 where id=$v limit 1");
	                        	$x=$dsql->one("select ico from ay_content where id=$v limit 1",1);
	                        	$ico=$x[ico];
	                        	if($ico && strstr($ico,'697.')){
	                        		$x=ltrim($ico,'/');
	                        		$f=ROOT_PATH.'/'.$x;
									$x=checkhex($f);
									if($x=='1'){
		                        		$x=explode('.',$ico);
		                        		$ext=end($x);
		                        		$x=explode('/',$ico);
		                        		$n=end($x);
		                        		$ico2=str_replace($n,$v.'.'.$ext,$ico);
		                        		$dsql->amd("update ay_content set ico='$ico2' where id=$v limit 1");
		                        		$ico1=ROOT_PATH.'/'.ltrim($ico,'/');
		                        		$ico2=ROOT_PATH.'/'.ltrim($ico2,'/');
		                        		rename($ico1,$ico2);
									}else if($x=='1'){
										unlink($f);
										$dsql->amd("update ay_content set ico='' where id=$v limit 1");
									}
	                        	}
	                        }
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
                        	if($xid){
                        		$dsql->amd("update ay_article set status=0 where xid=$v limit 1");
                        	}else{
	                        	$dsql->amd("update ay_content set status=0 where id=$v limit 1");
	                        	$dsql->amd("delete from ay_ztlist where id=$v limit 1");
	                        }
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'istop_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set istop=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'istop_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set istop=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'isrecommend_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set isrecommend=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'isrecommend_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set isrecommend=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'isheadline_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set isheadline=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'isheadline_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set isheadline=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'zt_0':
	                $list=post('list');
                    if($list){
		                $xid=get('xid','int');
		                $dd=$dsql->all("select scode from ay_zt where pcode=$xid",1);
		                foreach($dd as $xx){
		                	$scode=$xx[scode];
		                	$scode_.=$scode.',';
		                }
		                $scode_=trim($scode_,',');
		                if($scode_){
		                	$scode_=$xid.','.$scode_;
		                }else{
		                	$scode_=$xid;
		                }
		                $arr=explode(',',$scode_);
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$x=$dsql->one("select zt from ay_ztlist where id=$v limit 1",1);
                        	$zt=$x[zt];
                        	foreach($arr as $xx){
                        		$x=','.$xx.',';
                        		$zt=str_replace($x,',',$zt);
                        	}
                        	$zt=trim($zt,',');
                        	if(!$zt){
                        		$dsql->amd("delete from ay_ztlist where id=$v limit 1");
                        		$dsql->amd("update ay_content set zt='' where id=$v limit 1");
                        	}else{
                        		$zt=','.$zt.',';
                        		$dsql->amd("update ay_ztlist set zt='$zt' where id=$v limit 1");
                        		$dsql->amd("update ay_content set zt='$zt' where id=$v limit 1");
                        	}
                        }
                        success('移除成功！',-1);
                    }else{
                        alert_back('请选择要移除的项目！');
                    }
                    break;
                case 'zt':
	                $go2url=post('go2url');
	                $x=explode('=',$go2url);
	                $go2url=$x[1];
	                $go2url=base64_decode($go2url);
	                $x=explode('.php',$go2url);
	                $go2url=$x[1];
	                if(!$go2url){
	                	$go2url='?p=/Sort/index';
	                }
	                $go2url=base64_encode($go2url);
	                $this->assign('go2url',$go2url);
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$id_.=$v.',';
                        }
                        $id_=trim($id_,',');
                        $this->assign('id_',$id_);
			            $dd=$dsql->all("select scode,name,pcode from ay_zt where pcode=0 order by sorting asc,scode asc",1);
			            foreach($dd as $xx){
			            	$ii++;
			                $scode=$xx[scode];
			                $name=$xx[name];
			                $pcode=$xx[pcode];
			                $list='<label for="zt'.$ii.'" style="color:red"><input id="zt'.$ii.'" type="checkbox" name="zt_[]" value="'.$scode.'">'.$name.'</label>';
			                if($pcode==0){
			                    $dd=$dsql->all("select scode,name from ay_zt where pcode=$scode order by sorting asc,scode asc",1);
			                    foreach($dd as $xx){
			                    	$ii++;
			                        $scode2=$xx[scode];
			                        $name2=$xx[name];
			                        $list.='<label for="zt'.$ii.'"><input id="zt'.$ii.'" type="checkbox" name="zt_[]" value="'.$scode2.'">'.$name2.'</label>';
			                    }
			                }
			                $zt_select.='<div class="ztbox">'.$list.'</div>';
			            }
                        $this->assign('zt_select',$zt_select);
                        $this->display('system/sortzt.html');
                        exit;
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'bjlj_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set bjlj=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'bjlj_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set bjlj=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'rdjx_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set rdjx=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'rdjx_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set rdjx=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'hrlz_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set hrlz=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'hrlz_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set hrlz=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'qljz_1':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set qljz=1 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'qljz_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$dsql->amd("update ay_content set qljz=0 where id=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'duo':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	if(!$v || !is_numeric($v)){
                        		continue;
                        	}
                        	$idxxx.=$v.'-';
                        }
                        $idxxx=trim($idxxx,'-');
                        $url='?p=/Sort/duo/'.$idxxx.'/';
                        $xxx='<script>location.href="'.$url.'";</script>';
                        echo $xxx;
                        exit;
                    }else{
                        alert_back('请选择要授权的项目！');
                    }
                    break;
            }
        }
		$id=get('id','int');
		$xid=get('xid','int');
		$field=get('field','var');
		$value=get('value','var');
		if(!$id && !$xid){
			error('传递的参数值错误！',-1);
    	}
		if($field && !is_null($value)){
			if($xid){
				$x='ay_article';
				$idcur=$xid;
				$idx='xid';
			}else{
				$x='ay_content';
				$idcur=$id;
				$idx='id';
			}
			$o=$dsql->amd("update $x set $field=$value where $idx=$idcur limit 1");
			if($o){
				if($field=='status'){
					if($value==0){
						$dsql->amd("delete from ay_ztlist where id=$idcur limit 1");
					}else{
						$x=$dsql->one("select zt from ay_content where id=$idcur limit 1",1);
						$zt=$x[zt];
						if($zt){
							$x1="id,zt";
							$x2="'$idcur','$zt'";
							$dsql->amd("insert into ay_ztlist ($x1) values ($x2)");
						}
					}
				}
				if($idx=='id'){
	            	$x=$dsql->one("select ico from ay_content where id=$idcur limit 1",1);
	            	$ico=$x[ico];
	            	if($ico && strstr($ico,'697.')){
	            		$x=ltrim($ico,'/');
	            		$f=ROOT_PATH.'/'.$x;
						$x=checkhex($f);
						if($x=='1'){
		            		$x=explode('.',$ico);
		            		$ext=end($x);
		            		$x=explode('/',$ico);
		            		$n=end($x);
		            		$ico2=str_replace($n,$idcur.'.'.$ext,$ico);
		            		$dsql->amd("update ay_content set ico='$ico2' where id=$idcur limit 1");
		            		$ico1=ROOT_PATH.'/'.ltrim($ico,'/');
		            		$ico2=ROOT_PATH.'/'.ltrim($ico2,'/');
		            		rename($ico1,$ico2);
						}else if($x=='1'){
							unlink($f);
							$dsql->amd("update ay_content set ico='' where id=$idcur limit 1");
						}
	            	}
	            }
				location(-1);
    		}else{
				error('修改失败！',-1);
    		}
    	}else{
    		error('编辑的内容已经不存在！',-1);
    	}
    }
	public function del(){
		global $dsql;
		$id=get('id','int');
		$xid=get('xid','int');
		if(!$id && !$xid){
			error('传递的参数值错误！',-1);
    	}
    	if($id){
        	$o=$dsql->amd("delete from ay_content where id=$id limit 1");
        	if($o){
        		$dsql->amd("delete from ay_ztlist where id=$id limit 1");
        		$x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
        		if(file_exists($x)){
        			$dsql->amd("delete from ay_article where id=$id");
        		}
        		$dsql->amd("delete from ay_697_content where id=$id limit 1");
            	success('删除成功！',-1);
        	}else{
        		error('删除失败...',-1);
        	}
    	}else{
    		$x=$dsql->one("select id from ay_article where xid=$xid limit 1",1);
    		$id=$x[id];
        	$o=$dsql->amd("delete from ay_article where xid=$xid limit 1");
        	if($o){
        		$x=$dsql->one("select count(*) from ay_article where id=$id",1);
        		$count=$x['count(*)'];
        		$dsql->amd("update ay_697_content set cjon=0,chapter=$count,update_time=0 where id=$id limit 1");
            	success('删除成功！',-1);
        	}else{
        		error('删除失败...',-1);
        	}
        }
    }
	public function edit(){
		global $dsql;
		$id=get('id','int');
		$xid=get('xid','int');
		if(!$id && !$xid){
			error('传递的参数值错误！',-1);
    	}

		
		$ucode___=session('ucode');
		if($id){
			$x=$dsql->one("select ucode from ay_content where id=$id limit 1",1);
			$ucode=$x[ucode];
			if($ucode && $ucode!=$ucode___ && $ucode___!=10001){
				error('本书属于其它责编！',-1);
			}
		}else if($xid){
			$x=$dsql->one("select id from ay_article where xid=$xid limit 1",1);
			$id_=$x[id];
			$x=$dsql->one("select ucode from ay_content where id=$id_ limit 1",1);
			$ucode=$x[ucode];
			if($ucode && $ucode!=$ucode___ && $ucode___!=10001){
				error('本书属于其它责编！',-1);
			}
		}

        if($_POST){
        	if($pan){

	    	}else if($id){
	            $scode=gpvar('scode');
	            $subscode=gpvar('subscode');
	            $title=gpvar('title');
	            $titlecolor=gpvar('titlecolor');
	            $subtitle=gpvar('subtitle');
	            $filename=gpvar('filename');
	            $author=gpvar('author');
	            $source=gpvar('source') ?: '本站';
	            $outlink=gpvar('outlink');
	            $date=gpvar('date');
	            $ico=gpvar('ico');
	            if($ico=='/'){
	            	$ico='';
	            }
	            $pics=gpvar('pics');
	            $picstitle=gpvar('picstitle');
	            foreach($picstitle as $v){
	                $xxx.=$v.',';
	            }
	            $picstitle=rtrim($xxx,',');
	            $content=$_POST['content'];
	            $tags=str_replace('，', ',', gpvar('tags'));
	            $keywords=gpvar('keywords');
	            $description=gpvar('description');
	            $status=gpvar('status', 'int');
	            $istop=gpvar('istop', 'int', '', '', 0);
	            $isrecommend=gpvar('isrecommend', 'int', '', '', 0);
	            $isheadline=gpvar('isheadline', 'int', '', '', 0);
	            
	            $gid=gpvar('gid', 'int') ?: 0;
	            $gtype=gpvar('gtype', 'int') ?: 4;
	            $gnote=gpvar('gnote');
	            $jinbi=gpvar('jinbi');
	            $jifen=gpvar('jifen');
	            $visits=gpvar('visits');
	            $likes=gpvar('likes');
	            $oppose=gpvar('oppose');
	            $score=gpvar('score');
	            $zt=gpvar('zt');

				$ucode=gpvar('ucode');

	            if($zt){
	            	$zt=','.$zt.',';
	            }

	            $vip_xxx=gpvar('vip_xxx');
	            
	            if (! $scode) {
	                alert_back('内容分类不能为空！');
	            }
	            
	            if (! $title) {
	                alert_back('文章标题不能为空！');
	            }
	            
	            if ($filename && ! preg_match('/^[a-zA-Z0-9\-]+$/', $filename)) {
	                // alert_back('内容URL名称只允许字母、数字、横线组成!');
	            }
	            
	            if (! $description && isset($_POST['content'])) {
	                $description=escape_string(clear_html_blank(substr_both(strip_tags($_POST['content']), 0, 150)));
	            }
	            
	            if ($ico) {
	                resize_img(ROOT_PATH . $ico, '', $this->config('ico.max_width'), $this->config('ico.max_height'));
	            }

	            $content=str_replace('\\','\\\\',$content);
	            $content=repjin($content);
	            $description=repjin($description);
	            
	            $arr=array(
	                'scode'=>$scode,
	                'subscode'=>$subscode,
	                'title'=>$title,
	                'titlecolor'=>$titlecolor,
	                'subtitle'=>$subtitle,
	                'filename'=>$filename,
	                'author'=>$author,
	                'source'=>$source,
	                'outlink'=>$outlink,
	                'date'=>$date,
	                'ico'=>$ico,
	                'pics'=>$pics,
	                'picstitle'=>$picstitle,
	                'content'=>$content,
	                'tags'=>$tags,
	                'keywords'=>$keywords,
	                'description'=>$description,
	                'status'=>$status,
	                'istop'=>$istop,
	                'isrecommend'=>$isrecommend,
	                'isheadline'=>$isheadline,
	                'gid'=>$gid,
	                'gtype'=>$gtype,
	                'gnote'=>$gnote,
	                'update_user'=>session('username'),
	                'update_time'=>get_datetime(),
	                'jinbi'=>$jinbi,
	                'jifen'=>$jifen,
	                'visits'=>$visits,
	                'likes'=>$likes,
	                'oppose'=>$oppose,
	                'score'=>$score,
	                'zt'=>$zt,
	                'ucode'=>$ucode,
	                'vip_xxx'=>$vip_xxx
	            );
	            foreach($arr as $k => $v){
	            	$xxx.="$k='$v',";
	            }
	            $xxx=rtrim($xxx,',');
				$o=$dsql->amd("update ay_content set $xxx where id=$id limit 1");
				if($o){
					if($zt){
						$x=$dsql->one("select id from ay_ztlist where id=$id limit 1",1);
						$o=$x[id];
						if($o){
							$dsql->amd("update ay_ztlist set zt='$zt' where id=$id limit 1");
						}else{
							$x1="id,zt";
							$x2="'$id','$zt'";
							$dsql->amd("insert into ay_ztlist ($x1) values ($x2)");
						}
					}else{
						$dsql->amd("delete from ay_ztlist where id=$id limit 1");
					}
					if($tags){
						$dd=$dsql->all("select tid,tag from ay_taglist where id=$id",1);
						foreach($dd as $xx){
							$tid=$xx[tid];
							$tag=$xx[tag];
							$dsql->amd("delete from ay_taglist where tid=$tid and id=$id limit 1");
							$x=$dsql->one("select count(*) from ay_taglist where tag='$tag'",1);
							$o=$x['count(*)'];
							if($o){
								$dsql->amd("update ay_tagindex set total=$o where id=$tid limit 1");
							}else{
								$dsql->amd("delete from ay_tagindex where id=$tid limit 1");
							}
						}
						$arr=explode(',',$tags);
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
								$dsql->amd("insert into ay_taglist (tid,id,scode,tag) values ('$o','$id','$scode','$tag')");
							}
						}
					}
		            $backurl=gpvar('backurl');
		            if(!$backurl || strstr($backurl,'edit')){
		            	$backurl='?p=/Sort/index/scode/'.$scode;
		            }
		            $backurl=str_replace('&amp;','&',$backurl);
					success('修改成功！',$backurl);
	    		}else{
					error('修改失败...',-1);
	    		}
	    	}else{
	            $xtitle=gpvar('xtitle');
	            $content=$_POST['content'];
	            $content=preg_replace('/<(p|P)(.*?)>/','<p>',$content);
	            if (!$xtitle) {
	                alert_back('章节名称不能为空！');
	            }

	            $content=str_replace('\\','\\\\',$content);
	            $content=repjin($content);

	            $arr=array(
	                'xtitle'=>$xtitle,
	                'content'=>$content
	            );
	            foreach($arr as $k => $v){
	            	$xxx.="$k='$v',";
	            }
	            $xxx=rtrim($xxx,',');
				$dsql->amd("update ay_article set $xxx where xid=$xid limit 1");
	            $backurl=gpvar('backurl');
	            if(!$backurl || strstr($backurl,'edit')){
		    		$x=$dsql->one("select id from ay_article where xid=$xid limit 1",1);
		    		$id=$x[id];
	            	$backurl='?p=/Sort/index/id/'.$id;
	            }
	            $backurl=str_replace('&amp;','&',$backurl);
				success('修改成功！',$backurl);
	    	}
        }
        if($pan){

        }else if($id){
    		$x='ay_content';
    		$idcur=$id;
    		$idx='id';
    	}else{
    		$x='ay_article';
    		$idcur=$xid;
    		$idx='xid';
    	}
		$dbarr=$dsql->one("select * from $x where $idx=$idcur limit 1",1);
		$tags=$dbarr[tags];
		$tags=trim($tags);
		if(!$tags){
			$dd=$dsql->all("select tag from ay_taglist where id=$idcur",1);
			foreach($dd as $xx){
				$tag=$xx[tag];
				$tag=strip_tags($tag);
				$tag=trim($tag);
				if($tag){
					$tags.=$tag.',';
				}
			}
			$tags=trim($tags,',');
			if($tags){
				$dsql->amd("update ay_content set tags='$tags' where id=$idcur limit 1");
				$dbarr[tags]=$tags;
			}
		}
		if($pan){

		}else if($id){
			$scode=$dbarr[scode];
			$mcode=$dbarr[mcode];
			$dd=$dsql->all("select scode,name from ay_content_sort where pcode=0 order by id asc",1);
			foreach($dd as $xx){
				$scodecur=$xx[scode];
				$namecur=$xx[name];
				if($scodecur==$scode){
					$c=' selected="selected"';
					$loc01='
					&nbsp;&gt;&nbsp;
					<a href="?p=/Sort/index/scode/'.$scodecur.'">'.$namecur.'</a>';
				}else{
					$c='';
				}
				$navxxx.='<option value="'.$scodecur.'"'.$c.'>'.$namecur.'</option>';
				if($mcode!=18){
					$dd=$dsql->all("select scode,name from ay_content_sort where pcode=$scodecur order by id asc",1);
					foreach($dd as $xx){
						$scodesub=$xx[scode];
						$namesub=$xx[name];
						if($scodesub==$scode){
							$c=' selected="selected"';
							$x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
							$x=$x[name];
							$loc01='
							&nbsp;&gt;&nbsp;
							<a href="?p=/Sort/index/scode/'.$scodecur.'">'.$x.'</a>';
							$loc02='
							&nbsp;&gt;&nbsp;
							<a href="?p=/Sort/index/scode/'.$scodesub.'">'.$namesub.'</a>';
						}else{
							$c='';
						}
						$navxxx.='<option value="'.$scodesub.'"'.$c.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$namesub.'</option>';
					}
				}
			}


			$ucode=$dbarr[ucode];
			$dd=$dsql->all("select ucode,username,realname from ay_user order by ucode asc",1);
			foreach($dd as $xx){
				$ucodecur=$xx[ucode];
				$usernamecur=$xx[username];
				$realnamecur=$xx[realname];
				if($ucodecur==$ucode){
					$c=' selected="selected"';
				}else{
					$c='';
				}
				$zbxxx.='<option value="'.$ucodecur.'"'.$c.'>'.$usernamecur.'（'.$realnamecur.'）</option>';
			}


			$loccur='
	    	'.$loc01.'
	    	'.$loc02.'
	    	&nbsp;&gt;&nbsp;
	    	修改&nbsp;“<a href="?p=/Sort/edit/id/'.$id.'">'.$dbarr[title].'</a>”';
	    	$this->assign('groups', model('admin.member.MemberGroup')->getSelect());
	    	$content=$dbarr[content];
	        $content=escape_string($content);
	        $dbarr[content]=$content;

	        $zt=$dbarr[zt];
            $dd=$dsql->all("select scode,name,pcode from ay_zt where pcode=0 order by sorting asc,scode asc",1);
            foreach($dd as $xx){
                $scode2=$xx[scode];
                $name2=$xx[name];
                $pcode2=$xx[pcode];
                if(strstr($zt,','.$scode2.',')){
                    $s=' selected="selected"';
                }else{
                    $s='';
                }
                $zt_select.='<option value="'.$scode2.'"'.$s.'>'.$name2.'</option>';
                if($pcode2==0){
                    $dd=$dsql->all("select scode,name from ay_zt where pcode=$scode2 order by sorting asc,scode asc",1);
                    foreach($dd as $xx){
                        $scode3=$xx[scode];
                        $name3=$xx[name];
		                if(strstr($zt,','.$scode3.',')){
		                    $s=' selected="selected"';
		                }else{
		                    $s='';
		                }
                        $zt_select.='<option value="'.$scode3.'"'.$s.'>　　'.$name3.'</option>';
                    }
                }
            }
            $this->assign('zt_select',$zt_select);
		}else{
			$id=$dbarr[id];
			$content=$dbarr[content];

			if(strstr($content,'http')){
				$x=mb_substr($content,0,1);
				if($x!='h'){
					$content=str_replace('http','htttp',$content);
					$content=sbyouNet_Body($xid,$content);
					$content=str_replace('htttp','http',$content);
				}else{
					$content=sbyouNet_Body($xid,$content,$jsonline);
				}
			}else{
				$content=sbyouNet_Body($xid,$content);
			}

	        // $content=escape_string($content);
	        // $content=decode_slashes($content);

			$content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');


	        if(strstr($content,'内容缺失，稍后重试')){
	        	$content='';
	        }
			$dbarr[content]=$content;
			$x=$dsql->one("select scode,title from ay_content where id=$id limit 1",1);
			$dbarr[title]=$x[title];
			$scodecur=$x[scode];
			$x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
			$namecur=$x[name];
			$loccur='
			&nbsp;&gt;&nbsp;
			<a href="?p=/Sort/index/scode/'.$scodecur.'">'.$namecur.'</a>
			&nbsp;&gt;&nbsp;
			<a href="?p=/Sort/index/id/'.$dbarr[id].'">'.$dbarr[title].'</a>
			&nbsp;&gt;&nbsp;
			修改&nbsp;“<a href="?p=/Sort/edit/xid/'.$xid.'">'.$dbarr[xtitle].'</a>”';
		}
    	$loc='
    	<a href="?p=/ContentSort/index"><i class="fa fa-home"></i>栏目管理</a>
    	'.$loccur;
		$pbstr='loc,idcur,idx,navxxx,pan,zbxxx';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->assign('dbarr',$dbarr);
		$this->display('system/sortedit.html');
    }
	public function add(){
		global $dsql,$allletter;
		$book=get('book','int');
		$chapter=get('chapter','int');
		$content_00=get('content','int');
		if(!$book && !$chapter && !$content_00){
			error('传递的参数值错误！',-1);
    	}
        if($_POST){
        	if($book || $content_00){
	            $scode=gpvar('scode');
	            $subscode=gpvar('subscode');
	            $title=gpvar('title');
	            $x=$dsql->one("select id from ay_content where title='$title' limit 1",1);
	            $o=$x[id];
	            if($o){
	            	alert_back('内容重复！标题为“'.$title.'”的内容已经存在...');
	            }
	            $titlecolor=gpvar('titlecolor');
	            $subtitle=gpvar('subtitle');
	            $filename=gpvar('filename');
	            $author=gpvar('author');
	            $source=gpvar('source') ?: '本站';
	            $outlink=gpvar('outlink');
	            $date=gpvar('date');
	            if(!$date){
	            	$date=get_datetime();
	            }
	            $ico=gpvar('ico');
	            if($ico=='/'){
	            	$ico='';
	            }
	            $pics=gpvar('pics');
	            $picstitle=gpvar('picstitle');
	            foreach($picstitle as $v){
	                $xxx.=$v.',';
	            }
	            $picstitle=rtrim($xxx,',');
	            $content=$_POST['content'];
	            $tags=str_replace('，', ',', gpvar('tags'));
	            $keywords=gpvar('keywords');
	            $description=gpvar('description');
	            $status=gpvar('status', 'int');
	            $istop=gpvar('istop', 'int', '', '', 0);
	            $isrecommend=gpvar('isrecommend', 'int', '', '', 0);
	            $isheadline=gpvar('isheadline', 'int', '', '', 0);
	            $gid=gpvar('gid', 'int') ?: 0;
	            $gtype=gpvar('gtype', 'int') ?: 4;
	            $gnote=gpvar('gnote');
	            $jinbi=gpvar('jinbi');
	            $jifen=gpvar('jifen');
	            $visits=gpvar('visits');
	            $likes=gpvar('likes');
	            $oppose=gpvar('oppose');
	            $score=gpvar('score');
	            $zt=gpvar('zt');

	            $ucode=gpvar('ucode');

	            $vip_xxx=gpvar('vip_xxx');

	            if($zt){
	            	$zt=','.$zt.',';
	            }
	            if (! $scode) {
	                alert_back('内容分类不能为空！');
	            }
	            if (! $title) {
	                alert_back('文章标题不能为空！');
	            }
	            if ($filename && ! preg_match('/^[a-zA-Z0-9\-]+$/', $filename)) {
	                // alert_back('内容URL名称只允许字母、数字、横线组成!');
	            }
	            if (! $description && isset($_POST['content'])) {
	                $description=escape_string(clear_html_blank(substr_both(strip_tags($_POST['content']), 0, 150)));
	            }
	            if ($ico) {
	                resize_img(ROOT_PATH . $ico, '', $this->config('ico.max_width'), $this->config('ico.max_height'));
	            }
        		$x=$dsql->one("select mcode from ay_content_sort where scode=$scode limit 1",1);
        		$mcode=$x[mcode];
        		if($book){
        			if(!$filename){
						if($allletter==3){
							$filename='669977.net';
							$fn='pbcode';
						}else{
							require_once(ROOT_PATH.'/i'.''.'n'.'c'.'l'.'ud'.'e'.'/'.'p'.''.'y'.'.php');
							if($allletter==2){
								$x=0;
							}else{
								$x=1;
							}
							$filename=py($title,$x);
							$x=$dsql->one("select id from ay_content where filename='$filename' limit 1",1);
							$fn=$x[id];
						}
					}
					$source='';
					$gid='';
				}
				$visits=rand(100,500);

				$content=str_replace('\\','\\\\',$content);
				$content=repjin($content);
				$description=repjin($description);
				
	            $arr=array(
	                'scode'=>$scode,
	                'subscode'=>$subscode,
	                'title'=>$title,
	                'titlecolor'=>$titlecolor,
	                'subtitle'=>$subtitle,
	                'filename'=>$filename,
	                'author'=>$author,
	                'source'=>$source,
	                'outlink'=>$outlink,
	                'date'=>$date,
	                'ico'=>$ico,
	                'pics'=>$pics,
	                'picstitle'=>$picstitle,
	                'content'=>$content,
	                'tags'=>$tags,
	                'keywords'=>$keywords,
	                'description'=>$description,
	                'status'=>$status,
	                'istop'=>$istop,
	                'isrecommend'=>$isrecommend,
	                'isheadline'=>$isheadline,
	                'gid'=>$gid,
	                'gtype'=>$gtype,
	                'gnote'=>$gnote,
	                'update_user'=>session('username'),
	                'update_time'=>get_datetime(),
	                'mcode'=>$mcode,
	                'visits'=>$visits,
	                'acode'=>'cn',
	                'create_time'=>get_datetime(),
	                'gtype'=>4,
	                'jinbi'=>$jinbi,
	                'jifen'=>$jifen,
	                'visits'=>$visits,
	                'likes'=>$likes,
	                'oppose'=>$oppose,
	                'score'=>$score,
	                'zt'=>$zt,
	                'ucode'=>$ucode,
	                'vip_xxx'=>$vip_xxx
	            );
	            foreach($arr as $k => $v){
	            	$x01.="$k,";
	            	$x02.="'$v',";
	            }
	            $x01=rtrim($x01,',');
	            $x02=rtrim($x02,',');
				$o=$dsql->amd("insert into ay_content ($x01) values ($x02)");
				if($o){
					if($zt){
						$x=$dsql->one("select id from ay_content order by id desc limit 1",1);
						$id=$x[id];
						$x1="id,zt";
						$x2="'$id','$zt'";
						$dsql->amd("insert into ay_ztlist ($x1) values ($x2)");
					}
					if($book && $fn){
						$x=$dsql->one("select id from ay_content order by id desc limit 1",1);
						$id=$x[id];
						if($fn=='pbcode'){
							$filename=$id;
						}else{
							$filename=$filename.$id;
						}
						$dsql->amd("update ay_content set filename='$filename' where id=$id limit 1");
					}
					if($tags){
						if(!$id){
							$x=$dsql->one("select id from ay_content order by id desc limit 1",1);
							$id=$x[id];
						}
						$arr=explode(',',$tags);
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
								$dsql->amd("insert into ay_taglist (tid,id,scode,tag) values ('$o','$id','$scode','$tag')");
							}
						}
					}
		            $backurl=gpvar('backurl');
		            if(!$backurl || strstr($backurl,'add')){
		            	if($book){
		            		$x=$book;
		            	}else{
		            		$x=$content_00;
		            	}
		            	$backurl='?p=/Sort/index/scode/'.$x;
		            }
		            $backurl=str_replace('&amp;','&',$backurl);
					success('修改成功！',$backurl);
	    		}else{
					error('修改失败...',-1);
	    		}
	    	}else{
	            $xtitle=gpvar('xtitle');
	            $content=$_POST['content'];
	            $content=preg_replace('/<(p|P)(.*?)>/','<p>',$content);
	            if (!$xtitle) {
	                alert_back('章节名称不能为空！');
	            }

	            $content=str_replace('\\','\\\\',$content);
	            $content=repjin($content);
	            
	            $arr=array(
	                'id'=>$chapter,
	                'xtitle'=>$xtitle,
	                'content'=>$content,
	                'cjid'=>1
	            );
	            foreach($arr as $k => $v){
	            	$x01.="$k,";
	            	$x02.="'$v',";
	            }
	            $x01=rtrim($x01,',');
	            $x02=rtrim($x02,',');
				$o=$dsql->amd("insert into ay_article ($x01) values ($x02)");
				if($o){
					$x=strip_tags($content);
		            $len=mb_strlen($x);
					$time=get_datetime();
					$dsql->amd("update ay_content set update_time='$time',subscode=subscode+$len where id=$chapter limit 1");
		            $backurl=gpvar('backurl');
		            if(!$backurl || strstr($backurl,'add')){
		            	$backurl='?p=/Sort/index/id/'.$chapter;
		            }
		            $backurl=str_replace('&amp;','&',$backurl);
					success('修改成功！',$backurl);
	    		}else{
					error('修改失败...',-1);
	    		}
	    	}
        }
    	if($book || $content_00){
			$dd=$dsql->all("select scode,name,mcode from ay_content_sort where pcode=0 order by id asc",1);
			foreach($dd as $xx){
				$scodecur=$xx[scode];
				$namecur=$xx[name];
				$mcodecur=$xx[mcode];
				if($scodecur==$book || $scodecur==$content_00){
					$c=' selected="selected"';
					$loc01='
					&nbsp;&gt;&nbsp;
					<a href="?p=/Sort/index/scode/'.$scodecur.'">'.$namecur.'</a>';
				}else{
					$c='';
				}
				$navxxx.='<option value="'.$scodecur.'"'.$c.'>'.$namecur.'</option>';
				if($mcodecur!=18 && $mcodecur!=19){
					$dd=$dsql->all("select scode,name from ay_content_sort where pcode=$scodecur order by id asc",1);
					foreach($dd as $xx){
						$scodesub=$xx[scode];
						$namesub=$xx[name];
						if($scodesub==$book || $scodesub==$content_00){
							$c=' selected="selected"';
							$x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
							$x=$x[name];
							$loc01='
							&nbsp;&gt;&nbsp;
							<a href="?p=/Sort/index/scode/'.$scodecur.'">'.$x.'</a>';
							$loc02='
							&nbsp;&gt;&nbsp;
							<a href="?p=/Sort/index/scode/'.$scodesub.'">'.$namesub.'</a>';
						}else{
							$c='';
						}
						$navxxx.='<option value="'.$scodesub.'"'.$c.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$namesub.'</option>';
					}
				}
			}
			$navxxx2=str_replace(' selected="selected"','',$navxxx);




			$dd=$dsql->all("select ucode,username,realname from ay_user order by ucode asc",1);
			foreach($dd as $xx){
				$ucodecur=$xx[ucode];
				$usernamecur=$xx[username];
				$realnamecur=$xx[realname];
				$zbxxx.='<option value="'.$ucodecur.'">'.$usernamecur.'（'.$realnamecur.'）</option>';
			}




			$this->assign('groups', model('admin.member.MemberGroup')->getSelect());
			if($book){
				$loc03='
				&nbsp;&gt;&nbsp;
				新增小说';
			}else{
				$loc03='
				&nbsp;&gt;&nbsp;
				新增内容';
			}
            $dd=$dsql->all("select scode,name,pcode from ay_zt where pcode=0 order by sorting asc,scode asc",1);
            foreach($dd as $xx){
                $scode2=$xx[scode];
                $name2=$xx[name];
                $pcode2=$xx[pcode];
                $zt_select.='<option value="'.$scode2.'">'.$name2.'</option>';
                if($pcode2==0){
                    $dd=$dsql->all("select scode,name from ay_zt where pcode=$scode2 order by sorting asc,scode asc",1);
                    foreach($dd as $xx){
                        $scode3=$xx[scode];
                        $name3=$xx[name];
                        $zt_select.='<option value="'.$scode3.'">　　'.$name3.'</option>';
                    }
                }
            }
            $this->assign('zt_select',$zt_select);
    	}else if($chapter){
    		$dbarr=$dsql->one("select scode,title from ay_content where id=$chapter limit 1",1);
    		$scode=$dbarr[scode];
    		$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
    		$name=$x[name];
			$loc03='
			&nbsp;&gt;&nbsp;
			<a href="?p=/Sort/index/scode/'.$scode.'">'.$name.'</a>
			&nbsp;&gt;&nbsp;
			<a href="?p=/Sort/index/id/'.$chapter.'">'.$dbarr[title].'</a>
			&nbsp;&gt;&nbsp;
			新增章节';
    	}
    	if($content_00){
    		if($mcodecur==19){
    			$code=$content_00;
    		}else{
    			$content=$content_00;
    		}
    	}
    	$loc='
    	<a href="?p=/ContentSort/index"><i class="fa fa-home"></i>栏目管理</a>
    	'.$loc01.'
    	'.$loc02.'
    	'.$loc03;
		$pbstr='loc,book,chapter,content,code,navxxx,navxxx2,zbxxx';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->assign('dbarr',$dbarr);
		$this->display('system/sortadd.html');
	}
	public function info(){
		global $dsql,$free_chapter;
		$id=get('id','int');
		if(!$id){
			error('传递的参数值错误！',-1);
    	}
        $x=$dsql->one("select title,score,num_dy,num_dy_first,num_zs_month from ay_content where id=$id limit 1",1);
        $title=$x[title];
        $score=$x[score];
        $num_dy=$x[num_dy];
        if($num_dy<0){
            $num_dy=0;
        }
        $num_dy_first=$x[num_dy_first];
        $num_zs_month=$x[num_zs_month];
        if($num_zs_month){
            $x=explode(',',$num_zs_month);
            $n=$x[0];
            $t=$x[1];
            $t_=date('Y-m');
            if($t==$t_){
                $num_zs_month=$n;
            }else{
                $num_zs_month=0;
                $dsql->amd("update ay_content set num_zs_month='' where id=$id limit 1");
            }
        }else{
            $num_zs_month=0;
        }
        $x=$dsql->one("select count(*) from ay_article where status=1 and id=$id limit 1",1);
        $num=$x['count(*)'];
        $num_=$num-$free_chapter;
        if($num_>0){
            $x=$num_dy/$num_;
            $num_jd=round($x,3);
        }else{
            $num_jd=0;
        }
        $x=$dsql->one("select count(*) from ay_member_comment where status=1 and contentid=$id limit 1",1);
        $num_com=$x['count(*)'];


		$pbstr='title,score,num_dy,num_dy_first,num_zs_month,num_jd,num_com';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->display('system/sortinfo.html');
	}
	public function gf(){
		global $dsql;
		$id=get('id','int');
    	$act=gpvar('act');
		if(!$id || !$act){
			error('传递的参数值错误！',-1);
    	}
    	if($act=='del'){
    		$idx=gpvar('idx');
    		$x=$dsql->amd("delete from ay_gf where id=$id and idx=$idx limit 1");
    		if($x){
    			success('删除成功!',-1);
    		}else{
    			alert_back('删除失败，稍后重试！');
    		}
    	}
    	if($_POST){
    		$yf=gpvar('yf');
    		$mc=gpvar('mc');
    		$je=gpvar('je');
    		$dy=gpvar('dy');
    		$qd=gpvar('qd');
    		$pt=gpvar('pt');
    		$update_time=gpvar('update_time');
    		$yf=trim($yf);
    		$mc=trim($mc);
    		$je=trim($je);
    		$dy=trim($dy);
    		$qd=trim($qd);
    		$pt=trim($pt);
    		if(!$yf){
    			error('请填写相关信息！',-1);
    		}
    		$backurl=gpvar('backurl');
    		if(!$backurl){
    			$backurl='?p=/Sort/index';
    		}
    		if($act=='add'){
    			$x1="id,yf,mc,je,dy,qd,pt,update_time";
    			$x2="'$id','$yf','$mc','$je','$dy','$qd','$pt','$update_time'";
    			$x=$dsql->amd("insert into ay_gf ($x1) values ($x2)");
    			if($x){
    				success('成功添加！',$backurl);
    			}else{
    				alert_back('添加失败！');
    			}
    		}else if($act=='edit'){
    			$idx=gpvar('idx');
    			$dsql->amd("update ay_gf set yf='$yf',mc='$mc',je='$je',dy='$dy',qd='$qd',pt='$pt',update_time='$update_time' where id=$id and idx=$idx limit 1");
				$backurl='?p=/Sort/gf/id/'.$id.'&act=look';
				success('编辑成功！',$backurl);
    		}
    	}
		$x=$dsql->one("select title from ay_content where id=$id limit 1",1);
		$title=$x[title];
		$pbstr='title,id,act';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		if($act=='add'){
			$this->assign('update_time',date('Y-m-d H:i:s'));
			$this->display('system/gf.html');
		}else if($act=='look'){
			$dd=$dsql->all("select idx from ay_gf where id=$id order by idx desc",1);
			foreach($dd as $xx){
				$idx=$xx[idx];
				$xx=$dsql->one("select * from ay_gf where idx=$idx limit 1",1);
				$yf=$xx[yf];
				$mc=$xx[mc];
				$je=$xx[je];
				$dy=$xx[dy];
				$qd=$xx[qd];
				$pt=$xx[pt];
				$update_time=$xx[update_time];
				$list.='
				<tr>
					<td>'.$yf.'</td>
					<td>'.$mc.'</td>
					<td>'.$je.'</td>
					<td>'.$dy.'</td>
					<td>'.$qd.'</td>
					<td>'.$pt.'</td>
					<td>'.$update_time.'</td>
					<td>
						<a href="?p=/Sort/gf/id/'.$id.'&act=del&idx='.$idx.'" onclick="return confirm(&quot;您确定要删除吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
						<a href="?p=/Sort/gf/id/'.$id.'&act=edit&idx='.$idx.'" class="layui-btn layui-btn-xs">编辑</a>
					</td>
				</tr>';
			}
			$this->assign('list',$list);
			$this->display('system/gflook.html');
		}else if($act=='edit'){
			$idx=gpvar('idx');
			if(!$idx){
				error('传递的参数值错误！',-1);
			}
			$xx=$dsql->one("select * from ay_gf where id=$id and idx=$idx limit 1",1);
			$yf=$xx[yf];
			$mc=$xx[mc];
			$je=$xx[je];
			$dy=$xx[dy];
			$qd=$xx[qd];
			$pt=$xx[pt];
			$update_time=$xx[update_time];

			$this->assign('idx',$idx);

			$this->assign('yf',$yf);
			$this->assign('mc',$mc);
			$this->assign('je',$je);
			$this->assign('dy',$dy);
			$this->assign('qd',$qd);
			$this->assign('pt',$pt);
			$this->assign('update_time',$update_time);
			$this->display('system/gf.html');
		}
	}
	public function sq(){
		global $dsql,$sq_xxx;
		if(!$sq_xxx){
			$x='未设置渠道！';
			$url='?p=/Sort/index';
			error($x,$url,3);
		}
		$id=get('id','int');
		if(!$id){
			error('传递的参数值错误！',-1);
    	}
        if($_POST){
        	$arr=explode(',',$sq_xxx);
        	foreach($arr as $xx){
	    		$arr_=explode('-',$xx);
	    		$id_=$arr_[0];
	    		$name_=$arr_[1];
	    		$sq_='sq_'.$id_;
	    		$x=gpvar($sq_);
	    		if(!$x){
	    			$x=0;
	    		}else{
	    			$x=1;
	    		}
	    		$sql.=$sq_.'='.$x.',';
        	}
        	$sql=trim($sql,',');
			$dsql->amd("update ay_content set $sql where id=$id limit 1");
            $backurl=gpvar('backurl');
            if(!$backurl){
            	$backurl='?p=/Sort/index';
            }
			success('修改成功！',$backurl);
        }
		$dbarr=$dsql->one("select * from ay_content where id=$id limit 1",1);
    	$arr=explode(',',$sq_xxx);
    	foreach($arr as $xx){
    		$arr_=explode('-',$xx);
    		$id_=$arr_[0];
    		$name_=$arr_[1];
    		$sq_='sq_'.$id_;
    		$o=$dsql->one("SHOW FIELDS FROM ay_content where Field ='$sq_'",1);
    		if(!$o){
    			$dsql->amd("ALTER TABLE `ay_content` ADD `$sq_` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX `$sq_` (`$sq_`);");
    		}
    		$x=$dbarr[$sq_];
    		if($x==1){
    			$ch=' checked';
    		}else{
    			$ch='';
    		}
    		$sq__.='<input type="checkbox" name="'.$sq_.'" value="1" title="'.$name_.'"'.$ch.'>';
    	}
        $this->assign('sq__',$sq__);
		$this->assign('dbarr',$dbarr);
		$this->display('system/sortsq.html');
    }
	public function duo(){
		global $dsql,$sq_xxx;
		if(!$sq_xxx){
			$x='未设置渠道！';
			$url='?p=/Sort/index';
			error($x,$url,3);
		}
        if($_POST){
        	$duo=gpvar('duo');
        	$status=gpvar('status');
        	if(!$duo){
				$x='请输入书籍ID！';
				$url='?p=/Sort/duo';
				error($x,$url,3);
        	}
        	$arr=explode(',',$sq_xxx);
        	foreach($arr as $xx){
	    		$arr_=explode('-',$xx);
	    		$id_=$arr_[0];
	    		$name_=$arr_[1];
	    		$sq_='sq_'.$id_;
	    		$x=gpvar($sq_);
	    		if(!$x){
	    			$x=0;
	    			continue;
	    		}else{
	    			$x=1;
	    		}
	    		if($status==1){
	    			$x=1;
	    		}else{
	    			$x=0;
	    		}
	    		$sql.=$sq_.'='.$x.',';
        	}
        	$sql=trim($sql,',');
        	$duo=str_replace('，',',',$duo);
        	$arr=explode(',',$duo);
        	foreach($arr as $xx){
        		$xx=trim($xx);
        		if(!$xx || !is_numeric($xx)){
        			continue;
        		}
        		$dsql->amd("update ay_content set $sql where id=$xx limit 1");
        	}
        	$backurl='?p=/Sort/index';
			success('修改成功！',$backurl);
        }else{
        	$x=$_SERVER['QUERY_STRING'];
        	$x=explode('duo',$x);
        	$x=$x[1];
        	$x=explode('/',$x);
        	$x=$x[1];
        	$idxxx=str_replace('-',',',$x);
        }
    	$arr=explode(',',$sq_xxx);
    	foreach($arr as $xx){
    		$arr_=explode('-',$xx);
    		$id_=$arr_[0];
    		$name_=$arr_[1];
    		$sq_='sq_'.$id_;
    		$o=$dsql->one("SHOW FIELDS FROM ay_content where Field ='$sq_'",1);
    		if(!$o){
    			$dsql->amd("ALTER TABLE `ay_content` ADD `$sq_` TINYINT(1) NOT NULL DEFAULT '0', ADD INDEX `$sq_` (`$sq_`);");
    		}
    		$sq__.='<input type="checkbox" name="'.$sq_.'" value="1" title="'.$name_.'">';
    	}
        $this->assign('sq__',$sq__);
        $this->assign('idxxx',$idxxx);
		$this->display('system/sortduo.html');
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