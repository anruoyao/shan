<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2019年07月12日
 *  内链链接控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\TagsModel;

class TagsController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new TagsModel();
    }

    // 文章内链列表
    public function index()
    {
        global $dsql;
        $OnePAGE_NUM=10;
        $page=get('page','int');
        $x=$dsql->one("select count(*) from ay_tags",1);
        $total=$x["count(*)"];
        $pagenum=ceil($total/$OnePAGE_NUM);
        $page?'':$page='1';
        $page=min($pagenum,$page);!$page?$page=1:"";
        $prepg=$page-1;
        $nextpg=($page==$pagenum?0:$page+1);
        $offset=($page-1)*$OnePAGE_NUM;
        $dd=$dsql->all("select * from ay_tags order by id desc limit $offset,$OnePAGE_NUM",1);
        foreach($dd as $xx){
            $id=$xx[id];
            $name=$xx[name];
            $link=$xx[link];
            $list.='
            <tr>
                <td><input type="checkbox" class="checkitem" lay-ignore="" name="list1[]" value="'.$id.'"></td>
                <td>'.$name.'</td>
                <td>'.$link.'</td>
                <td>
                    <a href="?p=/Tags/del/id/'.$id.'" onclick="return confirm(&quot;您确定要删除么？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                    <a href="?p=/Tags/mod/id/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
                </td>
            </tr>';
        }
        if(!$list){
            $list='
            <tr>
                <td colspan="4" style="text-align:center">暂无相关内容</td>
            </tr>';
        }
        $pageurl='?p=/Tags/index&page=';
        $pagebox=pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg);

        $this->assign('list', $list);
        $this->assign('pagebox', $pagebox);

        $page2=get('page2','int');
        $x=$dsql->one("select count(*) from ay_jin",1);
        $total=$x["count(*)"];
        $pagenum=ceil($total/$OnePAGE_NUM);
        $page2?'':$page2='1';
        $page2=min($pagenum,$page2);!$page2?$page2=1:"";
        $prepg=$page2-1;
        $nextpg=($page2==$pagenum?0:$page2+1);
        $offset=($page2-1)*$OnePAGE_NUM;
        $dd=$dsql->all("select * from ay_jin order by id desc limit $offset,$OnePAGE_NUM",1);
        foreach($dd as $xx){
            $id=$xx[id];
            $key=$xx[key];
            $list2.='
            <tr>
                <td><input type="checkbox" class="checkitem" lay-ignore="" name="list2[]" value="'.$id.'"></td>
                <td>'.$key.'</td>
                <td>
                    <a href="?p=/Tags/del/id2/'.$id.'" onclick="return confirm(&quot;您确定要删除么？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                    <a href="?p=/Tags/mod/id2/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
                </td>
            </tr>';
        }
        if(!$list2){
            $list2='
            <tr>
                <td colspan="4" style="text-align:center">暂无相关内容</td>
            </tr>';
        }
        $pageurl='?p=/Tags/index&page2=';
        $pagebox2=pagebox($pageurl,$total,$pagenum,$page2,$prepg,$nextpg,'#tab=t2');
        
        $this->assign('list2', $list2);
        $this->assign('pagebox2', $pagebox2);

        $page3=get('page3','int');
        $x=$dsql->one("select count(*) from ay_rep",1);
        $total=$x["count(*)"];
        $pagenum=ceil($total/$OnePAGE_NUM);
        $page3?'':$page3='1';
        $page3=min($pagenum,$page3);!$page3?$page3=1:"";
        $prepg=$page3-1;
        $nextpg=($page3==$pagenum?0:$page3+1);
        $offset=($page3-1)*$OnePAGE_NUM;
        $dd=$dsql->all("select * from ay_rep order by id desc limit $offset,$OnePAGE_NUM",1);
        foreach($dd as $xx){
            $id=$xx[id];
            $key1=$xx[key1];
            $key2=$xx[key2];
            $list3.='
            <tr>
                <td><input type="checkbox" class="checkitem" lay-ignore="" name="list3[]" value="'.$id.'"></td>
                <td>'.$key1.'</td>
                <td>'.$key2.'</td>
                <td>
                    <a href="?p=/Tags/del/id3/'.$id.'" onclick="return confirm(&quot;您确定要删除么？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                    <a href="?p=/Tags/mod/id3/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
                </td>
            </tr>';
        }
        if(!$list3){
            $list3='
            <tr>
                <td colspan="4" style="text-align:center">暂无相关内容</td>
            </tr>';
        }
        $pageurl='?p=/Tags/index&page3=';
        $pagebox3=pagebox($pageurl,$total,$pagenum,$page3,$prepg,$nextpg,'#tab=t2');
        
        $this->assign('list3', $list3);
        $this->assign('pagebox3', $pagebox3);

        $f=ROOT_PATH.'/cj/include/cj.fun.php';
        $h=xxx_url($f);
        preg_match('/\$words_tags=\'(.*?)\';/',$h,$x);
        $words_tags=$x[1];
        preg_match('/\$num_tags=\'(.*?)\';/',$h,$x);
        $num_tags=$x[1];
        preg_match('/\$auto_rep=\'(.*?)\';/',$h,$x);
        $auto_rep=$x[1];
        $this->assign('words_tags', $words_tags);
        $this->assign('num_tags', $num_tags);
        $this->assign('auto_rep', $auto_rep);
        $this->display('content/tags.html');
    }

    // 文章内链增加
    public function add()
    {
        global $dsql;
        if ($_POST) {
            // 获取数据
            $tags = post('tags');
            $jin = post('jin');
            $rep = post('rep');
            $tags=trim($tags);
            $jin=trim($jin);
            $rep=trim($rep);
            
            if (!$tags && !$jin && !$rep) {
                alert_back('关键词或近义词，至少填写一个！');
            }

            if($tags){
                $arr=explode("\n",$tags);
                foreach($arr as $xx){
                    $xx=trim($xx);
                    if(!$xx){
                        continue;
                    }
                    $xx=str_replace(array('，','|'),',',$xx);
                    $arr_=explode(',',$xx);
                    $name=$arr_[0];
                    $link=$arr_[1];
                    $name=trim($name);
                    $link=trim($link);
                    if(!$name || !$link){
                        continue;
                    }
                    // 构建数据
                    $data = array(
                        'acode' => session('acode'),
                        'name' => $name,
                        'link' => $link,
                        'create_user' => session('username'),
                        'update_user' => session('username')
                    );
                    $this->model->addTags($data);
                }
            }

            if($jin){
                $arr=explode("\n",$jin);
                foreach($arr as $xx){
                    $xx=trim($xx);
                    if(!$xx){
                        continue;
                    }
                    $xx=trim($xx);
                    if(!$xx){
                        continue;
                    }
                    $xx=str_replace(array('，','|'),',',$xx);
                    $x1="`key`";
                    $x2="'$xx'";
                    $dsql->amd("insert into ay_jin ($x1) values ($x2)");
                }
            }

            if($rep){
                $arr=explode("\n",$rep);
                foreach($arr as $xx){
                    $xx=trim($xx);
                    if(!$xx){
                        continue;
                    }
                    $xx=trim($xx);
                    if(!$xx){
                        continue;
                    }
                    $xx=str_replace(array('，','|'),',',$xx);
                    $x=explode(',',$xx);
                    $key1=$x[0];
                    $key2=$x[1];
                    $x1="`key1`,`key2`";
                    $x2="'$key1','$key2'";
                    $dsql->amd("insert into ay_rep ($x1) values ($x2)");
                }
            }

            $x='增加成功！';
            if($rep){
                $url='?p=/Tags/index#tab=t3';
            }else if($jin){
                $url='?p=/Tags/index#tab=t2';
            }else{
                $url='?p=/Tags/index';
            }
            success($x,$url,3);
        }
    }

    public function set()
    {
        global $dsql;
        $words_tags=gpvar('words_tags');
        $num_tags=gpvar('num_tags');
        $auto_rep=gpvar('auto_rep');
        if(!$words_tags || !is_numeric($words_tags)){
            $words_tags=0;
        }
        if(!$num_tags || !is_numeric($num_tags)){
            $num_tags=0;
        }
        if(!$auto_rep || !is_numeric($auto_rep)){
            $auto_rep=0;
        }
        $f=ROOT_PATH.'/cj/include/cj.fun.php';
        $h=xxx_url($f);
        preg_match('/\$words_tags=\'(.*?)\';/',$h,$x);
        $words_tags_=$x[0];
        preg_match('/\$num_tags=\'(.*?)\';/',$h,$x);
        $num_tags_=$x[0];
        preg_match('/\$auto_rep=\'(.*?)\';/',$h,$x);
        $auto_rep_=$x[0];
        $h=str_replace($words_tags_,'$words_tags=\''.$words_tags.'\';',$h);
        $h=str_replace($num_tags_,'$num_tags=\''.$num_tags.'\';',$h);
        $h=str_replace($auto_rep_,'$auto_rep=\''.$auto_rep.'\';',$h);
        file_put_contents($f,$h);
        $x='设置成功！';
        $url='?p=/Tags/index#tab=t5';
        success($x,$url,3);
    }

    // 文章内链删除
    public function del()
    {
        global $dsql;
        $id=gpvar('id');
        $id2=gpvar('id2');
        $id3=gpvar('id3');
        $idx1=gpvar('idx1');
        $idx2=gpvar('idx2');
        $idx3=gpvar('idx3');
        $idx11=gpvar('idx11');
        $idx22=gpvar('idx22');
        $idx33=gpvar('idx33');
        if (!$id && !$id2 && !$id3 && !$idx1 && !$idx2 && !$idx3 && !$idx11 && !$idx22 && !$idx33) {
            error('传递的参数值错误！', - 1);
        }
        if($id){
            $this->model->delTags($id);
            $url='?p=/Tags/index';
        }
        if($id2){
            $dsql->amd("delete from ay_jin where id=$id2 limit 1");
            $url='?p=/Tags/index#tab=t2';
        }
        if($id3){
            $dsql->amd("delete from ay_rep where id=$id3 limit 1");
            $url='?p=/Tags/index#tab=t3';
        }
        if($idx1 || $idx11){
            if($idx11){
                $dsql->amd("truncate ay_tags");
            }else{
                $arr=explode('-',$idx1);
                foreach($arr as $xx){
                    $xx=trim($xx);
                    if(!$xx || !is_numeric($xx)){
                        continue;
                    }
                    $dsql->amd("delete from ay_tags where id=$xx limit 1");
                }
            }
            $url='?p=/Tags/index#tab=t1';
        }
        if($idx2 || $idx22){
            if($idx22){
                $dsql->amd("truncate ay_jin");
            }else{
                $arr=explode('-',$idx2);
                foreach($arr as $xx){
                    $xx=trim($xx);
                    if(!$xx || !is_numeric($xx)){
                        continue;
                    }
                    $dsql->amd("delete from ay_jin where id=$xx limit 1");
                }
            }
            $url='?p=/Tags/index#tab=t2';
        }
        if($idx3 || $idx33){
            if($idx33){
                $dsql->amd("truncate ay_rep");
            }else{
                $arr=explode('-',$idx3);
                foreach($arr as $xx){
                    $xx=trim($xx);
                    if(!$xx || !is_numeric($xx)){
                        continue;
                    }
                    $dsql->amd("delete from ay_rep where id=$xx limit 1");
                }
            }
            $url='?p=/Tags/index#tab=t3';
        }
        success('删除成功！',$url, 3);
    }

    // 文章内链修改
    public function mod()
    {
        global $dsql;
        $id=gpvar('id');
        $id2=gpvar('id2');
        $id3=gpvar('id3');
        if (!$id && !$id2 && !$id3) {
            error('传递的参数值错误！', - 1);
        }

        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modTags($id, "$field='$value',update_user='" . session('username') . "'")) {
                location(- 1);
            } else {
                alert_back('修改失败！');
            }
        }
        
        // 修改操作
        if ($_POST) {

            if($id){
                // 获取数据
                $name = post('name');
                $link = post('link');
                
                if (! $name) {
                    alert_back('名称不能为空！');
                }
                
                if (! $link) {
                    alert_back('链接不能为空！');
                }
                
                // 构建数据
                $data = array(
                    'name' => $name,
                    'link' => $link,
                    'update_user' => session('username')
                );
                
                // 执行添加
                if ($this->model->modTags($id, $data)) {
                    $this->log('修改文章内链' . $id . '成功！');
                    if (! ! $backurl = get('backurl')) {
                        success('修改成功！', base64_decode($backurl));
                    } else {
                        success('修改成功！', url('/admin/Tags/index'));
                    }
                } else {
                    location(- 1);
                }
            }
            if($id2){
                $key = post('key');
                $key=str_replace(array('，','|'),',',$key);
                $dsql->amd("update ay_jin set `key`='$key' where id=$id2 limit 1");
                success('修改成功！', '?p=/Tags/index#tab=t2',3);
            }
            if($id3){
                $key1 = post('key1');
                $key2 = post('key2');
                $key1=str_replace(array('，','|'),',',$key1);
                $key2=str_replace(array('，','|'),',',$key2);
                $dsql->amd("update ay_rep set key1='$key1',key2='$key2' where id=$id3 limit 1");
                success('修改成功！', '?p=/Tags/index#tab=t3',3);
            }
        } else {
            // 调取修改内容
            if($id3){
                $result=$dsql->one("select * from ay_rep where id=$id3 limit 1",1);
                $this->assign('id3', $id3);
                $this->assign('mod', $id3);
            }else if($id2){
                $result=$dsql->one("select * from ay_jin where id=$id2 limit 1",1);
                $this->assign('id2', $id2);
                $this->assign('mod', $id2);
            }else{
                $this->assign('mod', true);
                if (! $result = $this->model->getTags($id)) {
                    error('编辑的内容已经不存在！', - 1);
                }
            }
            $this->assign('tags', $result);
            $this->display('content/tags.html');
        }
    }
}
function pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg,$jing=null){
    !$pagenum?$prepg='':'';
    if($prepg){
        $x=str_replace('&page=','',$pageurl);
        if($prepg==1){
            $xurl=$x;
        }else{
            $xurl=$pageurl.$prepg;
        }
        $pre='<a href="'.$xurl.$jing.'">前一页</a>';
        $first='<a href="'.$x.$jing.'">首页</a>';
    }else{
        $pre='<a class="no" href="javascript:">前一页</a>';
        $first='<a class="no" href="javascript:">首页</a>';
    }
    if($nextpg){
        $next='<a href="'.$pageurl.$nextpg.$jing.'">后一页</a>';
        $last='<a href="'.$pageurl.$pagenum.$jing.'">尾页</a>';
    }else{
        $next='<a class="no" href="javascript:">后一页</a>';
        $last='<a class="no" href="javascript:">尾页</a>';
    }
    if($pagenum=='1'){
        $numnav='<a class="page-num page-num-current" href="javascript:">1</a>';
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
                    $numnav.='<a class="page-num" href="'.$x.$jing.'">'.$i.'</a>';
                }
            }
        }else{
            for($i=($page-1);$i<=((($page+1)<=$pagenum)?($page+1):$pagenum);$i++){
                if($page==$i){
                    $numnav.='<a class="page-num page-num-current" href="javascript:">'.$i.'</a>';
                }else{
                    $numnav.='<a class="page-num" href="'.$pageurl.$i.$jing.'">'.$i.'</a>';
                }
            }
        }
        $numnav.='<a class="page-num" href="javascript:">...</a>';
        if($page!=$pagenum && $page!=($pagenum-1)){
            (($page+5)<=$pagenum)?$page_big=($page+5):$page_big=$pagenum;
            $numnav.='<a class="page-num" href="'.$pageurl.$page_big.$jing.'">'.$page_big.'</a>';
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