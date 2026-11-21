<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年12月26日
 *  内容专题控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\ContentSortModel;

class ZtController extends Controller
{

    private $count;

    private $blank;

    private $outData = array();

    private $model;

    public function __construct()
    {
        $this->model = new ContentSortModel();
    }

    // 内容专题列表
    public function index()
    {
        global $dsql;
        $this->assign('list', true);
        $dd=$dsql->all("select pcode,scode,name,status,title,keywords,description,filename,sorting from ay_zt where pcode=0 order by sorting asc,scode asc",1);
        foreach($dd as $xx){
            $pcode=$xx[pcode];
            $scode=$xx[scode];
            $name=$xx[name];
            $status=$xx[status];
            $title=$xx[title];
            $keywords=$xx[keywords];
            $description=$xx[description];
            $filename=$xx[filename];
            $sorting=$xx[sorting];
            $sub='';
            $sub_count=0;
            if($pcode==0){
                $dd2=$dsql->all("select pcode,scode,name,status,title,keywords,description,filename,sorting from ay_zt where pcode=$scode order by sorting asc,scode asc",1);
                foreach($dd2 as $xx2){
                    $pcode2=$xx2[pcode];
                    $scode2=$xx2[scode];
                    $name2=$xx2[name];
                    $status2=$xx2[status];
                    $title2=$xx2[title];
                    $keywords2=$xx2[keywords];
                    $description2=$xx2[description];
                    $filename2=$xx2[filename];
                    $sorting2=$xx2[sorting];
                    $x=$dsql->one("select count(*) from ay_ztlist where zt like '%,$scode2,%'",1);
                    $count2=$x['count(*)'];
                    $sub_count=$sub_count+$count2;
                    if($status2==1){
                        $status2='<a href="?p=/Zt/mod/scode/'.$scode2.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击禁用"></i></a>';
                    }else{
                        $status2='<a href="?p=/Zt/mod/scode/'.$scode2.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击启用"></i></a>';
                    }
                    $sub.='
                    <tr data-tt-id="'.$scode2.'" data-tt-parent-id="'.$scode.'" class="leaf collapsed" style="display:none;">
                        <td>
                            <input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$scode2.'">
                            <input type="hidden" name="listall[]" value="'.$scode2.'">
                        </td>
                        <td>
                            <a href="?p=/Zt/list/scode/'.$scode2.'">
                                <i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;&nbsp;'.$name2.'<span style="color:brown">('.$count2.')</span>
                            </a>
                        </td>
                        <td>'.$scode2.'</td>
                        <td>'.$filename2.'</td>
                        <td class="table-input"><input type="text" name="sorting[]" value="'.$sorting2.'" class="layui-input"></td>
                        <td>'.$status2.'</td>
                        <td>
                            <a href="?p=/Zt/list/scode/'.$scode2.'" class="layui-btn layui-btn-xs">内容管理</a>
                            <a href="?p=/Zt/del/scode/'.$scode2.'" onclick="return confirm(&quot;您确定要删除么？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                            <a href="?p=/Zt/mod/scode/'.$scode2.get_btn_qs().'" class="layui-btn layui-btn-xs">编辑</a>
                        </td>
                    </tr>';
                }
            }
            $x=$dsql->one("select count(*) from ay_ztlist where zt like '%,$scode,%'",1);
            $count=$x['count(*)'];
            $count=$count+$sub_count;
            if($status==1){
                $status='<a href="?p=/Zt/mod/scode/'.$scode.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击禁用"></i></a>';
            }else{
                $status='<a href="?p=/Zt/mod/scode/'.$scode.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击启用"></i></a>';
            }
            $sorts.='
            <tr data-tt-id="'.$scode.'" data-tt-parent-id="'.$pcode.'" class="branch collapsed">
                <td>
                    <input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$scode.'">
                    <input type="hidden" name="listall[]" value="'.$scode.'">
                </td>
                <td>
                    <a href="?p=/Zt/list/scode/'.$scode.'">
                        <i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;&nbsp;'.$name.'<span style="color:brown">('.$count.')</span>
                    </a>
                </td>
                <td>'.$scode.'</td>
                <td>'.$filename.'</td>
                <td class="table-input"><input type="text" name="sorting[]" value="'.$sorting.'" class="layui-input"></td>
                <td>'.$status.'</td>
                <td>
                    <a href="?p=/Zt/list/scode/'.$scode.'" class="layui-btn layui-btn-xs">内容管理</a>
                    <a href="?p=/Zt/del/scode/'.$scode.'" onclick="return confirm(&quot;您确定要删除么？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                    <a href="?p=/Zt/mod/scode/'.$scode.get_btn_qs().'" class="layui-btn layui-btn-xs">编辑</a>
                </td>
            </tr>';
            $sorts.=$sub;
        }
        $this->assign('sorts', $sorts);

        $dd=$dsql->all("select scode,name,pcode from ay_zt where pcode=0 order by sorting asc,scode asc",1);
        foreach($dd as $xx){
            $scode=$xx[scode];
            $name=$xx[name];
            $pcode=$xx[pcode];
            $sort_select.='<option value="'.$scode.'">'.$name.'</option>';
            if($pcode==0){
                $dd=$dsql->all("select scode,name from ay_zt where pcode=$scode order by sorting asc,scode asc",1);
                foreach($dd as $xx){
                    $scode2=$xx[scode];
                    $name2=$xx[name];
                    $sort_select.='<option value="'.$scode2.'">　　'.$name2.'</option>';
                }
            }
        }
        $this->assign('sort_select',$sort_select);

        $this->display('content/zt.html');
    }

    // 内容专题增加
    public function add()
    {
        global $dsql;
        if ($_POST) {
            if (! ! $multiplename = post('multiplename')) {
                $multiplename = str_replace('，', ',', $multiplename);
                $pcode = post('pcode', 'var');
                $status = post('status');
                if (! $pcode) { // 父编码默认为0
                    $pcode = 0;
                }
                require_once(ROOT_PATH.'/include/py.php');
                $names = explode(',', $multiplename);
                foreach ($names as $key => $value) {
                    $filename=py($value);
                    $data[] = array(
                        'pcode' => $pcode,
                        'name' => $value,
                        'status' => $status,
                        'filename' => $filename,
                        'title' => '',
                        'keywords' => '',
                        'description' => '',
                        'sorting' => 255
                    );
                    $scode = get_auto_code($scode);
                }
            } else {
                // 获取数据
                $pcode = post('pcode', 'var');
                $name = post('name');
                $status = post('status');
                $filename = post('filename');
                $title = post('title');
                $keywords = post('keywords');
                $description = post('description');
                
                if (! $pcode) { // 父编码默认为0
                    $pcode = 0;
                }
                
                if (! $name) {
                    alert_back('专题名不能为空！');
                }

                if(!$filename){
                    require_once(ROOT_PATH.'/include/py.php');
                    $filename=py($name);
                }
                
                if ($filename && ! preg_match('/^[a-zA-Z0-9\-]+$/', $filename)) {
                    alert_back('URL名称只允许字母、数字、横线组成!');
                }
                
                // 检查自定义URL名称
                if ($filename) {
                    while ($this->model->checkFilename($filename)) {
                        $filename = $filename . '_' . mt_rand(1, 20);
                    }
                }
                
                // 构建数据
                $data[] = array(
                    'pcode' => $pcode,
                    'name' => $name,
                    'status' => $status,
                    'filename' => $filename,
                    'title' => $title,
                    'keywords' => $keywords,
                    'description' => $description,
                    'sorting' => 255
                );
            }

            foreach($data as $xx){
                $x1='';
                $x2='';
                foreach($xx as $k => $v){
                    $x1.=$k.',';
                    $x2.="'".$v."',";
                }
                $x1=trim($x1,',');
                $x2=trim($x2,',');
                $o=$dsql->amd("insert into ay_zt ($x1) values ($x2)");
                if(!$o){
                    error('新增失败！', - 1);
                }
            }
            
            // 执行添加
            if ($o) {
                $url='?p=/Zt/index';
                success('新增成功！',$url);
            } else {
                error('新增失败！', - 1);
            }
        }
    }

    // 生成内容专题下拉选择
    private function makeSortSelect($tree, $selectid = null)
    {
        $list_html = '';
        foreach ($tree as $value) {
            // 默认选择项
            if ($selectid == $value->scode) {
                $select = "selected='selected'";
            } else {
                $select = '';
            }
            if (get('scode') != $value->scode) { // 不显示本身，避免出现自身为自己的父节点
                $list_html .= "<option value='{$value->scode}' $select>{$this->blank}{$value->name}</option>";
            }
            // 子菜单处理
            if ($value->son) {
                $this->blank .= '　　';
                $list_html .= $this->makeSortSelect($value->son, $selectid);
            }
        }
        // 循环完后回归位置
        $this->blank = substr($this->blank, 0, - 6);
        return $list_html;
    }

    // 内容专题删除
    public function del()
    {
        global $dsql;
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                foreach($list as $xx){
                    $o=$dsql->amd("delete from ay_zt where scode=$xx or pcode=$xx");
                    if(!$o){
                        error('批量删除失败！', - 1);
                    }
                    $x=','.$xx.',';
                    $dsql->amd("delete from ay_ztlist where zt like '%$x%'");
                }
                if ($o) {
                    success('批量删除成功！', - 1);
                } else {
                    error('批量删除失败！', - 1);
                }
            } else {
                alert_back('请选择要删除的专题！');
            }
        }
        
        if (! $scode = get('scode', 'var')) {
            error('传递的参数值错误！', - 1);
        }
        $o=$dsql->amd("delete from ay_zt where scode=$scode or pcode=$scode");
        if ($o) {
            $x=','.$scode.',';
            $dsql->amd("delete from ay_ztlist where zt like '%$x%'");
            success('删除成功！', - 1);
        } else {
            error('删除失败！', - 1);
        }
    }

    // 内容专题修改
    public function mod(){
        global $dsql;
        if (! ! $submit = post('submit')) {
            switch ($submit) {
                case 'sorting': // 修改列表排序
                    $listall = post('listall');
                    if ($listall) {
                        $sorting = post('sorting');
                        foreach ($sorting as $key => $value) {
                            if(!$value){
                                $value=255;
                            }
                            $scode=$listall[$key];
                            $dsql->amd("update ay_zt set sorting=$value where scode=$scode limit 1");
                        }
                        success('修改成功！', - 1);
                    } else {
                        alert_back('排序失败，无任何内容！');
                    }
                    break;
                case 'status_1':
                    $list=post('list');
                    if($list){
                        foreach($list as $k => $v){
                            if(!$v || !is_numeric($v)){
                                continue;
                            }
                            $dsql->amd("update ay_zt set status=1 where scode=$v limit 1");
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
                            $dsql->amd("update ay_zt set status=0 where scode=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
                case 'zt_0':
                    $list=post('list');
                    if($list){
                        foreach($list as $xx){
                            $scode_='';
                            $sql='';
                            $dd=$dsql->all("select scode from ay_zt where pcode=$xx",1);
                            foreach($dd as $xx2){
                                $scode=$xx2[scode];
                                $scode_.=$scode.',';
                            }
                            $scode_=trim($scode_,',');
                            if($scode_){
                                $scode_=$xx.','.$scode_;
                            }else{
                                $scode_=$xx;
                            }
                            $arr=explode(',',$scode_);
                            foreach($arr as $xx2){
                                $sql.="zt like '%,$xx2,%'_";
                            }
                            $sql=trim($sql,'_');
                            $sql=str_replace('_',' or ',$sql);
                            $dd=$dsql->all("select id,zt from ay_ztlist where $sql",1);
                            foreach($dd as $xx2){
                                $id=$xx2[id];
                                $zt=$xx2[zt];

                                foreach($arr as $xx3){
                                    $xx3=','.$xx3.',';
                                    $zt=str_replace($xx3,',',$zt);
                                }
                                $zt=trim($zt,',');
                                if(!$zt){
                                    $dsql->amd("delete from ay_ztlist where id=$id limit 1");
                                }else{
                                    $zt=','.$zt.',';
                                    $dsql->amd("update ay_ztlist set zt='$zt' where id=$id limit 1");
                                }
                            }
                        }
                        success('移除成功！',-1);
                    }else{
                        alert_back('请选择要移除的项目！');
                    }
                    break;
            }
        }
        
        if (! $scode = get('scode', 'var')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            $o=$dsql->amd("update ay_zt set $field=$value where scode=$scode limit 1");
            if ($o) {
                location(- 1);
            } else {
                alert_back('修改失败！');
            }
        }
        
        // 修改操作
        if ($_POST) {
            
            // 获取数据
            $pcode = post('pcode', 'var');
            $name = post('name');
            $status = post('status');
            $filename = post('filename');
            $title = post('title');
            $keywords = post('keywords');
            $description = post('description');
            
            if (! $pcode) { // 父编码默认为0
                $pcode = 0;
            }
            
            if (! $name) {
                alert_back('专题名不能为空！');
            }
            
            if ($filename && ! preg_match('/^[a-zA-Z0-9\-]+$/', $filename)) {
                alert_back('URL名称只允许字母、数字、横线组成!');
            }
            
            // 构建数据
            $data = array(
                'pcode' => $pcode,
                'name' => $name,
                'status' => $status,
                'filename' => $filename,
                'title' => $title,
                'keywords' => $keywords,
                'description' => $description
            );

            foreach($data as $k => $v){
                $xxx.="$k='$v',";
            }
            $xxx=trim($xxx,',');
            $dsql->amd("update ay_zt set $xxx where scode=$scode limit 1");
            $url='?p=/Zt/index';
            success('修改成功！',$url);
        } else { // 调取修改内容
            $this->assign('mod', true);
            $sort=$dsql->one("select * from ay_zt where scode=$scode limit 1",1);
            if (! $sort) {
                error('编辑的内容已经不存在！', - 1);
            }
            $this->assign('sort', $sort);

            $pcode=$sort[pcode];
            
            // 父编码下拉选择
            $dd=$dsql->all("select scode,name,pcode from ay_zt where pcode=0 order by sorting asc,scode asc",1);
            foreach($dd as $xx){
                $scode2=$xx[scode];
                $name2=$xx[name];
                $pcode2=$xx[pcode];
                if($pcode==$scode2){
                    $s=' selected="selected"';
                }else{
                    $s='';
                }
                $sort_select.='<option value="'.$scode2.'"'.$s.'>'.$name2.'</option>';
                if($pcode2==0){
                    $dd=$dsql->all("select scode,name from ay_zt where pcode=$scode2 order by sorting asc,scode asc",1);
                    foreach($dd as $xx){
                        $scode3=$xx[scode];
                        $name3=$xx[name];
                        $sort_select.='<option value="'.$scode3.'">　　'.$name3.'</option>';
                    }
                }
            }
            $this->assign('sort_select',$sort_select);
            
            $this->display('content/zt.html');
        }
    }
    public function list(){
        global $dsql;
        $OnePAGE_NUM=50;
        $scode=get('scode','int');
        $page=get('page','int');
        if(($scode && !is_numeric($scode)) || ($page && !is_numeric($page))){
            error('传递的参数值错误！',-1);
        }
        $x=$dsql->one("select name,pcode from ay_zt where scode=$scode limit 1",1);
        $name=$x[name];
        $pcode=$x[pcode];
        $dd=$dsql->all("select scode from ay_zt where pcode=$scode",1);
        foreach($dd as $xx){
            $scode2=$xx[scode];
            $scode_.="zt like '%,$scode2,%'_";
        }
        $scode_=trim($scode_,'_');
        $scode_=str_replace('_',' or ',$scode_);
        if($scode_){
            $sql="zt like '%,$scode,%' or ".$scode_;
        }else{
            $sql="zt like '%,$scode,%'";
        }
        $x=$dsql->one("select count(*) from ay_ztlist where $sql",1);
        $total=$x["count(*)"];
        $pagenum=ceil($total/$OnePAGE_NUM);
        $page?'':$page='1';
        $page=min($pagenum,$page);!$page?$page=1:"";
        $prepg=$page-1;
        $nextpg=($page==$pagenum?0:$page+1);
        $offset=($page-1)*$OnePAGE_NUM;
        $dd=$dsql->all("select id from ay_ztlist where $sql order by id desc limit $offset,$OnePAGE_NUM",1);
        foreach($dd as $xx){
            $id=$xx[id];
            $xx=$dsql->one("select title,update_time,sorting,status,istop,isrecommend,isheadline,visits,scode from ay_content where id=$id limit 1",1);
            $title=$xx[title];
            $update_time=$xx[update_time];
            $sorting=$xx[sorting];
            $status=$xx[status];
            $istop=$xx[istop];
            $isrecommend=$xx[isrecommend];
            $isheadline=$xx[isheadline];
            $visits=$xx[visits];
            $scodecur=$xx[scode];
            $x=$dsql->one("select name from ay_content_sort where scode=$scodecur limit 1",1);
            $namecur=$x[name];
            if($status==1){
                $status='<a href="?p=/Sort/mod/id/'.$id.'/field/status/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
            }else{
                $status='<a href="?p=/Sort/mod/id/'.$id.'/field/status/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
            }
            if($istop==1){
                $istop='<a href="?p=/Sort/mod/id/'.$id.'/field/istop/value/0"><i class="fa fa-toggle-on" title="点击关闭"></i></a>';
            }else{
                $istop='<a href="?p=/Sort/mod/id/'.$id.'/field/istop/value/1"><i class="fa fa-toggle-off" title="点击开启"></i></a>';
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
            $fa='<i class="fa fa-edit" aria-hidden="true"></i>&nbsp;&nbsp;';
            $url=pbcode('content',$id);
            $editurl='';
            $editurl='?p=/Sort/edit/id/'.$id;
            $ztlist.='
            <tr>
                <td><input type="checkbox" class="checkitem" lay-ignore="" name="list[]" value="'.$id.'"></td>
                <td>'.$id.'</td>
                <td><a href="'.$editurl.'">'.$fa.$title.'</a></td>
                <td>'.$namecur.'</td>
                <td>'.$update_time.'</td>
                <td class="table-input">
                    <input type="text" lay-ignore="" class="layui-input" name="sorting['.$id.']" value="'.$sorting.'">
                </td>
                <td>'.$status.'</td>
                <td>'.$istop.'</td>
                <td>'.$isrecommend.'</td>
                <td>'.$isheadline.'</td>
                <td>'.$visits.'</td>
                <td>
                    <a href="'.$url.'" class="layui-btn layui-btn-xs layui-btn-primary" target="_blank">预览</a>
                    '.$chapter.'
                    <a href="?p=/Sort/del/id/'.$id.'" onclick="return del(1)" class="layui-btn layui-btn-xs layui-btn-danger" title="删除">删除</a>
                    <a href="?p=/Sort/edit/id/'.$id.'" class="layui-btn layui-btn-xs">编辑</a>
                </td>
            </tr>';
        }
        $pageurl='?p=/Zt/list/scode/'.$scode.'&page=';
        if(!$ztlist){
            $ztlist='<tr><td colspan="11" style="text-align:center">暂无相关内容...</td></tr>';
        }
        $pagebox=pagebox($pageurl,$total,$pagenum,$page,$prepg,$nextpg);
        if(!$nav){
            $nav='
            <a href="?p=/Zt/list/scode/'.$scode.'">'.$name.'</a>';
        }
        $loc='
        <a href="?p=/Zt/index"><i class="fa fa-home"></i>专题管理</a>
        &nbsp;&gt;&nbsp;
        '.$nav;
        $pbstr='loc,ztlist,pagebox,scode';
        $pbstr=explode(',',$pbstr);
        foreach($pbstr as $v){
            $pbarr[ $v ]=$$v;
        }
        $this->assign('pbarr',$pbarr);
        $this->display('content/zt.html');
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