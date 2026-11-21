<?php
namespace app\admin\controller\system;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;
class CjController extends Controller
{
	public function index(){
		global $dsql;
		$x=$dsql->one("select mcode from ay_content_sort limit 1",1);
		$mcode=$x[mcode];
		$dd=$dsql->all("select * from ay_697_cj order by cid desc",1);
		foreach($dd as $xx){
			$cid=$xx[cid];
			$cj=$xx[cj];
			$rule=$xx[rule];
			$rule_697code_com=$xx[rule_697code_com];
			$status=$xx[status];
			$scode=$xx[scode];
			$name=$xx[name];
			if($mcode==18 && $cj==1 && $rule){
				$x=$dsql->one("select cid from ay_697_cj where cj=2 and cid=$rule limit 1",1);
				$o=$x[cid];
				if(!$o){
					$rule=0;
					$status=0;
					$dsql->amd("update ay_697_cj set rule=$rule,status=$status where cid=$cid limit 1");
				}
			}
    		if($scode){
    			if($cj==1){
	    			$x=$dsql->one("select name from ay_content_sort where scode=$scode limit 1",1);
	    			$o=$x[name];
	    			if(!$o){
	    				if($mcode==18){
	    					$dsql->amd("update ay_697_cj set scode=0 where cid=$cid limit 1");
	    					if(!$name){
			    				$scode='<span class="red">未指定</span>';
	    					}else{
	    						$scode='自动归类';
	    					}
	    				}else{
		    				$dsql->amd("update ay_697_cj set scode=0 where cid=$cid limit 1");
		    				$scode='<span class="red">未指定</span>';
	    				}
	    			}else{
	    				$scode.=' - '.$o;
	    			}
	    		}else{
	    			$scode='---.---';
	    		}
    		}else{
    			$scode='<span class="red">未指定</span>';
    			if($mcode==18 && $cj==1 && $name){
	    			$scode='自动归类';
    			}else if($cj==2){
    				$scode='---.---';
    			}
    		}
			if(strstr($scode,'未指定')){
				$status=0;
				$dsql->amd("update ay_697_cj set status=$status where cid=$cid limit 1");
			}
    		if($mcode==18){
    			if($cj==1){
	    			if(!$rule){
	    				$x='<span class="red">（未关联）</span>';
	    				$status=0;
	    				$dsql->amd("update ay_697_cj set status=$status where cid=$cid limit 1");
	    			}else{
	    				$x='';
	    			}
	    			$rule='<td class="table-input"><input type="text" lay-ignore="" class="layui-input" name="rule['.$cid.']" value="'.$rule.'">'.$x.'</td>';
    				$cjx='<td>小说规则</td>';
    			}else{
    				$rule='<td class="table-input">---.---</td>';
    				$cjx='<td>章节规则</td>';
    				$status='---.---';
    			}
    		}else{
    			$rule='';
    		}
			if($status==1){
				$status='<a href="?p=/cj/mod/cid/'.$cid.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
				$wqy='';
    		}else if(is_numeric($status)){
				$status='<a href="?p=/cj/mod/cid/'.$cid.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
				$wqy='&nbsp;<span class="layui-badge layui-bg-red">未启用</span>';
    		}else{
    			$wqy='';
    		}
			$url='?p=/cj/edit/cid/'.$cid;
			$list.='
			<tr>
				<td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$cid.'"></td>
				<td>'.$cid.'</td>
				<td><a href="'.$url.'">'.$rule_697code_com.'</a>'.$wqy.'</td>
				'.$cjx.'
				<td>'.$scode.'</td>
				'.$rule.'
				<td>'.$status.'</td>
				<td>
					<a href="?p=/cj/del/cid/'.$cid.'" onclick="return tip()" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
					<a href="?p=/cj/edit/cid/'.$cid.'" class="layui-btn layui-btn-xs">编辑</a>
				</td>
			</tr>';
    	}
    	if(!$list){
    		$list='
    		<tr>
    			<td colspan="8">暂无规则</td>
    		</tr>';
    	}
		$pbstr='list,mcode';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->display('system/cj.html');
    }
	public function mod(){
		global $dsql;
        if(!!$submit=post('submit')){
            switch ($submit) {
                case 'status_1':
		        	$list=post('list');
                    if($list){
                    	$x=$dsql->one("select mcode from ay_content_sort limit 1",1);
                    	$mcode=$x[mcode];
                        foreach($list as $k => $v){
                        	$x=$dsql->one("select rule,scode,name from ay_697_cj where cid=$v limit 1",1);
                        	$rule=$x[rule];
                        	$scode=$x[scode];
                        	$name=$x[name];
                        	if($mcode==18){
	                        	if(!$rule){
	                        		continue;
	                        	}else{
	                        		$x=$dsql->one("select cid from ay_697_cj where cj=2 and cid=$rule limit 1",1);
	                        		$o=$x[cid];
	                        		if(!$o){
	                        			$dsql->amd("update ay_697_cj set rule=0 where cid=$v limit 1");
	                        			continue;
	                        		}
	                        	}
	                        }
	                        if(!$scode){
	                        	if($mcode==18){
	                        		if(!$name){
	                        			continue;
	                        		}
	                        	}else{
	                        		continue;
	                        	}
	                        }
                        	$dsql->amd("update ay_697_cj set status=1 where cj=1 and cid=$v limit 1");
                        	$dsql->amd("update ay_697_content set status=1 where cid=$v");
                        }
                        success('修改成功！',-1);
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
                    break;
                case 'status_0':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	$dsql->amd("update ay_697_cj set status=0 where cj=1 and cid=$v limit 1");
                        	$dsql->amd("update ay_697_content set status=0 where cid=$v");
                        }
                        success('修改成功！',-1);
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
                    break;
                case 'del':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
	                    	$o=$dsql->amd("delete from ay_697_cj where cid=$v limit 1");
	                    	if(!$o){
	                    		break;
	                    	}else{
	                    		$dsql->amd("delete from ay_697_content where cid=$v");
	                    	}
	                    }
                        if($o){
                        	success('删除成功！',-1);
                        }else{
                        	error('部分项目删除失败...',-1);
                        }
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
                    break;
                case 'copy':
		        	$list=post('list');
                    if($list){
                    	$list=array_reverse($list);
                        foreach($list as $k => $v){
                        	$arr=$dsql->one("select * from ay_697_cj where cid=$v limit 1",1);
                        	unset($arr[cid]);
                        	$x01='';
                        	$x02='';
				            foreach($arr as $k2 => $v2){
				            	$v2=str_replace('\\','\\\\',$v2);
				            	$x01.="$k2,";
				            	$x02.="'$v2',";
				            }
				            $x01=rtrim($x01,',');
				            $x02=rtrim($x02,',');
							$o=$dsql->amd("insert into ay_697_cj ($x01) values ($x02)");
							if(!$o){
								break;
				    		}
                        }
                        if($o){
                        	success('复制成功！',-1);
                        }else{
                        	error('部分项目复制失败...',-1);
                        }
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
                    break;
                case 'rule':
		        	$rule=post('rule');
                    if($rule){
                        foreach($rule as $k => $v){
                        	if($v){
	                        	$x=$dsql->one("select cid from ay_697_cj where cj=2 and cid=$v limit 1",1);
	                        	$o=$x[cid];
	                        	if(!$o){
	                        		error('请填写“章节规则”类型的ID！<br>',-1);
	                        	}
	                        }
	                    	$dsql->amd("update ay_697_cj set rule=$v where cid=$k limit 1");
	                    }
	                    success('保存成功！',-1);
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
                    break;
                case 'out':
		        	$list=post('list');
                    if($list){
                    	$list=array_reverse($list);
                        foreach($list as $k => $v){
	                    	$xxx.="{pbcode.xyz}\n";
	                    	$arr=$dsql->one("select * from ay_697_cj where cid=$v limit 1",1);
	                    	unset($arr[cid]);
	                    	foreach($arr as $k2 => $v2){
	                    		$v2=str_replace(PHP_EOL,'\n',$v2);
	                    		$xxx.=$v2.'{697}';
	                    	}
	                    	$xxx=substr($xxx,0,-5);
	                    	$xxx=$xxx."\n{/pbcode.xyz}\n\n";
	                    }
	                    $xxx=trim($xxx);
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
					$pbstr='xxx';
					$pbstr=explode(',',$pbstr);
					foreach($pbstr as $v){
						$pbarr[ $v ]=$$v;
					}
					$this->assign('pbarr',$pbarr);
					$this->display('system/cjrule.html');
                    exit;
                    break;
                case 'empty':
		        	$list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                        	$x=$dsql->one("select cid from ay_697_cj where cj=1 and cid=$v limit 1",1);
                        	$o=$x[cid];
                        	if($o){
                        		$dsql->amd("delete from ay_697_content where cid=$v");
                        		$dsql->amd("update ay_697_cj set cjcur=0 where cid=$v limit 1");
                        	}
                        }
                        success('已清空相关记录！',-1);
                    }else{
                        error('操作失败，未选择操作项目！',-1);
                    }
                    break;
            }
        }
		$cid=get('cid','int');
		$field=get('field','var');
		$value=get('value','var');
		if(!$cid){
			error('传递的参数值错误！',-1);
    	}
		if($field && !is_null($value)){
			$x=$dsql->one("select mcode from ay_content_sort limit 1",1);
			$mcode=$x[mcode];
        	$x=$dsql->one("select rule,scode,name from ay_697_cj where cid=$cid limit 1",1);
        	$rule=$x[rule];
        	$scode=$x[scode];
        	$name=$x[name];
        	if($mcode==18){
	        	if(!$rule){
	        		error('请设置“关联章节规则ID”！',-1);
	        	}else{
	        		$x=$dsql->one("select cid from ay_697_cj where cj=2 and cid=$rule limit 1",1);
	        		$o=$x[cid];
	        		if(!$o){
	        			$dsql->amd("update ay_697_cj set rule=0 where cid=$cid limit 1");
	        			error('请设置“关联章节规则ID”！',-1);
	        		}
	        	}
	        }
        	if(!$scode){
        		if($mcode==18){
        			if(!$name){
        				error('请设置“发布栏目”！',-1);
        			}
        		}else{
        			error('请设置“发布栏目”！',-1);
        		}
        	}
			$o=$dsql->amd("update ay_697_cj set $field=$value where cid=$cid limit 1");
			if($o){
				$dsql->amd("update ay_697_content set status=$value where cid=$cid");
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
		$cid=get('cid','int');
		if(!$cid){
			error('传递的参数值错误！',-1);
    	}
    	$o=$dsql->amd("delete from ay_697_cj where cid=$cid limit 1");
    	if($o){
    		$dsql->amd("delete from ay_697_content where cid=$cid");
        	success('删除成功！',-1);
    	}else{
    		error('删除失败...',-1);
    	}
    }
	public function edit(){
		global $dsql,$pcbase,$cid;
		$cid=get('cid','int');
		if(!$cid){
			error('传递的参数值错误！',-1);
    	}
		$x=$dsql->one("select mcode from ay_content_sort limit 1",1);
		$mcode=$x[mcode];
		$dbarr=$dsql->one("select * from ay_697_cj where cid=$cid limit 1",1);
		foreach($dbarr as $k => $v){
			if(strstr($k,'rep')){
				$v=decode_string($v);
				$v=str_replace('&','&amp;',$v);
			}else{
				$v=decode_slashes($v);
			}
			$dbarr[$k]=$v;
		}
		$cj=$dbarr[cj];
        if($_POST){
			$arr=$dsql->all("show fields from ay_697_cj",1);
			foreach($arr as $k => $v){
				$x=$v[Field];
				$xarr=array('cid','cj','cjcur','rule','status');
				if(in_array($x,$xarr)){
					continue;
				}
				$xv=post($x);
            	$xv=addslashes($xv);
            	$xxx.="$x='$xv',";
			}
            $xxx=rtrim($xxx,',');
			$dsql->amd("update ay_697_cj set $xxx where cid=$cid limit 1");
			$submit=post('submit');
			if($submit=='test'){
				require_once(ROOT_PATH.'/c'.''.'j/'.'inc'.'lude/'.'c'.'j'.'.f'.'u'.'n'.'.php');
				$dbarr=$dsql->one("select * from ay_697_cj where cid=$cid limit 1",1);
				foreach($dbarr as $k => $v){
					$v=decode_string($v);
					$dbarr[$k]=$v;
				}
				$url=$dbarr[url];
				$num1=$dbarr[num1];
				$num2=$dbarr[num2];
				$urlrep1=$dbarr[urlrep1];
				$urlrep2=$dbarr[urlrep2];
				$urlyes=$dbarr[urlyes];
				$urlno=$dbarr[urlno];
				$testurl=$dbarr[testurl];
				!$num1?$num1=1:'';
				!$num2?$num2=1:'';
				$x=rand($num1,$num2);
				$url=trim($url);
				$url=str_replace("\n",PHP_EOL,$url);
				$arr=explode(PHP_EOL,$url);
				shuffle($arr);
				$url=$arr[0];
				$url=str_replace('['.''.'p'.'b'.''.'c'.'o'.'de'.'.'.'x'.''.'y'.'z'.']',$x,$url);
				$h=cj_url($url);
				$h=xxxrep($h,$urlrep1,$urlrep2);
				$arr=xxxhref($h,$url,$urlyes,$urlno);
				foreach($arr as $k => $v){
					$ii++;
					$list01.='
                    <tr>
                        <td>'.$ii.'</td>
                        <td>'.$v.'</td>
                    </tr>';
					if($ii==3){
						break;
					}
				}
				if(!$list01){
	            	$list01.='
	                <tr>
	                    <td colspan="4">
		                    <p style="color:red;font-size:16px;padding:10px 0px">无法获取列表...</p>
		                    <p>请按以下步骤检查：</p>
		                    <p>1、浏览器打开网址 <a style="color:blue" href="'.$url.'" target="_blank">'.$url.'</a>，检查目标站是否能正常打开</p>
		                    <p>2、如果目标站打不开，那么，采集规则已失效，此时找客服更换采集规则</p>
		                    <p>3、如果目标站能打开，那么，点击 <a style="color:blue" href="'.$pcbase.'cj/?my='.$url.'" target="_blank">测试</a>，检查目标站是否防采集</p>
		                    <p>4、如果测试结果为“空白页、乱码、或其它报错”，说明目标站防采集，正常情况应该显示目标站页面（排版错乱属正常）</p>
		                    <p>5、步骤4中：不能显示页面，说明目标站防采集，要更换采集规则；能显示页面，说明规则失效，要修复采集规则</p>
		                    <p>6、找客服修复或更换采集规则</p>
		                    <p>源码官网：<a style="color:blue" href="//697code.com/" target="_blank">697code.com</a></p>
		                    <p>QQ / 微信：1981255858</p>
	                    </td>
	                </tr>';
				}else{
	            	$list01.='
	                <tr>
	                    <td>...</td>
	                    <td>......</td>
	                </tr>';
	                if(!$testurl){
	                	shuffle($arr);
	                	$testurl=$arr[0];
	                }
	            }
	            if(!$testurl){
	            	$list02='
	                <tr>
	                    <td colspan="4">缺少测试地址...</td>
	                </tr>';
	            }else{
	            	$h=cj_url($testurl);
	            	foreach($dbarr as $k => $v){
	            		$xarr=array('cid','cj','cjcur','rule','rule_697code_com','status','scode','url','num1','num2','urlrep1','urlrep2','urlyes','urlno','testurl');
	            		if((in_array($k,$xarr) && $k!='subscode') || strstr($k,'rep') || !$v){
	            			continue;
	            		}
	            		if($k=='chapterurl'){
			            	if($v=='697code.com'){
			            		$v=$testurl;
			            		$v=xxxrepmini($v,$v,$dbarr[$k.'rep']);
			            	}else{
			            		$v=xxxrepmini($h,$v,$dbarr[$k.'rep']);
			            	}
			            	if($v){
			            		$v='<a href="'.$v.'" target="_blank">'.$v.'</a>';
			            	}
	            		}else{
	            			$v=xxxrepmini($h,$v,$dbarr[$k.'rep']);
	            		}
	            		if($k=='content'){
	            			$s=' style="background:#fff;text-indent:0px;padding:5px 8px"';
	            			$v=escape_string($v);
	            		}else{
	            			$s='';
	            		}
	            		$list02.='
                        <tr>
                            <td>'.$k.'</td>
                            <td'.$s.'>'.$v.'</td>
                        </tr>';
	            	}
	            }
				$pbstr='mcode,list01,url,list02,testurl';
				$pbstr=explode(',',$pbstr);
				foreach($pbstr as $v){
					$pbarr[ $v ]=$$v;
				}
				$this->assign('pbarr',$pbarr);
				$this->assign('dbarr',$dbarr);
				$this->display('system/cjtest.html');
			}else{
				success('修改成功！','?p=/cj/index');
			}
        }
    	if($cj!=2){
    		$scode=$dbarr[scode];
			$dd=$dsql->all("select scode,name from ay_content_sort where pcode=0 order by sorting asc,id asc",1);
			foreach($dd as $xx){
				$scodecur=$xx[scode];
				$namecur=$xx[name];
				if($scodecur==$scode){
					$c=' selected="selected"';
				}else{
					$c='';
				}
				$navxxx.='<option value="'.$scodecur.'"'.$c.'>'.$scodecur.' - '.$namecur.'</option>';
				if($mcode!=18){
					$dd=$dsql->all("select scode,name from ay_content_sort where pcode=$scodecur order by sorting asc,id asc",1);
					foreach($dd as $xx){
						$scodesub=$xx[scode];
						$namesub=$xx[name];
						if($scodesub==$scode){
							$c=' selected="selected"';
						}else{
							$c='';
						}
						$navxxx.='<option value="'.$scodesub.'"'.$c.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$scodesub.' - '.$namesub.'</option>';
					}
				}
			}
    	}
		$pbstr='mcode,navxxx';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->assign('dbarr',$dbarr);
		$this->display('system/cjedit.html');
    }
	public function add(){
		global $dsql;
		$book=get('book','int');
		$chapter=get('chapter','int');
		$content_00=get('content','int');
		if(!$book && !$chapter && !$content_00){
			error('传递的参数值错误！',-1);
    	}
        if($_POST){
        	if($chapter){
        		$cj=2;
        	}else{
        		$cj=1;
        	}
			$arr=$dsql->all("show fields from ay_697_cj",1);
			foreach($arr as $k => $v){
				$x=$v[Field];
				$xarr=array('cid','cjcur','rule','status');
				if(in_array($x,$xarr)){
					continue;
				}
				if($x=='cj'){
					$xv=$cj;
				}else{
					$xv=post($x);
				}
            	$xv=addslashes($xv);
            	$x01.="$x,";
            	$x02.="'$xv',";
			}
            $x01=rtrim($x01,',');
            $x02=rtrim($x02,',');
			$o=$dsql->amd("insert into ay_697_cj ($x01) values ($x02)");
			if($o){
	            $backurl='?p=/cj/index';
				success('修改成功！',$backurl);
    		}else{
				error('修改失败...',-1);
    		}
        }
    	if($book || $content_00){
			$dd=$dsql->all("select scode,name from ay_content_sort where pcode=0 order by sorting asc,id asc",1);
			foreach($dd as $xx){
				$scodecur=$xx[scode];
				$namecur=$xx[name];
				$navxxx.='<option value="'.$scodecur.'">'.$scodecur.' - '.$namecur.'</option>';
				if($mcode!=18){
					$dd=$dsql->all("select scode,name from ay_content_sort where pcode=$scodecur order by sorting asc,id asc",1);
					foreach($dd as $xx){
						$scodesub=$xx[scode];
						$namesub=$xx[name];
						$navxxx.='<option value="'.$scodesub.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$scodesub.' - '.$namesub.'</option>';
					}
				}
			}
    	}
    	$content=$content_00;
		$pbstr='book,chapter,content,navxxx';
		$pbstr=explode(',',$pbstr);
		foreach($pbstr as $v){
			$pbarr[ $v ]=$$v;
		}
		$this->assign('pbarr',$pbarr);
		$this->display('system/cjadd.html');
	}
	public function rule(){
		global $dsql;
		$down=get('down','int');
		$in=get('in','int');
		if(!$down && !$in){
			error('传递的参数值错误！',-1);
    	}
    	if($_POST){
	    	$rule=post('rule');
    		if($down){
		    	$title='采集规则';
		    	$x=date('YmdHis',time());
		    	$title.='('.$x.')';
		    	downtxt('',$title,$rule);
		    	exit;
		    }
		    if($in){
		    	inrule($rule);
		    }
	    }
		$this->display('system/cjrule.html');
    }
}