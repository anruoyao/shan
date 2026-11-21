<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2020年06月26日
 *  会员前台控制器
 */
namespace app\home\controller;

use core\basic\Controller;
use app\home\model\MemberModel;
use core\basic\Url;

$x=ROOT_PATH.'/i'.'nc'.'l'.'ud'.'e/me'.'m'.'ber/inc'.'lude/m'.'e'.'mbe'.'r.f'.'u'.'n.php';
require_once($x);

class MemberController extends Controller
{

    protected $parser;

    protected $model;

    protected $htmldir;

    public function __construct()
    {
        $this->model=new MemberModel();
        $this->parser=new ParserController();
        $this->htmldir=$this->config('tpl_html_dir') ? $this->config('tpl_html_dir') . '/' : '';

        global $dsql;
        $uid=session('pboot_ucode');
        if($uid){
            $member=$dsql->one("select autologin from ay_member where ucode=$uid limit 1",1);
            if(!empty($member['autologin'])){
                $this->clearMemberSession();
            }
        }
    }

    private function clearMemberSession()
    {
        foreach(array('pboot_uid','pboot_ucode','pboot_username','pboot_useremail','pboot_usermobile','pboot_gid','pboot_gcode','pboot_gname') as $key){
            session($key,'');
        }
    }

    // 会员登录页面
    public function login()
    {
        global $dsql;
        // 已经登录时跳转到用户中心
        $uid=session('pboot_ucode');
        if ($uid) {
            $x=$dsql->one("select autologin from ay_member where ucode=$uid limit 1",1);
            $o=$x[autologin];
            if($o){
                session('pboot_uid',0);
                session('pboot_ucode',0);
            }else{
                location(pbcode('member','ucenter','','all'));
            }
        }
        $log_url=$_SERVER['HTTP_REFERER'];
        if(!strstr($log_url,'login') && !strstr($log_url,'register')){
            session('log_url',$log_url);
        }
        $x=session('log_url');
        if($x){
            $log_url=$x;
            $x=strstr($log_url,'//');
            $x=str_replace('//','',$x);
            $x=strstr($x,'/');
            $x=ltrim($x,'/');
            if(!$x){
                $log_url=pbcode('member','ucenter','','all');
            }else{
                $x=trim($x,'/');
                $x='/'.$x.'/';
                if($x==pcbase()){
                    $log_url=pbcode('member','ucenter','','all');
                }
            }
        }else{
            $log_url=pbcode('member','ucenter','','all');
        }
        // 执行登录验证
        if($_POST){
            if ($this->config('login_status') === '0') {
                error('系统已经关闭登录功能，请到后台开启再试！');
            }
            
            // 验证码验证
            $checkcode=strtolower(post('checkcode', 'var'));
            if ($this->config('login_check_code') !== '0') {
                if (! $checkcode) {
                    alert_back('验证码不能为空！');
                }
                
                if ($checkcode != session('checkcode')) {
                    alert_back('验证码错误！');
                }
            }
            
            $username=post('username');
            $password=post('password');
            
            if (! $username) {
                alert_back('用户账号不能为空！');
            }
            
            // 检查用户名
            if (! $this->model->checkUsername("username='$username' or useremail='$username' or usermobile='$username'")) {
                alert_back('用户账号不存在！');
            }
            
            // 检查密码
            if (! $password) {
                alert_back('用户密码不能为空！');
            } else {
                $password=md5(md5($password));
            }
            
            // 登录验证
            if (! ! $login=$this->model->login("(username='$username' or useremail='$username' or usermobile='$username') AND password='$password'")) {
                if (! $login->status) {
                    alert_back('您的账号待审核，请联系管理员！');
                }
                session('pboot_uid', $login->id);
                session('pboot_ucode', $login->ucode);
                session('pboot_username', $login->username);
                session('pboot_useremail', $login->seremail);
                session('pboot_usermobile', $login->usermobile);
                $gcode=$login->gid;
                session('pboot_gcode', $gcode);
                $x=$dsql->one("select id from ay_member_group where gcode=$gcode limit 1",1);
                $gid=$x[id];
                session('pboot_gid', $gid);
                session('pboot_gname', $login->gname);
                $uid=$login->ucode;
                $username=$login->username;
                $x=$dsql->one("select register_time from ay_member where ucode=$uid limit 1",1);
                $register_time=$x[register_time];
                $register_time=strtotime($register_time);
                $url=wapbase('all').'?login/'.$uid.'/'.$username.'/'.$register_time.'/';
                $bu=post('bu');
                if($bu){
                    $wap=post('wap');
                    $arr=explode('.',$bu);
                    $id=$arr[0];
                    $xid=$arr[1];
                    if($xid){
                        $log_url=pbcode('article',$xid,'','all','','',$wap);
                    }else{
                        $x=ROOT_PATH.'/include/chapter_pbcode_xyz/index.php';
                        if(file_exists($x)){
                            $log_url=pbcode('book',$id,'','all','','',$wap);
                        }else{
                            $log_url=pbcode('content',$id,'','all','','',$wap);
                        }
                    }
                }
                $log_url.='{697}'.$url;
                $x='登录成功！';
                success($x,$log_url,3);
            } else {
                alert_back('账号密码错误，请核对后重试！', 0);
            }
        } else {
            $this->tpl(__FUNCTION__);
        }
    }

    // 会员注册页面
    public function register()
    {
        global $dsql;
        // 已经登录时跳转到用户中心
        $uid=session('pboot_ucode');
        if ($uid) {
            $x=$dsql->one("select autologin from ay_member where ucode=$uid limit 1",1);
            $o=$x[autologin];
            if($o){
                session('pboot_uid',0);
                session('pboot_ucode',0);
            }else{
                location(pbcode('member','ucenter','','all'));
            }
        }
        $log_url=$_SERVER['HTTP_REFERER'];
        if(!strstr($log_url,'login') && !strstr($log_url,'register')){
            session('log_url',$log_url);
        }
        $x=session('log_url');
        if($x){
            $log_url=$x;
            $x=strstr($log_url,'//');
            $x=str_replace('//','',$x);
            $x=strstr($x,'/');
            $x=ltrim($x,'/');
            if(!$x){
                $log_url=pbcode('member','ucenter','','all');
            }else{
                $x=trim($x,'/');
                $x='/'.$x.'/';
                if($x==pcbase()){
                    $log_url=pbcode('member','ucenter','','all');
                }
            }
        }else{
            $log_url=pbcode('member','ucenter','','all');
        }
        // 执行注册
        if($_POST){
            if ($this->config('register_status') === '0') {
                error('系统已经关闭注册功能，请到后台开启再试！');
            }
            
            if (time() - session('lastreg') < 10) {
                alert_back('您注册太频繁了，请稍后再试！');
            }
            
            // 验证码验证
            $checkcode=strtolower(post('checkcode', 'var'));
            if ($this->config('register_check_code') !== '0') {
                if (! $checkcode) {
                    alert_back('验证码不能为空！');
                }
                
                if ($checkcode != session('checkcode')) {
                    alert_back('验证码错误！');
                }
            }
            
            $ucode=get_auto_code($this->model->getLastUcode(), 1);
            if($ucode<5){
                $ucode=5;
            }
            $username=post('username'); // 接受用户名、邮箱、手机三种方式
            $nickname=post('nickname');
            $password=post('password');
            $rpassword=post('rpassword');
            
            $useremail='';
            $usermobile='';
            // 注册类型判断
            if ($this->config('register_type')==2) { // 邮箱注册
                $useremail=$username;
                if (! $useremail) {
                    alert_back('账号不能为空，请输入注册的邮箱账号！');
                }
                if (! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                    alert_back('账号格式不正确，请输入正确的邮箱账号！');
                }
                if ($this->model->checkUsername("useremail='$useremail' OR username='$useremail'")) {
                    alert_back('您输入的邮箱已被注册！');
                }
            } elseif ($this->config('register_type')==3) { // 手机注册
                $usermobile=$username;
                if (! $usermobile) {
                    alert_back('账号不能为空，请输入注册的手机号码！');
                }
                if (! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                    alert_back('账号格式不正确，请输入正确的手机号码！');
                }
                if ($this->model->checkUsername("usermobile='$usermobile' OR username='$usermobile'")) {
                    alert_back('您输入的手机号码已被注册！');
                }
            } else { // 账号注册
                if (! $username) {
                    alert_back('用户名不能为空！');
                }
                if (! preg_match('/^[\w\@\.]+$/', $username)) {
                    // alert_back('用户账号含有不允许的特殊字符！');
                }
                // 检查用户名
                if ($this->model->checkUsername("username='$username' OR useremail='$username' OR usermobile='$username'")) {
                    alert_back('您输入的账号已被注册！');
                }
            }
            
            if ($password != $rpassword) {
                alert_back('确认密码不正确！');
            }
            
            if (! $password) {
                alert_back('密码不能为空！');
            } else {
                $password=md5(md5($password));
            }
            
            // 默认值设置
            $status=$this->config('register_verify') ? 0 : 1; // 默认不需要审核
            $score=$this->config('register_score') ?: 0;
            
            $x=$dsql->one("select gcode from ay_member_group where status=1 order by gcode asc limit 1",1);
            $gcode=$x[gcode];

            if(!$headpic){
                $headpic='/uploads/empty/'.rand(1,100).'.gif';
            }
            
            // 构建数据
            $data=array(
                'ucode' => $ucode,
                'username' => $username,
                'useremail' => $useremail,
                'usermobile' => $usermobile,
                'nickname' => $nickname,
                'password' => $password,
                'headpic' => $headpic,
                'status' => $status,
                'gid' => $gcode,
                'wxid' => '',
                'qqid' => '',
                'wbid' => '',
                'activation' => 1,
                'score' => $score,
                'register_time' => get_datetime(),
                'login_count' => 0,
                'last_login_ip' => 0,
                'last_login_time' => 0
            );
            
            // 读取字段
            if (! ! $field=$this->model->getField()) {
                foreach ($field as $value) {
                    $field_data=post($value->name);
                    if (is_array($field_data)) { // 如果是多选等情况时转换
                        $field_data=implode(',', $field_data);
                    }
                    $field_data=preg_replace_r('pboot:if', '', $field_data);
                    if ($value->required && ! $field_data) {
                        alert_back($value->description . '不能为空！');
                    } else {
                        $data[$value->name]=$field_data;
                    }
                }
            }
            
            // 执行注册
            if ($this->model->register($data)) {
                session('lastreg', time()); // 记录最后提交时间
                $x=strtotime('+100 year');
                $validdate=date('Y-m-d H:i:s',$x);
                $dsql->amd("update ay_member set validdate='$validdate' where ucode=$ucode limit 1");
                if ($status) {
                    $x='注册成功！';
                    $url=pbcode('member','login','','all');
                    $bu=post('bu');
                    if($bu){
                        $wap=post('wap');
                        $url.='&bu='.$bu;
                        if($wap){
                            $url.='&wap=wap';
                        }
                    }
                    success($x,$url,3);
                } else {
                    $x='注册成功，请等待管理员审核！';
                    $url=pbcode('member','login','','all');
                    success($x,$url,3);
                }
            } else {
                $x='会员注册失败！';
                error($x,-1,3);
            }
        } else {
            $this->tpl(__FUNCTION__);
        }
    }

    // 个人中心（读者）
    public function index()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        // 移动端沿用现有会员中心模板，PC 使用个人中心模板
        $this->tpl(is_mobile() ? 'ucenter' : __FUNCTION__, is_mobile());
    }

    // 用户中心
    public function ucenter()
    {
        $uid=session('pboot_ucode');
        if(!$uid){
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    // 作者等级
    public function author_level()
    {
        global $dsql,$author_level,$author_year_points;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $x=$dsql->one("select nickname,headpic,score,sign,author_title,author_honor from ay_member where ucode=$uid limit 1",1);
        $author_level=author_level_data($x['score'],$x['sign'],$x['author_title'],$x['author_honor']);
        $author_year_points=0;
        $year=date('Y').'年';
        $dd=$dsql->all("select g.je from ay_gf g left join ay_content c on g.id=c.id where c.uid=$uid and g.yf like '$year%'",1);
        foreach($dd as $xx){
            $author_year_points+=intval(str_replace(',','',$xx['je']));
        }
        $this->tpl(__FUNCTION__);
    }

    // 用户修改
    public function umodify()
    {
        global $dsql;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }  
        // 执行资料修改
        if ($_POST && session('pboot_uid')) {

            $x=$dsql->one("select autologin from ay_member where ucode=$uid limit 1",1);
            $autologin=$x[autologin];
            if($autologin){
                $x='游客禁止修改！';
                $url=pbcode('member','ucenter');
                error($x,$url,3);
            }

            $nickname=post('nickname');
            $useremail=post('useremail');
            $usermobile=post('usermobile');
            $password=post('password');
            $rpassword=post('rpassword');
            $headpic=$_FILES['headpic'];
            
            if ($useremail) { // 邮箱校验
                if (! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $useremail)) {
                    alert_back('邮箱格式不正确，请输入正确的邮箱账号！');
                }
                if ($this->model->checkUsername("(useremail='$useremail' OR username='$useremail') AND id<>'" . session('pboot_uid') . "'")) {
                    alert_back('您输入的邮箱已被注册！');
                }
            }
            
            if ($usermobile) { // 手机检验
                if (! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                    alert_back('手机格式不正确，请输入正确的手机号码！');
                }
                if ($this->model->checkUsername("(usermobile='$usermobile' OR username='$usermobile') AND id<>'" . session('pboot_uid') . "'")) {
                    alert_back('您输入的手机号码已被注册！');
                }
            }
            
            // 构建数据
            $data=array(
                'nickname' => $nickname,
                'useremail' => $useremail,
                'usermobile' => $usermobile
            );
            
            // 密码修改
            if ($password) {
                if ($password != $rpassword) {
                    alert_back('确认密码不正确！');
                } else {
                    $data['password']=md5(md5($password));
                }
            }
            
            // 读取字段
            if (! ! $field=$this->model->getField()) {
                foreach ($field as $value) {
                    $field_data=post($value->name);
                    if (is_array($field_data)) { // 如果是多选等情况时转换
                        $field_data=implode(',', $field_data);
                    }
                    $field_data=preg_replace_r('pboot:if', '', $field_data);
                    if ($value->required && ! $field_data) {
                        alert_back($value->description . '不能为空！');
                    } else {
                        $data[$value->name]=$field_data;
                    }
                }
            }
            
            // 不允许修改的字段
            unset($data['id']);
            unset($data['ucode']);
            unset($data['username']);
            unset($data['status']);
            unset($data['gid']);
            unset($data['wxid']);
            unset($data['qqid']);
            unset($data['wbid']);
            unset($data['score']);
            unset($data['register_time']);
            unset($data['login_count']);
            unset($data['last_login_ip']);
            unset($data['last_login_time']);
            unset($data['jinbi']);
            unset($data['validdate']);
            
            // 执行修改
            $this->model->modUser($data);
            uf($headpic,'headpic',$uid);
            $x='修改成功！';
            success($x,-1,3);
        } else {
            global $username,$nickname,$useremail,$headpic,$usermobile;
            $x=$dsql->one("select * from ay_member where ucode=$uid limit 1",1);
            $username=$x[username];
            $nickname=$x[nickname];
            $useremail=$x[useremail];
            $headpic=$x[headpic];
            $usermobile=$x[usermobile];
            if($headpic){
                $headpic=ico($headpic);
            }else{
                $headpic=ico();
            }
            $this->tpl(__FUNCTION__);
        }
    }

    // 退出登录
    public function logout()
    {
        session('pboot_uid', '');
        session('pboot_ucode', '');
        session('pboot_username', '');
        session('pboot_useremail', '');
        session('pboot_usermobile', '');
        session('pboot_gid', '');
        session('pboot_gcode', '');
        session('pboot_gname', '');
        $x='安全退出！';
        $log_url=$_SERVER['HTTP_REFERER'];
        if(strstr($log_url,'member')){
            $log_url=pbcode('member','ucenter','','all');
        }
        success($x,$log_url,3);
    }

    // 文件上传方法(Ajax)
    public function upload()
    {
        // 必须登录
        if(!session('pboot_ucode')){
            json(0, '请先登录！');
        }
        
        $ext=$this->config('home_upload_ext') ?: "jpg,jpeg,png,gif,xls,xlsx,doc,docx,ppt,pptx,rar,zip,pdf,txt";
        $upload=upload('upload', $ext);
        if (is_array($upload)) {
            json(1, $upload);
        } else {
            json(0, $upload);
        }
    }

    // 发送邮件
    public function sendEmail()
    {
        if ($this->config('register_check_code') != 2) {
            json(0, '发送失败，后台配置非邮箱验证码模式！');
        }
        
        if (time() - session('lastsend') < 10) {
            json(0, '您提交太频繁了，请稍后再试！');
        }
        
        if (! session('sendemail')) {
            json(0, '非法提交发送邮件！');
        }
        
        // 发送邮箱参数
        if (! ! $to=post('to')) {
            if (! preg_match('/^[\w]+@[\w]+\.[a-zA-Z]+$/', $to)) {
                json(0, '邮箱格式不正确，请输入正确的邮箱账号！');
            }
        } else {
            json(0, '发送失败，缺少发送对象参数to！');
        }
        
        // 检查邮箱注册
        if ($this->model->checkUsername("useremail='$to' OR username='$to'")) {
            alert_back('您输入的邮箱已被注册！');
        }
        
        $rs=false;
        if ($to) {
            session('lastsend', time()); // 记录最后提交时间
            $mail_subject="【PbootCMS】您有新的验证码信息，请注意查收！";
            $code=create_code(4);
            session('checkcode', strtolower($code));
            $mail_body="您的验证码为：" . $code;
            $mail_body .= '<br>来自网站 ' . get_http_url() . ' （' . date('Y-m-d H:i:s') . '）';
            $rs=sendmail($this->config(), $to, $mail_subject, $mail_body);
        }
        if ($rs === true) {
            json(1, '发送成功！');
        } else {
            json(0, '发送失败，' . $rs);
        }
    }

    // 检查用户是否注册
    public function isRegister()
    {
        // 接受用户名、邮箱、手机三种方式
        $info='';
        if (! $username=post('username')) {
            $err='账号不能为空！';
        }
        
        // 注册类型判断
        if ($this->config('register_type')==2) { // 邮箱注册
            if (! preg_match('/^[\w]+@[\w\.]+\.[a-zA-Z]+$/', $username)) {
                $err='账号格式不正确，请输入正确的邮箱账号！';
            }
            if ($this->model->checkUsername("useremail='$username' OR username='$username'")) {
                $err='您输入的邮箱已被注册！';
            } else {
                $suc='您输入的邮箱可以使用！';
            }
        } elseif ($this->config('register_type')==3) { // 手机注册
            if (! preg_match('/^1[0-9]{10}$/', $username)) {
                $err='账号格式不正确，请输入正确的手机号码！';
            }
            if ($this->model->checkUsername("usermobile='$username' OR username='$username'")) {
                $err='您输入的手机号码已被注册！';
            } else {
                $suc='您输入的手机号码可以使用！';
            }
        } else { // 账号注册
            if (! preg_match('/^[\w\@\.]+$/', $username)) {
                $err='用户账号含有不允许的特殊字符！';
            }
            // 检查用户名
            if ($this->model->checkUsername("username='$username' OR useremail='$username' OR usermobile='$username'")) {
                $err='您输入的账号已被注册！';
            } else {
                $suc='您输入的账号可以使用！';
            }
        }
        
        if ($err) {
            json(1, $err);
        } else {
            json(0, $suc);
        }
    }

    public function _empty()
    {
        _404('您访问的地址不存在，请核对再试！');
    }

    public function look()
    {
        $this->tpl(__FUNCTION__);
    }

    public function good()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function bad()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function fav()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function yc()
    {
        global $QUERY_STRING_ARR;
        global $dsql,$sqqy,$sign;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $sign=$QUERY_STRING_ARR[1];
        yc_fun();
        $this->tpl(__FUNCTION__);
    }

    public function yc_add()
    {
        global $QUERY_STRING_ARR;
        global $dsql,$article;
        $uid=session('pboot_ucode');
        $username=session('pboot_username');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $article=$QUERY_STRING_ARR[1];
        if($_POST){
            $x=$dsql->one("select autologin from ay_member where ucode=$uid limit 1",1);
            $autologin=$x[autologin];
            if($autologin){
                $x='游客禁止发布！';
                $url=pbcode('member','ucenter');
                error($x,$url,3);
            }
        }
        yc_add_fun();
        $this->tpl(__FUNCTION__);
    }

    public function yc_edit()
    {
        global $QUERY_STRING_ARR;
        global $dsql,$article;
        $uid=session('pboot_ucode');
        $username=session('pboot_username');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $article=$QUERY_STRING_ARR[2];
        yc_edit_fun();
        $this->tpl(__FUNCTION__);
    }

    public function yc_chapter()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function yc_del()
    {
        global $dsql;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $x='QUERY_STRING';
        $x=$_SERVER[ $x ];
        $x=str_replace('member/','',$x);
        $arr=explode('/',$x);
        $id=$arr[1];
        if(!is_numeric($id)){
            $x='数据有误！';
            error($x,-1,3);
        }
        $xid=$arr[2];
        $xid=strip_tags($xid);
        $xid=trim($xid);
        $arr=explode(',',$xid);
        foreach($arr as $k => $v){
            $v=trim($v);
            if(!is_numeric($v)){
                $idx='';
                break;
            }
            $idx.="xid=$v or ";
            $x=$dsql->one("select content from ay_article where xid=$v limit 1",1);
            $content_=$x[content];
            $content_=sbyouNet_Body($v,$content_,0);
            $content_=strip_tags($content_);
            $content_=str_replace(array(PHP_EOL,"   "," ","　","\t","\n","\r"),"",$content_);
            $x=strlen($content_);
            $len_+=ceil($x/3);
        }
        $idx=rtrim($idx,' or ');
        if($idx){
            $sql="id=$id and ($idx)";
        }
        $o=$dsql->amd("delete from ay_article where $sql");
        if($o){
            $dsql->amd("update ay_content set subscode=subscode-$len_ where id=$id limit 1");
            $x='删除成功！';
            success($x,-1,3);
        }else{
            $x='删除失败！';
            error($x,-1,3);
        }
    }

    public function vip()
    {
        global $QUERY_STRING_ARR,$pay_ready;
        global $dsql,$score,$scoreall,$jinbi2jifen,$pay_,$js;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        if(!strstr($pay_ready,'6'.''.''.''.''.'9'.'7c'.'ode'.'.'.'co'.'m')){
            $x='暂未'.''.'开'.'启'.'VI'.'P等'.'级和'.'充'.'值'.'提'.'现'.'功能'."<br".">";
            $url=pbcode('member','ucenter','','all');
            error($x,$url,3);
        }
        $pay_=$QUERY_STRING_ARR[1];
        vip_fun();
        $this->tpl(__FUNCTION__);
    }

    public function money()
    {
        global $QUERY_STRING_ARR,$pay_ready;
        global $dsql,$pay_,$idx,$jb,$jball,$tx,$js,$rmb2jinbi,$tx2rmb,$tx2sxf;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $x=explode('__',$pay_ready);
        $x=$x[1];
        $x1=md5($x);
        $x='697code.com';
        $x2=md5($x);
        if($x1!=$x2){
            $x='暂'.'未'.''.'开'.'启'.'VI'.'P等'.'级和'.'充'.'值提'.'现'.'功能'."<br>";
            $url=pbcode('member','ucenter','','all');
            error($x,$url,3);
        }
        $pay_=$QUERY_STRING_ARR[1];
        $idx=$QUERY_STRING_ARR[2];
        money_fun();
        $this->tpl(__FUNCTION__);
    }

    public function pay()
    {
        global $QUERY_STRING_ARR,$pay_ready;
        global $dsql,$HTTP_HOST,$pay_,$ding_,$id,$js,$pay_appid,$pay_appsecret,$pay_fn,$pay_xxx,$dingyue,$dy,$rmb2jinbi,$jinbi2jifen;
        $uid=session('pboot_ucode');
        $gcode=session('pboot_gcode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        if(!strstr($pay_ready,'6'.''.''.''.'9'.'7c'.'od'.'e'.'.'.'co'.'m')){
            $x='暂未'.''.'开'.'启'.'VI'.'P等级和'.'充'.'值'.'提'.'现'.'功能'."<br".">";
            $url=pbcode('member','ucenter','','all');
            error($x,$url,3);
        }
        $pay_=$QUERY_STRING_ARR[1];
        $ding_=$QUERY_STRING_ARR[2];
        pay_fun();
        $this->tpl(__FUNCTION__);
    }

    public function notify()
    {
        global $dsql,$pay_fn;
        include_once(ROOT_PATH.'/p'.''.'a'.''.'y'.'/'.$pay_fn.'/'.__FUNCTION__.'.php');
        exit;
    }

    public function query()
    {
        global $dsql,$pay_fn,$pay_appid,$pay_appsecret;
        $uid=session('pboot_ucode');
        if (!$uid) {
            return false;
        }
        if($_POST){
            $ding=gpvar('ding');
            include_once(ROOT_PATH.'/p'.''.'a'.''.'y'.'/'.$pay_fn.'/'.__FUNCTION__.'.php');
        }
        exit;
    }

    public function cmt()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function re()
    {
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function service()
    {
        $this->tpl(__FUNCTION__);
    }

    public function tg()
    {
        global $pay_ready;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        if(!strstr($pay_ready,'6'.''.''.''.'9'.'7c'.'od'.'e'.'.'.'co'.'m')){
            $x='暂未'.''.'开'.'启'.'VI'.'P等级和'.'充'.'值'.'提'.'现'.'功能'."<br".">";
            $url=pbcode('member','ucenter','','all');
            error($x,$url,3);
        }
        $this->tpl(__FUNCTION__);
    }

    public function yc_info()
    {
        global $dsql,$pay_ready,$free_chapter,$title,$score,$num_com,$num_dy,$num_dy_first,$num_zs_month,$num_jd,$num_chapters,$content_update_time,$gf_total,$gf_month,$gf_last_month;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $x='QUERY_STRING';
        $x=$_SERVER[ $x ];
        $x=str_replace('member/','',$x);
        $arr=explode('/',$x);
        $id=$arr[1];
        if(!is_numeric($id)){
            $x='数据有误！';
            error($x,-1,3);
        }
        $x=$dsql->one("select title,score,num_dy,num_dy_first,num_zs_month,update_time from ay_content where id=$id limit 1",1);
        $title=$x[title];
        $score=$x[score];
        $content_update_time=$x[update_time];
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
        $num_chapters=$num;
        $num_=$num-$free_chapter;
        if($num_>0){
            $x=$num_dy/$num_;
            $num_jd=round($x,3);
        }else{
            $num_jd=0;
        }
        $x=$dsql->one("select count(*) from ay_member_comment where status=1 and contentid=$id limit 1",1);
        $num_com=$x['count(*)'];
        $gf=author_gf_data($uid,$id);
        $gf_total=$gf['total'];
        $gf_month=$gf['this_month'];
        $gf_last_month=$gf['last_month'];
        $this->tpl(__FUNCTION__);
    }

    public function yc_gf()
    {
        global $dsql,$title,$list,$gf_data;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $x='QUERY_STRING';
        $x=$_SERVER[ $x ];
        $x=str_replace('member/','',$x);
        $arr=explode('/',$x);
        $id=$arr[1];
        if(!is_numeric($id)){
            $x='数据有误！';
            error($x,-1,3);
        }
        $x=$dsql->one("select title from ay_content where id=$id limit 1",1);
        $title=$x[title];
        $gf_data=author_gf_data($uid,$id);
        $list='';
        if(is_mobile()){
            foreach($gf_data['rows'] as $row){
                $list.='
                <li>
                    <div><span>月份</span><span>'.$row['yf'].'</span></div>
                    <div><span>名称</span><span>'.$row['book'].'</span></div>
                    <div><span>发放总金额</span><span>'.$row['je'].'</span></div>
                    <div><span>订阅收入</span><span>'.$row['dy'].'</span></div>
                    <div><span>渠道收入</span><span>'.$row['qd'].'</span></div>
                    <div><span>平台激励</span><span>'.$row['pt'].'</span></div>
                    <div><span>结算时间</span><span>'.$row['update_time'].'</span></div>
                </li>';
            }
        }
        $this->tpl(__FUNCTION__);
    }

    public function gf()
    {
        global $gf_data;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $gf_data=author_gf_data($uid);
        $this->tpl(__FUNCTION__);
    }

    // 申请签约
    public function sign()
    {
        global $sign_conditions;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }
        $sign_conditions=author_sign_conditions($uid);
        $this->tpl(__FUNCTION__);
    }

    public function info()
    {
        global $dsql;
        $uid=session('pboot_ucode');
        if (!$uid) {
            $x='未登录！';
            $url=pbcode('member','login','','all');
            error($x,$url,3);
        }  

        if ($_POST && session('pboot_uid')) {

            $x=$dsql->one("select autologin from ay_member where ucode=$uid limit 1",1);
            $autologin=$x[autologin];
            if($autologin){
                $x='游客禁止修改！';
                $url=pbcode('member','ucenter');
                error($x,$url,3);
            }

            $xingming=post('xingming');
            $usermobile=post('usermobile');
            $yh_ka=post('yh_ka');
            $yh_ming=post('yh_ming');
            $yh_hang=post('yh_hang');
            $wximg=isset($_FILES['wximg'])?$_FILES['wximg']:null;
            $headpic=isset($_FILES['headpic'])?$_FILES['headpic']:null;
            $qq=post('qq');
            $nickname=post('nickname');
            $author_bio=post('author_bio');
            $idcard=post('idcard');

            $ycico2=post('ycico2');
            if(!$ycico2){
                $wximg='';
            }

            $nickname=trim(strip_tags($nickname));
            $author_bio=trim(strip_tags($author_bio));
            $idcard=trim(strip_tags($idcard));
            $nickname=str_replace(array("'",'"'),'',$nickname);
            $author_bio=str_replace(array("'",'"'),'',$author_bio);
            $idcard=str_replace(array("'",'"'),'',$idcard);
            if(!$nickname){
                alert_back('请输入笔名！');
            }
            if($idcard && !preg_match('/(^\d{15}$)|(^\d{17}[\dXx]$)/',$idcard)){
                alert_back('身份证号格式不正确！');
            }
            if(mb_strlen($author_bio,'UTF-8')>500){
                $author_bio=mb_substr($author_bio,0,500,'UTF-8');
            }

            $xingming=strip_tags($xingming);
            $usermobile=strip_tags($usermobile);
            $yh_ka=strip_tags($yh_ka);
            $yh_ming=strip_tags($yh_ming);
            $yh_hang=strip_tags($yh_hang);

            $xingming=str_replace(array("'",'"','-','_','|','=',' ','.','{','}','+','*','<','>','^','&','%','#','/','\\','!'),'',$xingming);
            $usermobile=str_replace(array("'",'"','-','_','|','=',' ','.','{','}','+','*','<','>','^','&','%','#','/','\\','!'),'',$usermobile);
            $yh_ka=str_replace(array("'",'"','-','_','|','=',' ','.','{','}','+','*','<','>','^','&','%','#','/','\\','!'),'',$yh_ka);
            $yh_ming=str_replace(array("'",'"','-','_','|','=',' ','.','{','}','+','*','<','>','^','&','%','#','/','\\','!'),'',$yh_ming);
            $yh_hang=str_replace(array("'",'"','-','_','|','=',' ','.','{','}','+','*','<','>','^','&','%','#','/','\\','!'),'',$yh_hang);

            if ($usermobile) { // 手机检验
                if (! preg_match('/^1[0-9]{10}$/', $usermobile)) {
                    alert_back('手机格式不正确，请输入正确的手机号码！');
                }
                // if ($this->model->checkUsername("(usermobile='$usermobile' OR username='$usermobile') AND id<>'" . session('pboot_uid') . "'")) {
                //     alert_back('您输入的手机号码已被注册！');
                // }
            }

            $data=array(
                'nickname' => $nickname,
                'author_bio' => $author_bio,
                'idcard' => $idcard,
                'xingming' => $xingming,
                'usermobile' => $usermobile,
                'yh_ka' => $yh_ka,
                'yh_ming' => $yh_ming,
                'yh_hang' => $yh_hang,
                'qq' => $qq
            );

            $sql='';
            foreach($data as $k => $v){
                $sql.="$k='$v',";
            }
            $sql=trim($sql,',');
            $dsql->amd("update ay_member set $sql where ucode=$uid limit 1");
            uf_($wximg,$uid);
            uf($headpic,'headpic',$uid);
            $x='修改成功！';
            success($x,-1,3);
        } else {
            global $xingming,$usermobile,$yh_ka,$yh_ming,$yh_hang,$wximg,$qq,$nickname,$author_bio,$idcard,$headpic;
            $x=$dsql->one("select * from ay_member where ucode=$uid limit 1",1);
            $nickname=$x[nickname];
            $author_bio=$x[author_bio];
            $idcard=$x[idcard];
            $headpic=$x[headpic];
            $xingming=$x[xingming];
            $usermobile=$x[usermobile];
            $yh_ka=$x[yh_ka];
            $yh_ming=$x[yh_ming];
            $yh_hang=$x[yh_hang];
            $wximg=$x[wximg];
            $qq=$x[qq];
            if($wximg){
                $wximg=ico($wximg);
            }
            $headpic=ico($headpic);
            $this->tpl(__FUNCTION__);
        }
    }

    public function tpl($tpl, $forceWap = false)
    {
        global $dsql;
        $tpl.='.html';
        $template=$tpl;
        if(strstr($template,'edit') || strstr($template,'add')){
            global $page,$id,$title,$scode,$content,$subtitle,$ico,$article,$tags,$author_say;
            $id=$page;
            $tags='';
            $author_say='';
            if($id){
                $x=$dsql->one("select title,scode,content,subtitle,ico,mcode,description,status,tags from ay_content where id=$id limit 1",1);
                $title=$x[title];
                $scode=$x[scode];
                $content=$x[content];
                $subtitle=$x[subtitle];
                $ico=$x[ico];
                $mcode=$x[mcode];
                $description=$x[description];
                $status=$x[status];
                $tags=$x[tags];
            }
            if($mcode==18){
                $content=$description;
            }
            if($article && is_numeric($article)){
                global $xid,$xtitle,$content,$author_say;
                $xid=$article;
                $x=$dsql->one("select xtitle,content,author_say from ay_article where xid=$article limit 1",1);
                $xtitle=$x[xtitle];
                $content=$x[content];
                $author_say=$x[author_say];
            }
            if($status!=1){
                $ico='/include/member/image/empty.jpg';
            }
        }
        // 作者中心已改版为响应式工作台，移动端同样使用 PC 模板，避免刷新后回退到旧版 WAP 页面
        $authorTpls=array('ucenter','yc','yc_add','yc_edit','yc_chapter','yc_gf','yc_info','info','author_level','gf','sign');
        $tplName=basename($template,'.html');
        if(is_mobile() && ($forceWap || !in_array($tplName,$authorTpls,true))){
            $pw='wap';
        }else{
            $pw='pc';
        }
        $x=parent::parser('../../i'.'ncl'.'ude/m'.'e'.'mb'.'er/'.$pw.'/'.$template);
        $x=$this->parser->parserBefore($x);
        $x=$this->parser->parserAfter($x);
        echo $x;
        exit();
    }
}

function uf_($file=null,$ucode=null){
    global $dsql;
    if(!$file || !$ucode){
        if(!$file){
            $dsql->amd("update ay_member set wximg='' where ucode=$ucode limit 1");
        }
        return false;
    }
    $allowedExts=array("gif","jpeg","jpg","png","webp");
    $x=explode(".",$file["name"]);
    $extension=end($x);
    $extension=strtolower($extension);
    $ft=$file["type"];
    $fs=$file["size"];
    if((($ft=="image/gif") || ($ft=="image/jpeg") || ($ft=="image/jpg") || ($ft=="image/pjpeg") || ($ft=="image/x-png") || ($ft=="image/png") || ($ft=="image/webp")) && ($fs < 15728640) && in_array($extension,$allowedExts)){
        if($file["error"] > 0){
            $xxx='';
        }else{
            $f=ROOT_PATH.'/uploads/allimg/'.date('Ymd');
            mkdir($f,0777,true);
            $x='697.'.$ucode.'.'.$extension;
            $x=md5($x);
            $x=$x.'.'.$extension;
            $f.='/'.$x;
            unlink($f);
            move_uploaded_file($file["tmp_name"],$f);
            if(file_exists($f)){
                $xxx=str_replace(ROOT_PATH,'',$f);
                $dsql->amd("update ay_member set wximg='$xxx' where ucode=$ucode limit 1");
            }
        }
    }
    return $xxx;
}
