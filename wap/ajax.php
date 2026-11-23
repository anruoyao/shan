<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('../core/start.php');
}
require_once('include/abc.seo.php');
require_once('include/wap.fun.php');
$x=$_SERVER['HTTP_USER_AGENT'];
$x=strtolower($x);
if(strstr($x,'spider') || strstr($x,'robot')){
	exit;
}
$my=gpvar('my');
if($my=='home_feature'){
	require_once('../include/reader.fun.php');
	$sh_wap_sex=gpvar('sex');
	if($sh_wap_sex!='male' && $sh_wap_sex!='female'){
		$sh_wap_sex='';
	}
	$sh_wap_where=sh_sex_where($sh_wap_sex);
	$sh_wap_feature_where=$sh_wap_where.' and isrecommend=1';
	$sh_wap_feature_count=sh_book_count($sh_wap_feature_where);
	$sh_wap_feature_max=max(0,$sh_wap_feature_count-4);
	$sh_wap_feature_current=intval(gpvar('offset'));
	$sh_wap_feature_offset=0;
	if($sh_wap_feature_max>0){
		$sh_wap_feature_offset=mt_rand(0,$sh_wap_feature_max);
		if($sh_wap_feature_offset==$sh_wap_feature_current){
			$sh_wap_feature_offset=($sh_wap_feature_offset+1)%($sh_wap_feature_max+1);
		}
	}
	$sh_wap_feature=sh_books($sh_wap_feature_where,'sorting asc,score desc,visits desc,id desc',4,$sh_wap_feature_offset);
	if(!$sh_wap_feature){
		$sh_wap_feature=sh_books($sh_wap_where,'visits desc,id desc',4);
	}
	header('Content-Type: text/html; charset=utf-8');
	include(dirname(__FILE__).'/template/home-feature.html');
	exit;
}
if($my=='mk'){
	http_response_code(410);
	exit('当前为动态模式，无需生成页面');
}
if($my=='sitemap'){
	$xxx=sitemap(1,1);
}
if($my=='jindu'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$xxx=jindu4wap($id,$xid);
}
if($my=='chapte4pg'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$xxx=chapte4pg($id,$xid);
}
if($my=='pgsq'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$uid=gpvar('uid');
	$username=gpvar('username');
	$register_time=gpvar('register_time');
	function pgsq_($id=null,$xid=null,$uid=null,$username=null,$register_time=null){
		global $dsql;
		$time=get_datetime();
		if($uid){
			$x=$dsql->one("select ucode,username,register_time from ay_member where ucode=$uid limit 1",1);
			$uid_2=$x['ucode'];
			$username_2=$x['username'];
			$register_time_2=$x['register_time'];
		}
		if(!$uid || $uid!=$uid_2 || $username!=$username_2 || $register_time!=$register_time_2 || !is_numeric($id)){
			return false;
		}
		if($id && !$xid){
			$x=$dsql->one("select id from ay_member_stow where uid=$uid and id=$id limit 1",1);
			$o=$x['id'];
		}else if($id && $xid && is_numeric($xid)){
			$x=$dsql->one("select id from ay_member_stow where uid=$uid and id=$id and xid=$xid limit 1",1);
			$o=$x['id'];
		}
		if($o){
			$xxx='已收藏';
			$dsql->amd("update ay_member_stow set create_time='$time' where uid=$uid and (id=$id or xid=$xid) limit 1");
		}else{
			if($id && !$xid){
				$ok=$dsql->amd("insert into ay_member_stow (uid,id,xid,create_time) values ('$uid','$id','0','$time')");
			}else if($id && $xid && is_numeric($xid)){
				$x=$dsql->one("select id from ay_member_stow where uid='$uid' and id=$id limit 1",1);
				$o=$x['id'];
				if($o){
					$ok=$dsql->amd("update ay_member_stow set xid=$xid,create_time='$time' where id=$id limit 1");
				}else{
					$ok=$dsql->amd("insert into ay_member_stow (uid,id,xid,create_time) values ('$uid','$id','$xid','$time')");
				}
			}			
			if($ok){
				$dsql->amd("update ay_content set visits=visits+5,update_time='$time',score=score+1 where id=$id limit 1");
				$dsql->amd("update ay_member set score=score+2 where ucode=$uid limit 1");
				$xxx='成功';
			}
		}
		return $xxx;
	}
	$xxx=pgsq_($id,$xid,$uid,$username,$register_time);
}
if($my=='ccbox'){
	$id1=gpvar('id1');
	$id2=gpvar('id2');
	$xxx=SbYoU_neT_comments($id1,$id2,10);
}
if($my=='url2disk'){
	$id=gpvar('id');
	$xxx=url2disk($id);
}
if($my=='url2disknext'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$xxx=url2disknext($id,$xid);
}
if($my=='v' || $my=='l' || $my=='o' || $my=='f'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$uid=gpvar('uid');
	$username=gpvar('username');
	$register_time=gpvar('register_time');
	$xxx_cookie=gpvar('xxx_cookie');
	if($id && $xid!='no'){
		$xxx=vlof($my,$id,$xid,$xxx_cookie,$uid,$username,$register_time);
	}else if($xid=='no'){
		$xxx=true;
	}
	if($my=='v' && $xxx_cookie!='1' && $xxx_cookie!='2'){
		insertComment($id,1);
	}
}
if($my=='axd_free'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$xxx=axd_session($id,$xid);
}
if($my=='hf'){
	$id=gpvar('id');
	$recom=gpvar('recom');
	$re=gpvar('re');
	$go=gpvar('go');
	$pp=gpvar('pp');

	function hf_($id=null,$re=null,$go=null,$recom=null,$pp=null){
		global $dsql,$pcbaseall;
		if(!$id){
			return;
		}
		$rei_d='rei'.'d';
		$uidc=session('pboot_ucode');
		if($uidc){
			$x=$dsql->one("select id from ay_member where ucode=$uidc limit 1",1);
			$uidc=$x[id];
		}
		if($go=='go'){
			if(!$re || !is_numeric($re)){
				return false;
			}
			if($re==$id){
				$re='0';
			}
			$recom=strip_tags($recom);
			$recom=str_replace(array('&','|','&gt;','&lt;','script'),'',$recom);
			$recon=str_replace("'",'\'',$recom);
			$recom_=substr_both($recom,0,255);
			if($recom!=$recom_){
				$recom=$recom_.'...';
			}
			$time=date('Y-m-d H:i:s');
			if($pp){
				$uidc_=session('pboot_ucode');
				$x1="id,re_id,comment,uid,status,update_time";
				$x2="'$id','$re','$recom','$uidc_',1,'$time'";
				$dsql->amd("insert into ay_member_xidcmt_re ($x1) values ($x2)");
			}else{
				$x1="id,re_id,comment,uid,likes,oppose,status,update_time";
				$x2="'$id','$re','$recom','$uidc',0,0,1,'$time'";
				$dsql->amd("insert into ay_member_comment_re ($x1) values ($x2)");
			}
			$xxx='ok';
			return $xxx;
		}else if($go=='del'){
			if($id && $id==$re){
				$dsql->amd("delete from ay_member_comment where id=$re limit 1");
				$dsql->amd("delete from ay_member_comment_re where id=$re");
				$xxx='reload';
			}else{
				$dsql->amd("delete from ay_member_comment_re where $rei_d=$re limit 1");
				$xxx='ok';
			}
			return $xxx;
		}
		if($pp){
			$x=$dsql->one("select * from ay_member_xidcmt where id=$id limit 1",1);
			$comment=$x[comment];
			$uid=$x[uid];
			$update_time=$x[update_time];
			$x=explode(' ',$update_time);
			$update_time=$x[0];
			if($uid){
				$x=$dsql->one("select headpic,nickname,username from ay_member where ucode=$uid limit 1",1);
				$headpic=$x[headpic];
				$nickname=$x[nickname];
				if($headpic){
					$headpic=ico($headpic,1);
				}
				if(!$nickname){
					$nickname=$x[username];
				}
			}
			if(!$headpic){
				$x=rand(1,100);
				$headpic=$pcbaseall."uploads/empty/".$x.".gif";
			}
			if(!$nickname){
				$nickname='游客';
			}
			$nickname0=$nickname;
			$xxx='
			<li>
				<div class="pic"><img src="'.$headpic.'"></div>
				<div class="info"><span id="re_name'.$id.'">'.$nickname.'</span>&nbsp;&nbsp;'.$update_time.'</div>
				<div class="words">
					<p>'.$comment.'</p>
				</div>
			</li>';
			$dd=$dsql->all("select * from ay_member_xidcmt_re where status=1 and id=$id order by $rei_d desc",1);
			foreach($dd as $xx){
				$rei_d_=$xx[ $rei_d ];
				$comment=$xx[comment];
				$uid=$xx[uid];
				$update_time=$xx[update_time];
				$re_id=$xx[re_id];
				$ii++;
				$x=explode(' ',$update_time);
				$update_time=$x[0];
				if($uid){
					$x=$dsql->one("select nickname,headpic,username from ay_member where ucode=$uid limit 1",1);
					$nickname=$x[nickname];
					$headpic=$x[headpic];
					$headpic=ico($headpic,1);
					if(!$nickname){
						$nickname=$x[username];
					}
					$dis='';
				}else{
					$x=rand(1,100);
					$headpic=$pcbaseall."uploads/empty/".$x.".gif";
					$nickname='游客';
					$dis=' style="display:none"';
				}
				if($re_id){
					$x=$dsql->one("select * from ay_member_xidcmt_re where $rei_d=$re_id limit 1",1);
					$comment_=$x[comment];
					$uid_=$x[uid];
					if($uid_){
						$x=$dsql->one("select username,nickname from ay_member where ucode=$uid_ limit 1",1);
						$nickname_=$x[nickname];
						if(!$nickname_){
							$nickname_=$x[username];
						}
						$recom_='<span>@'.$nickname_.'：'.$comment_.'</span>';
					}else{
						$recom_='<span>原回复已删除</span>';
					}
				}else{
					$recom_='';
				}
				if($uidc && $uidc==$uid){
					$del='<div class="redel" id="redel'.$rei_d_.'" onclick="hf('.$id.','.$rei_d_.',\'del\')">x</div>';
				}else{
					$del='';
				}
				$list.='
				<li>
					<div class="pic"><img src="'.$headpic.'"></div>
					<div class="info"><span id="re_name'.$rei_d_.'">'.$nickname.'</span>&nbsp;&nbsp;'.$update_time.'</div>
					<div class="words">
						<p>'.$recom_.$comment.'</p>
					</div>
					<div class="cbtn"'.$dis.'>
						<div class="hf" onclick="hf('.$id.','.$rei_d_.')">回复 &gt;</div>
						'.$del.'
					</div>
				</li>';
			}
			if($ii){
				$ret=$ii.'条回复';
				$allre='
				<style>#ulrec li:nth-child(3),#ulrec li:nth-child(4){border:none}</style>
				<li class="allrec">全部回复</li>';
			}else{
				$ret='暂无回复';
				$allre='<li class="norec">暂无回复</li>';
			}
			$list=$xxx.$allre.$list;
			if(!$uidc){
				$btn='未登录';
			}else{
				$btn='提交回复';
			}
			$xxx='
			<div class="reh">
				<div class="rex" onclick="hf()">x</div>
				<div id="ret">'.$ret.'</div>
			</div>
			<ul class="ulc" id="ulrec">'.$list.'</ul>
			<div class="ulc_bg" id="ulc_bg" onclick="hf('.$id.','.$id.')"></div>
			<div class="recbox" id="recbox">
				<input type="hidden" id="re_id" value="'.$id.'">
				<textarea class="recom" id="recom" placeholder="回复 '.$nickname0.'"></textarea>
				<input type="button" class="recombtn" id="recombtn" value="'.$btn.'" onclick="hf('.$id.',\'\',\'go\')">
			</div>';
			return $xxx;
		}else{
			$x=$dsql->one("select * from ay_member_comment where id=$id limit 1",1);
			$name=$x[name];
			$comment=$x[comment];
			$uid=$x[uid];
			$update_time=$x[update_time];
			$x=explode(' ',$update_time);
			$update_time=$x[0];
			if($uid){
				$x=$dsql->one("select headpic,nickname,username from ay_member where id=$uid limit 1",1);
				$headpic=$x[headpic];
				$nickname=$x[nickname];
				$headpic=ico($headpic,1);
				if(!$nickname){
					$nickname=$x[username];
				}
			}else{
				$x=rand(1,100);
				$headpic=$pcbaseall."uploads/empty/".$x.".gif";
			}
			if(!$nickname){
				$nickname=$name;
			}
			if(!$nickname){
				$xxx='reload';
				return $xxx;
			}
			$nickname0=$nickname;
			$xxx='
			<li>
				<div class="pic"><img src="'.$headpic.'"></div>
				<div class="info"><span id="re_name'.$id.'">'.$nickname.'</span>&nbsp;&nbsp;'.$update_time.'</div>
				<div class="words">
					<p>'.$comment.'</p>
				</div>
			</li>';
			$dd=$dsql->all("select * from ay_member_comment_re where status=1 and id=$id order by $rei_d desc",1);
			foreach($dd as $xx){
				$rei_d_=$xx[ $rei_d ];
				$comment=$xx[comment];
				$uid=$xx[uid];
				$update_time=$xx[update_time];
				$re_id=$xx[re_id];
				$ii++;
				$x=explode(' ',$update_time);
				$update_time=$x[0];
				if($uid){
					$x=$dsql->one("select nickname,headpic,username from ay_member where id=$uid limit 1",1);
					$nickname=$x[nickname];
					$headpic=$x[headpic];
					$headpic=ico($headpic,1);
					if(!$nickname){
						$nickname=$x[username];
					}
					$dis='';
				}else{
					$x=rand(1,100);
					$headpic=$pcbaseall."uploads/empty/".$x.".gif";
					$nickname='游客';
					$dis=' style="display:none"';
				}
				if($re_id){
					$x=$dsql->one("select * from ay_member_comment_re where $rei_d=$re_id limit 1",1);
					$comment_=$x[comment];
					$uid_=$x[uid];
					if($uid_){
						$x=$dsql->one("select username,nickname from ay_member where id=$uid_ limit 1",1);
						$nickname_=$x[nickname];
						if(!$nickname_){
							$nickname_=$x[username];
						}
						$recom_='<span>@'.$nickname_.'：'.$comment_.'</span>';
					}else{
						$recom_='<span>原回复已删除</span>';
					}
				}else{
					$recom_='';
				}
				if($uidc && $uidc==$uid){
					$del='<div class="redel" id="redel'.$rei_d_.'" onclick="hf('.$id.','.$rei_d_.',\'del\')">x</div>';
				}else{
					$del='';
				}
				$list.='
				<li>
					<div class="pic"><img src="'.$headpic.'"></div>
					<div class="info"><span id="re_name'.$rei_d_.'">'.$nickname.'</span>&nbsp;&nbsp;'.$update_time.'</div>
					<div class="words">
						<p>'.$recom_.$comment.'</p>
					</div>
					<div class="cbtn"'.$dis.'>
						<div class="hf" onclick="hf('.$id.','.$rei_d_.')">回复 &gt;</div>
						'.$del.'
					</div>
				</li>';
			}
			if($ii){
				$ret=$ii.'条回复';
				$allre='
				<style>#ulrec li:nth-child(3),#ulrec li:nth-child(4){border:none}</style>
				<li class="allrec">全部回复</li>';
			}else{
				$ret='暂无回复';
				$allre='<li class="norec">暂无回复</li>';
			}
			$list=$xxx.$allre.$list;
			if(!$uidc){
				$btn='未登录';
			}else{
				$btn='提交回复';
			}
			$xxx='
			<div class="reh">
				<div class="rex" onclick="hf()">x</div>
				<div id="ret">'.$ret.'</div>
			</div>
			<ul class="ulc" id="ulrec">'.$list.'</ul>
			<div class="ulc_bg" id="ulc_bg" onclick="hf('.$id.','.$id.')"></div>
			<div class="recbox" id="recbox">
				<input type="hidden" id="re_id" value="'.$id.'">
				<textarea class="recom" id="recom" placeholder="回复 '.$nickname0.'"></textarea>
				<input type="button" class="recombtn" id="recombtn" value="'.$btn.'" onclick="hf('.$id.',\'\',\'go\')">
			</div>';
			return $xxx;
		}
	}

	$xxx=hf_($id,$re,$go,$recom,$pp);
}
if($my=='read_xxx'){
	$xid=gpvar('xid');
	$xxx=read_xxx($xid);
}
if($my=='xxxaxd'){
	$id=gpvar('id');
	$hash=gpvar('hash');
	$more=gpvar('more');
	$xxx=xxxaxd($id,$hash,$more);
}
if($my=='ajaxcontent'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$ajax_content=gpvar('ajax_content');
	$xxx=ajaxcontent($id,$xid,$ajax_content);
}
if($my=='lastread'){
	$id=gpvar('id');
	$cookie=gpvar('cookie');
	$uid=gpvar('uid');
	$username=gpvar('username');
	$register_time=gpvar('register_time');
	$xxx=lastread($id,$cookie,$uid,$username,$register_time);
}
if($my=='sutodychapter'){
	$xid=gpvar('xid');
	$uid=session('pboot_ucode');
	if(!$uid){
		$xxx='login';
	}else{
		$x=$dsql->one("select sutodychapter,jinbi from ay_member where ucode=$uid limit 1",1);
		$sutodychapter=$x[sutodychapter];
		$jinbi=$x[jinbi];
		if(!$xid){
			if($sutodychapter==1){
				$dsql->amd("update ay_member set sutodychapter=0 where ucode=$uid limit 1");
			}else{
				$dsql->amd("update ay_member set sutodychapter=1 where ucode=$uid limit 1");
			}
			$xxx='ok';
		}else{
			$x=$dsql->one("select id,content from ay_article where xid=$xid limit 1",1);
			$id=$x[id];
			$content=$x[content];
			$x=dycontent('jb',$id,$xid,'ajax');
			if(strstr($x,'{697}')){
				$xxx=sbyouNet_Body($xid,$content);
				$xxx=str_replace(array('<div id="bodybox">','</div>'),'',$xxx);
			    if($sutodychapter){
			        $c=' checked="checked"';
			        $k='已开启';
			        if($tip_){
			            $sutodychapter_js='<script>sutodychapter('.$xid.');</script>';
			        }
			    }else{
			        $c='';
			        $k='已关闭';
			    }
				$xxx.='
			    <p class="sutodychapter">
			        自动连续订阅章节：
			        <label id="sutodychapter" for="ck1" onclick="sutodychapter()"><input id="ck1" type="checkbox" style="position:relative;top:1px"'.$c.'> '.$k.'</label>
			    </p>';
			}else if($x=='login'){
				$xxx=$x;
			}else if($x=='pay'){
				$xxx=$x;
				$url=pbcode('member','pay','content_'.$id.'_'.$xid,'all');
				$xxx.=','.$url;
			}else{
				$xxx='';
			}
		}
	}
}
if($my=='xidcmt'){
	$xid=gpvar('xid');
	$idx=gpvar('idx');
	$dd=$dsql->all("select * from ay_member_xidcmt where xid=$xid and idx=$idx order by id desc",1);
	foreach($dd as $xx){
		$id_=$xx[id];
		$comment=$xx[comment];
		$uid=$xx[uid];
		$update_time=$xx[update_time];
		$x=explode(' ',$update_time);
		$update_time=$x[0];
		if($update_time=='0000-00-00'){
			continue;
		}
		$x=$dsql->one("select username,nickname,headpic from ay_member where ucode=$uid limit 1",1);
		$username=$x[username];
		$nickname=$x[nickname];
		$headpic=$x[headpic];
		$headpic=ico($headpic,1);
		if(!$nickname){
			$nickname=$username;
		}
		if(!$nickname){
			$nickname='游客';
		}
		$comment=strip_tags($comment);
		$comment=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$comment);


		$recom_='';
		$x=$dsql->one("select count(*) from ay_member_xidcmt_re where status=1 and id=$id_",1);
		$count=$x['count(*)'];
		if(!$count){
			$count='';
		}else{
			$x=$dsql->one("select * from ay_member_xidcmt_re where status=1 and id=$id_ and re_id=0 order by reid desc limit 1",1);
			$comment_=$x[comment];
			$uid_=$x[uid];
			if($uid_){
				$x=$dsql->one("select username,nickname from ay_member where ucode=$uid_ limit 1",1);
				$nickname_=$x[nickname];
				if($nickname_){
					$nickname_=$x[username];
				}
			}
			if(!$nickname_){
				$nickname_='游客';
			}
			if($count>1){
				$ck='<font class="f2" onclick="hf('.$id_.')">查看全部'.$count.'条回复</font>';
			}else{
				$ck='';
			}
			$recom_='<span><font>'.$nickname_.'</font>：'.$comment_.$ck.'</span>';
		}


		$xxx.='
        <li>
            <div class="pic"><img src="'.$headpic.'"></div>
            <div class="info">
	            '.$nickname.'
                &nbsp;
                '.$update_time.'
            </div>
            <div class="words">
                <p>'.$comment.'</p>
            </div>
			<div class="cbtn">
			  <div class="hf" onclick="hf('.$id_.')">'.$count.'回复 &gt;</div>
			  '.$del.'
			</div>
			<div class="words words2">
				<p>'.$recom_.'</p>
			</div>
        </li>';
	}
	if(!$xxx){
		$xxx='<center style="padding:50px 0px">暂无评论</center>';
	}
}
if($my=='idxcmt'){
	$uid=session('pboot_ucode');
	$xid=gpvar('xid');
	$idx=gpvar('idx');
	$comment=gpvar('comment');
	$checkcode=gpvar('checkcode');
	$url=pbcode('article',$xid).'#xidcmt_'.$xid.'_'.$idx;
	if(!$uid){
		$x='抱歉，登录会员才能评论哦！';
		error($x,$url,3);
	}
	if(!$xid || !$idx){
		$x='参数有误！';
		error($x,$url,3);
	}
	if(!$comment){
		$x='请输入你要评论的内容！';
		error($x,$url,3);
	}
	$c=session('xidcmt_'.$xid.'_'.$idx);
	$today=date('Ymd');
	if($c==$today){
		// $x='这个段落今天您已经评论过啦！<br>';
		// success($x,$url,3);
	}
	$comment=strip_tags($comment);
	$comment=str_replace(array(PHP_EOL,"	"," ","　","\t","\n","\r"),"",$comment);
	$update_time=get_datetime();
	$x01="xid,idx,comment,uid,update_time";
	$x02="'$xid','$idx','$comment','$uid','$update_time'";
	$o=$dsql->amd("insert into ay_member_xidcmt ($x01) values ($x02)");
	if($o){
		session('xidcmt_'.$xid.'_'.$idx,$today);
		$x='恭喜，已经成功评论啦！';
		success($x,$url,3);
	}else{
		$x='抱歉，评论失败！';
		error($x,$url,3);
	}
}
echo $xxx;
?>
