<?php
if(!isset($dsql)){
	define('IS_INDEX',true);
	define('URL_BIND','pbcode');
	define('PB_FILE',$_SERVER['SCRIPT_FILENAME']);
	require_once('core/start.php');
}
$x=$_SERVER['HTTP_USER_AGENT'];
$x=strtolower($x);
if(strstr($x,'spider') || strstr($x,'robot')){
	exit;
}
$my=gpvar('my');
if($my=='mk'){
	http_response_code(410);
	exit('当前为动态模式，无需生成页面');
}
if($my=='sitemap'){
	sitemap(1);
}
if($my=='bwjp' || $my=='randBOX'){
	$id1=gpvar('id1');
	$id2=gpvar('id2');
	$xxx=SByou_Net_rand($my,$id1,$id2);
}
if($my=='NewAdmin'){
	$num=gpvar('id1');
	$xxx=sbyou_NET_newList(10);
}
if($my=='SingleMid' || $my=='SingleCookie'){
	$cookie=gpvar('cookie');
	$uid=gpvar('uid');
	$username=gpvar('username');
	$register_time=gpvar('register_time');
	$xxx=xxx_SingleReaded($my,$cookie,$uid,$username,$register_time);
}
if($my=='MoreMid' || $my=='MoreCookie'){
	$cookie=gpvar('cookie');
	$uid=gpvar('uid');
	$username=gpvar('username');
	$register_time=gpvar('register_time');
	$xxx=xxx_MoreReaded($my,$cookie,$uid,$username,$register_time);
}
if($my=='NumCoMain'){
	$nid=gpvar('nid');
	$mcodeid=gpvar('mcodeid');
	$obj=gpvar('obj');
	$xxx=SByou_Net_NumCoMain($nid,$mcodeid,$obj);
}
if($my=='jindu'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$xxx=jindu($id,$xid);
}
if($my=='ccbox'){
	$id1=gpvar('id1');
	$id2=gpvar('id2');
	$xxx=SbYoU_neT_comments($id1,$id2,10);
}
if($my=='commentRand'){
	$id=gpvar('id');
	$num=gpvar('num');
	$xxx=insertComment($id,$num);
}
if($my=='url2disk'){
	$xid=gpvar('xid');
	$xxx=url2disk($xid);
}
if($my=='url2disknext'){
	$id=gpvar('id');
	$xid=gpvar('xid');
	$xxx=url2disknext($id,$xid);
}
if($my=='maketxt'){
	$scode=gpvar('scode');
	$id=gpvar('id');
	$txtid=gpvar('txtid');
	$total=gpvar('total');
	$filename=gpvar('filename');
	$xxx=maketxt($scode,$id,$txtid,$total,$filename);
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
	$xxx='<ul class="ulc">'.$xxx.'</ul>';
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
if($my=='lang'){
	$lang_xx=gpvar('lang_xx');
	$href=gpvar('href');
	$xxx=langbtn($lang_xx,$href);
}else{
	if($lang!='cn'){
		$xxx=lang_content($xxx,$lang);
	}
}
echo $xxx;
?>
