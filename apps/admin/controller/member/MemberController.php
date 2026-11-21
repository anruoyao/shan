<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2019年10月05日
 *  会员控制器
 */
namespace app\admin\controller\member;

use core\basic\Controller;
use app\admin\model\member\MemberModel;

class MemberController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new MemberModel();
    }

    // 会员列表
    public function index()
    {
        if ((! ! $id = get('id', 'int')) && $result = $this->model->getMember($id)) {
            $this->assign('more', true);
            $this->assign('fields', $this->model->getFields());
            $this->assign('member', $result);
        } else {
            $this->assign('list', true);
            if (! ! ($field = get('field', 'var')) && ! ! ($keyword = get('keyword', 'vars'))) {
                $result = $this->model->findMember($field, $keyword);
            } else {
                $result = $this->model->getList();
            }
            
            // 会员等级
            $this->assign('groups', model('admin.member.MemberGroup')->getSelect());
            $this->assign('members', $result);
        }
        $this->display('member/member.html');
    }

    // 会员增加
    public function add()
    {
        if ($_POST) {
            // 获取数据
            $ucode = get_auto_code($this->model->getLastCode(), 1);
            $username = post('username');
            $useremail = post('useremail');
            $usermobile = post('usermobile');
            $nickname = post('nickname');
            $password = post('password');
            $headpic = post('headpic');
            $status = post('status') ?: 1;
            $gid = post('gid');
            $score = post('score');
            $register_time = get_datetime();
            
            if (! $username) {
                alert_back('用户账号不能为空！');
            }
            
            if (! preg_match('/^[\w\@\.]+$/', $username)) {
                //alert_back('用户账号含有不允许的特殊字符！');
            }
            
            if ($useremail && ! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                alert_back('邮箱账号格式不正确！');
            }
            
            if ($usermobile && ! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                alert_back('手机号码格式不正确！');
            }
            
            if ($username && $this->model->checkMember("username='$username' OR useremail='$username' OR usermobile='$username'")) {
                alert_back('用户名已经存在！');
            }
            
            if ($useremail && $this->model->checkMember("username='$useremail' OR useremail='$useremail' OR usermobile='$useremail'")) {
                alert_back('邮箱账号已经存在！');
            }
            
            if ($usermobile && $this->model->checkMember("username='$usermobile' OR useremail='$usermobile' OR usermobile='$usermobile'")) {
                alert_back('手机号码已经存在！');
            }
            
            if (! $password) {
                alert_back('密码不能为空！');
            } else {
                $password = md5(md5($password));
            }
            
            // 构建数据
            $data = array(
                'ucode' => $ucode,
                'username' => $username,
                'useremail' => $useremail,
                'usermobile' => $usermobile,
                'nickname' => $nickname,
                'password' => $password,
                'headpic' => $headpic,
                'status' => $status,
                'gid' => $gid,
                'wxid' => '',
                'qqid' => '',
                'wbid' => '',
                'activation' => 1,
                'score' => $score,
                'register_time' => $register_time,
                'login_count' => 0,
                'last_login_ip' => 0,
                'last_login_time' => 0
            );
            
            // 执行添加
            if ($this->model->addMember($data)) {
                $this->log('新增会员成功！');
                if (! ! $backurl = get('backurl')) {
                    success('新增成功！', base64_decode($backurl));
                } else {
                    success('新增成功！', url('/admin/Member/index'));
                }
            } else {
                $this->log('新增会员失败！');
                error('新增失败！', - 1);
            }
        }
    }

    // 会员删除
    public function del()
    {
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                if ($this->model->delMemberList($list)) {
                    $this->log('批量删除会员成功！');
                    success('删除成功！', - 1);
                } else {
                    $this->log('批量删除会员失败！');
                    error('删除失败！', - 1);
                }
            } else {
                alert_back('请选择要删除的会员！');
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delMember($id)) {
            $this->log('删除会员' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除会员' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 会员修改
    public function mod()
    {
        if (! ! $submit = post('submit')) {
            switch ($submit) {
                case 'verify1':
                    $list = post('list');
                    if (! $list) {
                        alert_back('请选择要操作的会员！');
                    }
                    if ($this->model->modMemberList($list, "status=1")) {
                        $this->log('会员批量启用成功！');
                        success('启用成功！', - 1);
                    } else {
                        alert_back('启用失败！');
                    }
                    break;
                case 'verify0':
                    $list = post('list');
                    if (! $list) {
                        alert_back('请选择要操作的会员！');
                    }
                    if ($this->model->modMemberList($list, "status=0")) {
                        $this->log('会员批量禁用成功！');
                        success('禁用成功！', - 1);
                    } else {
                        alert_back('禁用失败！');
                    }
                    break;
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modMember($id, "$field='$value'")) {
                location(- 1);
            } else {
                alert_back('修改失败！');
            }
        }
        
        // 修改操作
        if ($_POST) {
            
            // 获取数据
            $username = post('username');
            $useremail = post('useremail');
            $usermobile = post('usermobile');
            $nickname = post('nickname');
            $password = post('password');
            $headpic = post('headpic');
            $status = post('status')?1:0;
            $gid = post('gid');
            $score = post('score');
            $jinbi = post('jinbi');
            $validdate = post('validdate');
            
            if (! $username) {
                alert_back('用户账号不能为空！');
            }
            
            if (! preg_match('/^[\w\@\.]+$/', $username)) {
                //alert_back('用户账号含有不允许的特殊字符！');
            }
            
            if ($useremail && ! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                alert_back('邮箱账号格式不正确！');
            }
            
            if ($usermobile && ! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                alert_back('手机号码格式不正确！');
            }
            
            if ($username && $this->model->checkMember("(username='$username' OR useremail='$username' OR usermobile='$username') AND id<>$id")) {
                alert_back('用户名已经存在！');
            }
            
            if ($useremail && $this->model->checkMember("(username='$useremail' OR useremail='$useremail' OR usermobile='$useremail') AND id<>$id")) {
                alert_back('邮箱账号已经存在！');
            }
            
            if ($usermobile && $this->model->checkMember("(username='$usermobile' OR useremail='$usermobile' OR usermobile='$usermobile') AND id<>$id")) {
                alert_back('手机号码已经存在！');
            }
            
            // 构建数据
            $data = array(
                'username' => $username,
                'useremail' => $useremail,
                'usermobile' => $usermobile,
                'nickname' => $nickname,
                'headpic' => $headpic,
                'status' => $status,
                'gid' => $gid,
                'score' => $score,
                'jinbi' => $jinbi,
                'validdate' => $validdate
            );
            
            if ($password) {
                $data['password'] = md5(md5($password));
            }
            
            // 执行添加
            if ($this->model->modMember($id, $data)) {
                $this->log('修改会员成功！');
                if (! ! $backurl = get('backurl')) {
                    success('修改成功！', base64_decode($backurl));
                } else {
                    success('修改成功！', url('/admin/Member/index'));
                }
            } else {
                $this->log('修改会员失败！');
                error('修改失败！', - 1);
            }
        } else {
            // 调取修改内容
            $this->assign('mod', true);
            if (! $result = $this->model->getMember($id)) {
                error('编辑的内容已经不存在！', - 1);
            }
            $x=$result->validdate;
            if($x=='0000-00-00 00:00:00'){
                $x=$result->register_time;
                global $dsql;
                $dsql->amd("update ay_member set validdate='$x' where ucode=$id limit 1");
                $result->validdate=$x;
            }
            // 会员等级
            $this->assign('groups', model('admin.member.MemberGroup')->getSelect());
            $this->assign('member', $result);
            $this->display('member/member.html');
        }
    }

    public function author()
    {
        global $dsql;
        $OnePAGE_NUM=50;
        $uid=get('uid','int');
        $page=get('page','int');
        $status=gpvar('status');
        $sign=gpvar('sign');
        if($uid){
            $x=$dsql->one("select username from ay_member where ucode=$uid limit 1",1);
            $username=$x[username];
            $time=time();
            $m=date('Y-m',$time);
            $e=date('d',$time);
            for($i=$e;$i>=1;$i--){
                $c=$m.'-'.$i;
                $c=strtotime($c);
                $x=$dsql->one("select * from ay_member_author where uid=$uid and create_time='$c' limit 1",1);
                $chapter=$x[chapter];
                $words=$x[words];
                if(!$chapter){
                    $chapter=0;
                }
                if(!$words){
                    $words=0;
                }
                $list.='
                <tr>
                    <td>'.date('Y-m-d',$c).'</td>
                    <td>'.$chapter.'</td>
                    <td>'.$words.'</td>
                </tr>';
            }
        }else if($status){
            $arr=explode('_',$status);
            $uidcur=$arr[0];
            $status=$arr[1];
            if(!$uidcur || !is_numeric($uidcur) || ($status!='0' && $status!='1')){
                $x='数据有误！';
                $url='?p=/Member/author';
                error($x,$url,3);
            }
            $dsql->amd("update ay_member set status=$status where ucode=$uidcur limit 1");
            $url='?p=/Member/author';
            location($url);
        }else if($sign){
            $arr=explode('_',$sign);
            $uidcur=$arr[0];
            $sign=$arr[1];
            if(!$uidcur || !is_numeric($uidcur) || !$sign || !is_numeric($sign)){
                $x='数据有误！';
                $url='?p=/Member/author';
                error($x,$url,3);
            }
            $dsql->amd("update ay_member set sign=$sign where ucode=$uidcur limit 1");
            $url='?p=/Member/author';
            location($url);
        }else{
            $x=$dsql->one("select count(*) from ay_member where sign!=0",1);
            $total=$x["count(*)"];
            $pagenum=ceil($total/$OnePAGE_NUM);
            $page?'':$page='1';
            $page=min($pagenum,$page);!$page?$page=1:"";
            $prepg=$page-1;
            $nextpg=($page==$pagenum?0:$page+1);
            $offset=($page-1)*$OnePAGE_NUM;
            $dd=$dsql->all("select ucode,status,username,nickname,register_time,last_login_time,score,jinbi,sign from ay_member where sign!=0 order by id desc limit $offset,$OnePAGE_NUM",1);
            foreach($dd as $xx){
                $ucode=$xx[ucode];
                $status=$xx[status];
                $username=$xx[username];
                $nickname=$xx[nickname];
                $register_time=$xx[register_time];
                $x=explode(' ',$register_time);
                $register_time=$x[0];
                $last_login_time=$xx[last_login_time];
                $score=$xx[score];
                $jinbi=$xx[jinbi];
                $sign=$xx[sign];
                $x=$dsql->one("select count(*) from ay_content where uid=$ucode",1);
                $artnum=$x['count(*)'];
                if(!$artnum){
                    $artnum=0;
                }
                if($status==1){
                    $status='<a href="?p=/Member/author/status/'.$ucode.'_0"><i class="fa fa-toggle-on"></i></a>';
                }else{
                    $status='<a href="?p=/Member/author/status/'.$ucode.'_1"><i class="fa fa-toggle-off"></i></a>';
                }
                if($sign!=1){
                    $zzsh='<a href="?p=/Member/author/sign/'.$ucode.'_1"><i class="fa fa-toggle-on"></i></a>';
                    if($sign==3){
                        $zzqy='<a href="?p=/Member/author/sign/'.$ucode.'_2"><i class="fa fa-toggle-on"></i></a>';
                    }else{
                        $zzqy='<a href="?p=/Member/author/sign/'.$ucode.'_3"><i class="fa fa-toggle-off"></i></a>';
                        if($sign==4){
                            $zzqy.='<span style="position:relative;top:-3px;color:red">（申请中）</span>';
                        }
                    }
                }else{
                    $zzsh='<a href="?p=/Member/author/sign/'.$ucode.'_2"><i class="fa fa-toggle-off"></i></a>';
                    $zzqy='<a href="?p=/Member/author/sign/'.$ucode.'_3"><i class="fa fa-toggle-off"></i></a>';
                }
                $x=$dsql->one("select id from ay_member where ucode=$ucode limit 1",1);
                $id=$x[id];
                $list.='
                <tr>
                    <td>'.$ucode.'</td>
                    <td><a href="?p=/Member/index/id/'.$id.'&backurl='.base64_encode('?p=/Member/author').'" style="text-decoration:underline">'.$username.'</a></td>
                    <td>'.$nickname.'</td>
                    <td>'.$status.'</td>
                    <td>'.$zzsh.'</td>
                    <td>'.$zzqy.'</td>
                    <td>'.$score.'/'.$jinbi.'</td>
                    <td>'.$register_time.'</td>
                    <td>'.$last_login_time.'</td>
                    <td><a href="?p=/Sort/index/mode/4/uid/'.$ucode.'" class="layui-btn layui-btn-xs">作品（'.$artnum.'）</a></td>
                </tr>';
            }
            $pagebar=pagebox('author',$total,$pagenum,$page,$prepg,$nextpg);
        }
        $this->assign('uid',$uid);
        $this->assign('list',$list);
        $this->assign('pagebar',$pagebar);
        $this->assign('username',$username);
        $this->display('member/author.html');
    }

    public function tx()
    {
        global $dsql;
        $OnePAGE_NUM=50;
        $page=get('page','int');
        $status=gpvar('status');
        $pay=gpvar('pay');
        $uid=gpvar('uid');
        $tip=gpvar('tip');
        if($status){
            $arr=explode('_',$status);
            $idx=$arr[0];
            $status=$arr[1];
            if(!$idx || !is_numeric($idx) || ($status!='0' && $status!='1')){
                $x='数据有误！';
                $url='?p=/Member/tx';
                error($x,$url,3);
            }
            $o=$dsql->amd("update ay_member_money set status=$status where idx=$idx limit 1");
            if($o){
                $x=$dsql->one("select uid,jb from ay_member_money where idx=$idx limit 1",1);
                $uid=$x[uid];
                $jb=$x[jb];
                if($status=='0'){
                    $o=$dsql->amd("update ay_member set jinbi=jinbi+$jb where ucode=$uid limit 1");
                }else{
                    $o=$dsql->amd("update ay_member set jinbi=jinbi-$jb where ucode=$uid limit 1");
                }
                if(!$o){
                    if($status=='0'){
                        $dsql->amd("update ay_member_money set status=1 where idx=$idx limit 1");
                    }else{
                        $dsql->amd("update ay_member_money set status=0 where idx=$idx limit 1");
                    }
                }
            }
            $url='?p=/Member/tx';
            location($url);
        }else if($pay){
            $arr=explode('_',$pay);
            $idx=$arr[0];
            $pay=$arr[1];
            if(!$idx || !is_numeric($idx) || ($pay!='0' && $pay!='1' && $pay!='2')){
                $x='数据有误！';
                $url='?p=/Member/tx';
                error($x,$url,3);
            }
            $x=$dsql->one("select status from ay_member_money where idx=$idx limit 1",1);
            $status=$x[status];
            if(!$status){
                $x='提现申请已被用户取消！';
                $url='?p=/Member/tx';
                error($x,$url,3);
            }
            $dsql->amd("update ay_member_money set pay=$pay where idx=$idx limit 1");
            $url='?p=/Member/tx';
            location($url);
        }else if($uid){
            $arr=explode('_',$uid);
            $uidcur=$arr[0];
            $status=$arr[1];
            if(!$uidcur || !is_numeric($uidcur) || ($status!='0' && $status!='1')){
                $x='数据有误！';
                $url='?p=/Member/tx';
                error($x,$url,3);
            }
            $dsql->amd("update ay_member set status=$status where ucode=$uidcur limit 1");
            $url='?p=/Member/tx';
            location($url);
        }else if($tip && is_numeric($tip)){
            if ($_POST) {
                $tip_=gpvar('tip_');
                $tip_=strip_tags($tip_);
                $tip_=trim($tip_);
                $tip_=str_replace("'","\'",$tip_);
                $dsql->amd("update ay_member_money set tip='$tip_' where idx=$tip limit 1");
                $x='提成成功！';
                $url='?p=/Member/tx';
                success($x,$url,3);
            }
            $dtarr=$dsql->one("select * from ay_member_money where idx=$tip limit 1",1);
            $id=$dtarr[id];
            $id=str_replace('{697}','、',$id);
            $dtarr[id]=$id;
            $this->assign('dtarr',$dtarr);
            $this->display('member/tx_tip.html');
        }else{
            $x=$dsql->one("select count(*) from ay_member_money where lx=5",1);
            $total=$x["count(*)"];
            $pagenum=ceil($total/$OnePAGE_NUM);
            $page?'':$page='1';
            $page=min($pagenum,$page);!$page?$page=1:"";
            $prepg=$page-1;
            $nextpg=($page==$pagenum?0:$page+1);
            $offset=($page-1)*$OnePAGE_NUM;
            $dd=$dsql->all("select idx,uid,status,ding,je,jb,pay,id,create_time,tip from ay_member_money where lx=5 order by idx desc limit $offset,$OnePAGE_NUM",1);
            foreach($dd as $xx){
                $idx=$xx[idx];
                $uid=$xx[uid];
                $status=$xx[status];
                $ding=$xx[ding];
                $je=$xx[je];
                $jb=$xx[jb];
                $pay=$xx[pay];
                $payxx=$xx[id];
                $create_time=$xx[create_time];
                $tip=$xx[tip];
                if($pay==1){
                    $pay='<a href="?p=/Member/tx/pay/'.$idx.'_0"><i class="fa fa-toggle-on"></i></a>';
                    $yichang='<a href="?p=/Member/tx/pay/'.$idx.'_2"><i class="fa fa-toggle-off"></i></a>';
                }else{
                    if($pay==2){
                        $yichang='<a href="?p=/Member/tx/pay/'.$idx.'_1"><i class="fa fa-toggle-on"></i></a>';
                    }else{
                        $yichang='<a href="?p=/Member/tx/pay/'.$idx.'_2"><i class="fa fa-toggle-off"></i></a>';
                    }
                    $pay='<a href="?p=/Member/tx/pay/'.$idx.'_1"><i class="fa fa-toggle-off"></i></a>';
                    if($status==1){
                        $pay.='<span style="position:relative;top:-3px;color:red">（待处理）</span>';
                    }
                }
                if($status==1){
                    $status='<a href="?p=/Member/tx/status/'.$idx.'_0"><i class="fa fa-toggle-on"></i></a>';
                }else{
                    $status='<a href="?p=/Member/tx/status/'.$idx.'_1"><i class="fa fa-toggle-off"></i></a>';
                }
                $x=$dsql->one("select id,status,username,jinbi from ay_member where ucode=$uid limit 1",1);
                $id=$x[id];
                $status2=$x[status];
                $username=$x[username];
                $jinbi=$x[jinbi];
                if($status2==1){
                    $status2='<a href="?p=/Member/tx/uid/'.$uid.'_0"><i class="fa fa-toggle-on"></i></a>';
                }else{
                    $status2='<a href="?p=/Member/tx/uid/'.$uid.'_1"><i class="fa fa-toggle-off"></i></a>';
                }
                $arr=explode('{697}',$payxx);
                $pay01=$arr[0];
                $pay02=$arr[1];
                if(!$pay01 || !$pay02){
                    $zh='<span style="position:relative;top:-3px;color:red">（异常，未填账户）</span>';
                }else{
                    $zh='<font class="fxx">'.$pay01.'</font><font class="fxx">'.$pay02.'</font>';
                }
                $tip='<a href="?p=/Member/tx/tip/'.$idx.'" class="layui-btn layui-btn-xs">添加/编辑</a><br><span style="padding:5px">'.$tip.'</span>';
                $list.='
                <tr>
                    <td>'.$idx.'</td>
                    <td>'.$ding.'</td>
                    <td>'.$status.'</td>
                    <td><a href="?p=/Member/mod/id/'.$id.'&backurl='.base64_encode('?p=/Member/tx').'" style="text-decoration:underline">'.$username.'</a></td>
                    <td>'.$status2.'</td>
                    <td>'.$jinbi.'</td>
                    <td>'.$jb.'</td>
                    <td>'.$je.'</td>
                    <td>'.$zh.'</td>
                    <td>'.$pay.'</td>
                    <td>'.$yichang.'</td>
                    <td>'.$create_time.'</td>
                    <td>'.$tip.'</td>
                </tr>';
            }
            $pagebar=pagebox('tx',$total,$pagenum,$page,$prepg,$nextpg);
        }
        $list.='
        <style>
        .fxx{float:left;width:100%;height:16px;line-height:16px;overflow:hidden;}
        .fxx:first-child{margin-top:1px;}
        </style>';
        $this->assign('list',$list);
        $this->assign('pagebar',$pagebar);
        $this->display('member/tx.html');
    }

    public function order()
    {
        global $dsql;
        $OnePAGE_NUM=50;
        $page=get('page','int');

        $key=gpvar('key');
        $del=gpvar('del');

        $payok=gpvar('payok');

        if($del && is_numeric($del)){
            $dsql->amd("delete from ay_member_money where idx=$del limit 1");
            $x='成功删除！';
            success($x,-1,3);
        }

        if ($_POST && !$key) {
            $list=post('list');
            if($list){
                foreach($list as $k => $v){
                    if(!$v || !is_numeric($v)){
                        continue;
                    }
                    $o=$dsql->amd("delete from ay_member_money where idx=$v limit 1");
                }
                if($o){
                    success('删除成功！',-1);
                }else{
                    error('部分项目删除失败...',-1);
                }
            }else{
                alert_back('请选择要删除的项目！');
            }
        }

        $dd=$dsql->all("select ucode from ay_member where username like '%$key%'",1);
        foreach($dd as $xx){
            $uid=$xx[ucode];
            $uidxxx.=$uid.',';
        }
        $uidxxx=trim($uidxxx,',');
        if($uidxxx){
            $uidxxx="or uid in($uidxxx)";
        }
        if($key){
            $OnePAGE_NUM=5000;
            $sql="where (ding like '%$key%' $uidxxx)";
        }

        if($payok){
            if($sql){
                $sql_=$sql." and pay=1";
            }else{
                $sql_="where pay=1";
            }
        }else{
            $sql_=$sql;
        }

        $x=$dsql->one("select count(*) from ay_member_money $sql_",1);
        $total=$x["count(*)"];
        $pagenum=ceil($total/$OnePAGE_NUM);
        $page?'':$page='1';
        $page=min($pagenum,$page);!$page?$page=1:"";
        $prepg=$page-1;
        $nextpg=($page==$pagenum?0:$page+1);
        $offset=($page-1)*$OnePAGE_NUM;
        $dd=$dsql->all("select * from ay_member_money $sql_ order by idx desc limit $offset,$OnePAGE_NUM",1);
        foreach($dd as $xx){
            $idx=$xx[idx];
            $uid=$xx[uid];
            $status=$xx[status];
            $ding=$xx[ding];
            $je=$xx[je];
            $jb=$xx[jb];
            $lx=$xx[lx];
            $pay=$xx[pay];
            $create_time=$xx[create_time];
            $x=$dsql->one("select username from ay_member where ucode=$uid limit 1",1);
            $username=$x[username];
            if($lx==1){
                $lx='微信';
            }else if($lx==2){
                $lx='支付宝';
            }else if($lx==5){
                $lx='PayPal';
            }
            if($pay==1){
                $pay='<font style="color:red">已付款</font>';
            }else{
                $pay='待付款';
            }
            $x=$idx.','.$ding;
            $md5=md5($x);
            $list.='
            <tr>
                <td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$idx.'"></td>
                <td>'.$idx.'</td>
                <td>'.$username.'</td>
                <td ondblclick="edui_(\''.$ding.'\',\''.$md5.'\')">'.$ding.'</td>
                <td>'.$je.'</td>
                <td>'.$pay.'</td>
                <td>'.$lx.'</td>
                <td>'.$create_time.'</td>
                <td>
                    <a href="?p=/Member/order/del/'.$idx.'" onclick="return del(1)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                </td>
            </tr>';
        }
        $pagebar=pagebox('order',$total,$pagenum,$page,$prepg,$nextpg);


        $x=$dsql->one("select sum(je) from ay_member_money $sql",1);
        $je_all=$x['sum(je)'];
        if(!$je_all){
            $je_all=0;
        }
        if($sql){
            $sql.=" and pay=1";
        }else{
            $sql="where pay=1";
        }
        $x=$dsql->one("select sum(je) from ay_member_money $sql",1);
        $je_1=$x['sum(je)'];
        if(!$je_1){
            $je_1=0;
        }

        $je_all='<a style="color:blue;text-decoration:underline" href="?p=/Member/order&key='.$key.'">'.$je_all.'</a>';
        $je_1='<a style="color:blue;text-decoration:underline" href="?p=/Member/order&key='.$key.'&payok=1">'.$je_1.'</a>';

        $tip='当前列表，总金额：'.$je_all.'，已成功支付：'.$je_1;
        if($key && !$uidxxx){
            $tip='';
        }else if($key && $uidxxx){
            $tip=str_replace('当前列表','当前列表（'.$key.'）',$tip);
        }

        $this->assign('tip',$tip);


        $this->assign('key',$key);
        $this->assign('list',$list);
        $this->assign('pagebar',$pagebar);
        $this->display('member/order.html');
    }
    
    public function mka()
    {
        global $dsql,$pay_ka;
        $ucode=gpvar('ucode');
        $id=gpvar('id');
        $x=$dsql->one("select * from ay_member where ucode=$ucode limit 1",1);
        $mka=$x[mka];
        if($mka && $mka==$id){
            $dsql->amd("update ay_member set mka=0,mka_date='' where ucode=$ucode limit 1");
        }else{
            $t1=time();
            $arr=explode(',',$pay_ka);
            foreach($arr as $xx){
                $ii++;
                $arr_=explode('-',$xx);
                $x1=$arr_[0];
                $x2=$arr_[1];
                $x3=$arr_[2];
                if($ii==$id){
                    $x=$x2*24*60*60;
                    $t2=$t1+$x;
                    $mka_date=$t1.','.$t2;
                    break;
                }
            }
            if(!$mka_date){
                $mka_date=$t1.','.$t1;
            }
            $dsql->amd("update ay_member set mka=$id,mka_date='$mka_date' where ucode=$ucode limit 1");
        }
        $x='修改成功！';
        success($x,-1,3);
    }
    
    public function addnew()
    {
        global $dsql,$pay_ka;
        if($_POST){
            $num=gpvar('num');
            $ka=gpvar('ka');
            $status=gpvar('status');
            if(!$num || !is_numeric($num)){
                $x='数据有误！';
                $url='?p=/Member/addnew';
                error($x,$url,3);
            }
            if($ka){
                $arr=explode(',',$pay_ka);
                $x=$arr[$ka-1];
                if($x){
                    $x=explode('-',$x);
                    $d=$x[1];
                }
                if($d){
                    $time1=time();
                    $t=$d*24*60*60;
                    $time2=$time1+$t;
                    $mka=$ka;
                    $mka_date=$time1.','.$time2;
                }
            }
            for($i=1;$i<=$num;$i++){
                $x=randnum(8);
                $username=strtolower($x);
                $pwd=md5(md5($username));
                $x=$dsql->one("select ucode from ay_member order by ucode desc limit 1",1);
                $ucode=$x[ucode];
                if($ucode<5){
                    $ucode=5;
                }else{
                    $ucode=$ucode+1;
                }
                $headpic='/uploads/empty/'.rand(1,100).'.gif';
                $x=strtotime('+100 year');
                $validdate=date('Y-m-d H:i:s',$x);
                // 构建数据
                $data=array(
                    'ucode' => $ucode,
                    'username' => $username,
                    'useremail' => '',
                    'usermobile' => '',
                    'nickname' => $username,
                    'password' => $pwd,
                    'headpic' => $headpic,
                    'status' => $status,
                    'gid' => 1,
                    'wxid' => '',
                    'qqid' => '',
                    'wbid' => '',
                    'activation' => 1,
                    'score' => 0,
                    'register_time' => get_datetime(),
                    'validdate' => $validdate,
                    'login_count' => 0,
                    'last_login_ip' => 0,
                    'last_login_time' => 0,
                    'mka' => $mka,
                    'mka_date' => $mka_date
                );
                foreach($data as $k => $v){
                    $x1.=$k.',';
                    $x2.="'$v',";
                }
                $x1=trim($x1,',');
                $x2=trim($x2,',');
                $dsql->amd("insert into ay_member ($x1) values ($x2)");
                $x1='';
                $x2='';
            }
            $x='新增成功！';
            $url='?p=/Member/index';
            success($x,$url,3);
        }
        $this->display('member/addnew.html');
    }
}
function pagebox($my,$total,$pagenum,$page,$prepg,$nextpg,$uid=null){
    if($uid){
        $pageurl='?p=/Member/'.$my.'/uid/'.$uid.'/page/';
    }else{
        $pageurl='?p=/Member/'.$my.'/page/';
    }
    !$pagenum?$prepg='':'';
    if($prepg){
        $x=str_replace('page/','',$pageurl);
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
                    $x=str_replace('page/','',$pageurl);
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