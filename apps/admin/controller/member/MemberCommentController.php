<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2020年06月27日
 *  文章评论控制器
 */
namespace app\admin\controller\member;

use core\basic\Controller;
use app\admin\model\member\MemberCommentModel;

class MemberCommentController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new MemberCommentModel();
    }

    // 文章评论列表
    public function index()
    {
        if ((! ! $id = get('id', 'int')) && $result = $this->model->getComment($id)) {
            $this->assign('more', true);
            $this->assign('comment', $result);
        } else {
            $this->assign('list', true);
            if (! ! ($field = get('field', 'var')) && ! ! ($keyword = get('keyword', 'vars'))) {
                $result = $this->model->findComment($field, $keyword);
            } else {
                $result = $this->model->getList();
            }
            $this->assign('comments', $result);
        }
        $this->display('member/comment.html');
    }

    public function re()
    {
        global $dsql;
        $OnePAGE_NUM=50;
        $page=get('page','int');
        $del=get('del','int');
        $status=get('status','int');
        $xq=get('xq','int');
        if($del && is_numeric($del)){
            $dsql->amd("delete from ay_member_comment_re where reid=$del limit 1");
            success('删除成功！', - 1);
        }
        if($_POST){
            $list = post('list');
            if(!$list){
                error('未选择操作的项目！',-1);
            }
            foreach($list as $xx){
                $dsql->amd("delete from ay_member_comment_re where reid=$xx limit 1");
            }
            success('删除成功！', - 1);
        }
        if($status && is_numeric($status)){
            $value=get('value','int');
            $dsql->amd("update ay_member_comment_re set status=$value where reid=$status limit 1");
            location(-1);
        }
        if($xq && is_numeric($xq)){
            $pbarr=$dsql->one("select * from ay_member_comment_re where reid=$xq limit 1",1);
            $id=$pbarr[id];
            $re_id=$pbarr[re_id];
            $uid=$pbarr[uid];
            if($re_id){
                $x=$dsql->one("select comment from ay_member_comment_re where reid=$re_id limit 1",1);
                $comment_=$x[comment];
            }else{
                $x=$dsql->one("select comment from ay_member_comment where id=$id limit 1",1);
                $comment_=$x[comment];
            }
            if(!$comment_){
                $comment_='<del>原评论已删除</del>';
            }
            $pbarr[comment_]=$comment_;
            $x=$dsql->one("select title from ay_content where id=$id limit 1",1);
            $pbarr[title]=$x[title];
            $x=$dsql->one("select username,nickname from ay_member where id=$uid limit 1",1);
            $pbarr[username]=$x[username];
            $pbarr[nickname]=$x[nickname];
            $this->assign('pbarr',$pbarr);
            $this->display('member/comment_re.html');
        }
        $x=$dsql->one("select count(*) from ay_member_comment_re",1);
        $total=$x["count(*)"];
        $pagenum=ceil($total/$OnePAGE_NUM);
        $page?'':$page='1';
        $page=min($pagenum,$page);!$page?$page=1:"";
        $prepg=$page-1;
        $nextpg=($page==$pagenum?0:$page+1);
        $offset=($page-1)*$OnePAGE_NUM;
        $dd=$dsql->all("select * from ay_member_comment_re order by reid desc limit $offset,$OnePAGE_NUM",1);
        foreach($dd as $xx){
            $reid=$xx[reid];
            $id=$xx[id];
            $re_id=$xx[re_id];
            $comment=$xx[comment];
            $uid=$xx[uid];
            $update_time=$xx[update_time];
            $status=$xx[status];
            if($status=='1'){
                $status='<a href="?p=/MemberComment/re/status/'.$reid.'/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
            }else{
                $status='<a href="?p=/MemberComment/re/status/'.$reid.'/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
            }
            $x=$dsql->one("select username from ay_member where id=$uid limit 1",1);
            $username=$x[username];
            $comment2=substr_both($comment,0,30);
            if($comment!=$comment2){
                $comment2.='...';
            }
            if($re_id){
                $x=$dsql->one("select comment from ay_member_comment_re where reid=$re_id limit 1",1);
                $comment_=$x[comment];
            }else{
                $x=$dsql->one("select comment from ay_member_comment where id=$id limit 1",1);
                $comment_=$x[comment];
            }
            $comment_2=substr_both($comment_,0,15);
            if($comment_!=$comment_2){
                $comment_2.='...';
            }
            if(!$comment_2){
                $comment_2='<del>原评论已删除</del>';
            }
            $list.='
            <tr>
                <td>
                    <input type="checkbox" class="checkbox checkitem" lay-ignore="" name="list[]" value="'.$reid.'" style="display: inline-block;">
                    <input type="hidden" name="listall[]" value="'.$reid.'">
                </td>
                <td title="'.$comment_.'">'.$comment_2.'</td>
                <td title="'.$comment.'">'.$comment2.'</td>
                <td>'.$status.'</td>
                <td title="'.$username.'"><a href="?p=/Member/index/id/'.$uid.'">'.$username.'</a></td>
                <td>'.$update_time.'</td>
                <td>
                    <a href="?p=/MemberComment/re/xq/'.$reid.'" class="layui-btn layui-btn-xs layui-btn-primary" title="详情">详情</a>
                    <a href="?p=/MemberComment/re/del/'.$reid.'" onclick="return confirm(&quot;您确定要删除么？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                </td>
            </tr>';
        }
        if(!$list){
            $list='<tr><td colspan="7" style="text-align:center">暂无相关内容</td></tr>';
        }
        $pageurl='?p=/MemberComment/re&page=';
        $pagebox=pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg);
        $this->assign('list',$list);
        $this->assign('pagebox',$pagebox);
        $this->display('member/comment_re.html');
    }

    // 文章评论删除
    public function del()
    {
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                if ($this->model->delCommentList($list)) {
                    $this->log('批量删除评论成功！');
                    success('删除成功！', - 1);
                } else {
                    $this->log('批量删除评论失败！');
                    error('删除失败！', - 1);
                }
            } else {
                alert_back('请选择要删除的评论！');
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delComment($id)) {
            $this->log('删除文章评论' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除文章评论' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 字段修改
    public function mod()
    {
        if (! ! $submit = post('submit')) {
            switch ($submit) {
                case 'verify1':
                    $list = post('list');
                    if (! $list) {
                        alert_back('请选择要操作的评论！');
                    }
                    $this->model->modCommentList($list, "status=1");
                    success('审核成功！', - 1);
                    break;
                case 'verify0':
                    $list = post('list');
                    if (! $list) {
                        alert_back('请选择要操作的评论！');
                    }
                    $this->model->modCommentList($list, "status=0");
                    success('禁用成功！', - 1);
                    break;
            }
        }
        
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modComment($id, "$field='$value'")) {
                location(- 1);
            } else {
                alert_back('修改失败！');
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