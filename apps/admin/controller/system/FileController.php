<?php
namespace app\admin\controller\system;
use core\basic\Controller;
use core\basic\Config;
use core\basic\Model;
class FileController extends Controller
{
    public function index()
    {
        global $dsql,$pcbase;
        $x=$dsql->one("select theme from ay_site where id=1 limit 1",1);
        $tpl=$x[theme];
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $dir0=$arr[0];
        $loc=$arr[1];
        if(!$dir0 && $dir0!=='0'){
            $dir=ROOT_PATH;
        }else{
            $dir=ROOT_PATH.'/'.$dir0;
        }
        if ($_POST) {
            if (isset($_POST)){
                $name=$_FILES['file']['name'];
                $size=$_FILES['file']['size'];
                $name_tmp=$_FILES['file']['tmp_name'];
                if ($name){
                    $url=$dir.'/'.$name;
                    // $url=iconv('UTF-8','GB2312//IGNORE',$url);
                    if (move_uploaded_file($name_tmp,$url)){
                        $xxx='ok';
                    }
                }
            }
            echo $xxx;
            exit;
        }
        // $dir=iconv('UTF-8','GB2312//IGNORE',$dir);
        $arr=path_list($dir);
        foreach ($arr as $k => $v) {
            $fn=$v;
            // $fn2=iconv('GB2312//IGNORE','UTF-8',$fn);
            $fn2=$fn;
            $arr=pathinfo($fn2);
            $ext=$arr['extension'];
            $dircur=$dir.'/'.$fn;
            if($dir0 || $dir0=='0'){
                $fn22=$dir0.'/'.$fn2;
            }else{
                $fn22=$fn2;
            }
            if(!$fn2 && $fn2!=='0'){
                continue;
            }
            $fn222=$this->str2str($fn22);
            $fn33=str_replace('&','&amp;',$fn22);
            if(is_dir($dircur)){
                $dir_list.='
                <tr>
                    <td>
                        <a href="'.url('/admin/File/index/'.$fn33).'"><i class="fa fa-folder-open"></i>'.$fn2.'</a>
                    </td>
                    <td>---</td>
                    <td>---</td>
                    <td>
                        <a class="layui-btn layui-btn-xs layui-btn-primary no">编辑</a>
                        <a href="'.url('/admin/File/rename/'.$fn222).'" class="layui-btn layui-btn-xs layui-btn-primary">改名</a>
                        <a href="'.url('/admin/File/del/'.$fn222).'" onclick="return confirm(&quot;您确定要删除 “'.$fn2.'” 吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger">删除</a>
                    </td>
                </tr>';
            }else if(is_file($dircur)){
                $fs=filesize($dircur);
                $fs=$fs/1024;
                if($fs>1024){
                    $fs=$fs/1024;
                    $fs=round($fs,2);
                    $fs.='M';
                }else{
                    $fs=round($fs,2);
                    $fs.='K';
                }
                $ft=filemtime($dircur);
                $iw='';
                $no='';
                $faz='';
                if($ext=='htm' || $ext=='html' || $ext=='shtml'){
                    $fa='fa-internet-explorer';
                    $iw=1;
                }else if($ext=='jpg' || $ext=='gif' || $ext=='png' || $ext=='jepg' || $ext=='ico'){
                    $fa='fa-file-image-o';
                }else if($ext=='php' || $ext=='js' || $ext=='css' || $ext=='htaccess' || $ext=='config' || $ext=='txt' || $ext=='xml'){
                    $fa='fa-file-text-o';
                    $iw=1;
                }else if($ext=='zip'){
                    $fa='fa-file-archive-o';
                    $faz='<a href="'.url('/admin/File/unzip/'.$fn222).'" onclick="return confirm(&quot;您确定要解压吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-primary">解压</a>';
                }else{
                    $fa='fa-file-o';
                }
                if(!$iw){
                    $no=' no';
                }
                $tr='
                <tr>
                    <td>
                        <a href="'.$pcbase.$fn33.'" target="_blank"><i class="fa '.$fa.'"></i>'.$fn2.'</a>
                    </td>
                    <td>'.$fs.'</td>
                    <td>'.date('Y-m-d H:i:s',$ft).'</td>
                    <td>
                        <a href="'.url('/admin/File/edit/'.$fn222).'" class="layui-btn layui-btn-xs layui-btn-primary'.$no.'">编辑</a>
                        <a href="'.url('/admin/File/rename/'.$fn222).'" class="layui-btn layui-btn-xs layui-btn-primary">改名</a>
                        '.$faz.'
                        <a href="'.url('/admin/File/del/'.$fn222).'" onclick="return confirm(&quot;您确定要删除 “'.$fn2.'” 吗？&quot;)" class="layui-btn layui-btn-xs layui-btn-danger">删除</a>
                    </td>
                </tr>';
                $x1='template/'.$tpl.'/image';
                $x2='wap/style/image';
                if((strstr($dircur,$x1) || strstr($dircur,$x2)) && $fn2=='logo.png'){
                    $logo=str_replace('target="_blank"','target="_blank" style="color:red"',$tr);
                }else{
                    $file_list.=$tr;
                }
            }
        }
        $filelist=$dir_list.$logo.$file_list;
        if(!$filelist){
            $filelist='<tr><td colspan="4">当前目录无文件</td></tr>';
        }
        $x=ROOT_PATH.'/template/'.$tpl.'/image';
        if(is_dir($x)){
            $pclogo='<a href="'.url('/admin/File/index/template/tpl/image').'" class="layui-btn layui-btn-sm">直达 Logo (pc)</a>';
        }
        $x=ROOT_PATH.'/wap/style/image';
        if(is_dir($x)){
            $waplogo='<a href="'.url('/admin/File/index/wap/style/image').'" class="layui-btn layui-btn-sm">直达 Logo (wap)</a>';
        }
        $this->assign('template',__FUNCTION__);
        $this->assign('loc',$loc);
        $this->assign('filelist',$filelist);
        $this->assign('pclogo',$pclogo);
        $this->assign('waplogo',$waplogo);
        $this->assign('dir',$dir0);
        $this->display('system/file.html');
    }
    public function del()
    {
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $x=$arr[0];
        $dircur=ROOT_PATH.'/'.$x;
        // $dircur=iconv('UTF-8','GB2312//IGNORE',$dircur);
        $ok=path_delete($dircur,'del');
        if($ok){
            success('删除成功！',-1);
        }else{
            alert_back('无法删除，请检查目录是否有相关权限！');
        }
    }
    public function rename()
    {
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $dir=$arr[0];
        $loc=$arr[1];
        $arr=explode('/',$dir);
        $name=$arr[count($arr)-1];
        if ($_POST) {
            $name2=gpvar('name');
            $f1=ROOT_PATH.'/'.$dir;
            $f2=str_replace('/'.$name,'/'.$name2,$f1);
            if(file_exists($f2)){
                error('重命名失败！该目录中已存在同名文件~',-1);
            }else{
                // $f1=iconv('UTF-8','GB2312//IGNORE',$f1);
                // $f2=iconv('UTF-8','GB2312//IGNORE',$f2);
                rename($f1,$f2);
                if(file_exists($f2)){
                    $x=str_replace($name,'',$dir);
                    $x=ltrim($x,'/');
                    $x=url('/admin/File/index/'.$x);
                    success('重命名成功！',$x);
                }else{
                    alert_back('重命名失败！请稍后重试~');
                }
            }
            exit;
        }
        $this->assign('template',__FUNCTION__);
        $this->assign('loc',$loc);
        $this->assign('name',$name);
        $this->assign('dir',$dir);
        $this->display('system/file.html');
    }
    public function edit()
    {
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $dir=$arr[0];
        $loc=$arr[1];
        $arr=explode('/',$dir);
        $name=$arr[count($arr)-1];
        $f=ROOT_PATH.'/'.$dir;
        // $f=iconv('UTF-8','GB2312//IGNORE',$f);
        if ($_POST) {
            $content=$_POST['content'];
            file_put_contents($f,$content);
            $c=file_get_contents($f);
            if($c==$content){
                $x='/'.$name;
                if(!strstr($dir,$x)){
                    $x=$name;
                }
                $x=str_replace($x,'',$dir);
                $x=url('/admin/File/index/'.$x);
                success('编辑成功！',$x);
            }else{
                alert_back('编辑失败！请检查文件是否有可写权限~');
            }
            exit;
        }
        $arr=explode('.',$f);
        $ext=$arr[count($arr)-1];
        $ext=strtolower($ext);
        if($ext!='php' && $ext!='js' && $ext!='css' && $ext!='htaccess' && $ext!='config' && $ext!='txt' && $ext!='xml' && $ext!='htm' && $ext!='html' && $ext!='shtml'){
            $x='?p=/File/index';
            error('此文件不允许编辑！',$x);
        }
        if(!file_exists($f)){
            $x='?p=/File/index';
            error('此文件不存在！',$x);
        }
        $content=file_get_contents($f);
        $content=escape_string($content);
        $content=decode_slashes($content);
        $this->assign('template',__FUNCTION__);
        $this->assign('loc',$loc);
        $this->assign('name',$name);
        $this->assign('content',$content);
        $this->assign('dir',$dir);
        $this->display('system/file.html');
    }
    public function unzip()
    {
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $dir=$arr[0];
        $loc=$arr[1];
        $arr=explode('/',$dir);
        $name=$arr[count($arr)-1];
        $f=ROOT_PATH.'/'.$dir;
        $arr=explode('.',$f);
        $ext=$arr[count($arr)-1];
        $ext=strtolower($ext);
        if($ext!='zip'){
            $x='?p=/File/index';
            error('此文件不允许解压！',$x);
        }
        if(!file_exists($f)){
            $x='?p=/File/index';
            error('此文件不存在！',$x);
        }
        $x=unzip($f);
        if($x){
            $arr=explode(ROOT_PATH,$x);
            $x=$arr[1];
            $x=ltrim($x,'/');
            $x=url('/admin/File/index/'.$x);
            success('解压成功！',$x);
        }else{
            alert_back('解压失败！请检查ZipArchive类是否已开启~');
        }
    }
    public function newf()
    {
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $dir=$arr[0];
        $loc=$arr[1];
        if ($_POST) {
            $name=gpvar('name',0,0);
            $content=gpvar('content',0,0);
            $f=ROOT_PATH.'/'.$dir.'/'.$name;
            // $f=iconv('UTF-8','GB2312//IGNORE',$f);
            $arr=explode('.',$f);
            $ext=$arr[count($arr)-1];
            $ext=strtolower($ext);
            if($ext!='php' && $ext!='js' && $ext!='css' && $ext!='htaccess' && $ext!='config' && $ext!='txt' && $ext!='xml' && $ext!='htm' && $ext!='html' && $ext!='shtml'){
                error('无效的文件格式！',-1);
            }
            if(is_file($f)){
                alert_back('当前目录已存在同名文件！');
            }else{
                file_put_contents($f,$content);
                if(is_file($f)){
                    $x=url('/admin/File/index/'.$dir);
                    success('新建文件成功！',$x);
                }else{
                    alert_back('新建文件失败！请检查目录是否有可写权限~');
                }
            }
            exit;
        }
        $this->assign('template',__FUNCTION__);
        $this->assign('loc',$loc);
        $this->assign('dir',$dir);
        $this->display('system/file.html');
    }
    public function newd()
    {
        $x=$this->loc();
        $arr=explode('{697}',$x);
        $dir=$arr[0];
        $loc=$arr[1];
        if ($_POST) {
            $name=gpvar('name',0,0);
            $f=ROOT_PATH.'/'.$dir.'/'.$name;
            // $f=iconv('UTF-8','GB2312//IGNORE',$f);
            if(is_dir($f)){
                alert_back('当前目录已存在同名文件夹！');
            }else{
                mkdir($f);
                if(is_dir($f)){
                    $x=url('/admin/File/index/'.$dir);
                    success('新建目录成功！',$x);
                }else{
                    alert_back('新建目录失败！请检查目录是否有可写权限~');
                }
            }
            exit;
        }
        $this->assign('template',__FUNCTION__);
        $this->assign('loc',$loc);
        $this->assign('dir',$dir);
        $this->display('system/file.html');
    }
    public function loc()
    {
        $p=gpvar('p');
        $p=$this->str2str($p,0);
        if(strstr($p,'File/edit')){
            $exp='File/edit';
        }else if(strstr($p,'File/rename')){
            $exp='File/rename';
        }else if(strstr($p,'File/del')){
            $exp='File/del';
        }else if(strstr($p,'File/unzip')){
            $exp='File/unzip';
        }else if(strstr($p,'File/newf')){
            $exp='File/newf';
        }else if(strstr($p,'File/newd')){
            $exp='File/newd';
        }else{
            $exp='File/index';
        }
        $arr=explode($exp,$p);
        $x=$arr[1];
        $x=trim($x,'/');
        $arr=explode('/',$x);
        $count=count($arr);
        if(!$count){
            return false;
        }else{
            foreach($arr as $v){
                $ii++;
                if(!$v && $v!=='0'){
                    continue;
                }
                $d.=$v.'/';
                if($ii==$count && $exp=='File/rename'){
                    $l.='&nbsp;&gt;&nbsp;'.$v.'&nbsp;&gt;&nbsp;重命名';
                }else if($ii==$count && $exp=='File/edit'){
                    $l.='&nbsp;&gt;&nbsp;'.$v.'&nbsp;&gt;&nbsp;编辑';
                }else{
                    $l.='&nbsp;&gt;&nbsp;<a href="'.url('/admin/File/index/'.$d).'">'.$v.'</a>';
                }
            }
        }
        $d=rtrim($d,'/');
        if($l){
            $l='<a href="'.url('/admin/File/index').'"><i class="fa fa-home"></i>根目录</a>'.$l;
        }
        $xxx=$d.'{697}'.$l;
        return $xxx;
    }
    public function str2str($xxx,$x=1)
    {
        $arr=array(
            '('=>'..1..',
            ')'=>'..2..',
            '（'=>'..3..',
            '）'=>'..4..',
            '['=>'..5..',
            ']'=>'..6..',
            '+'=>'..7..',
            '&'=>'..8..',
            ';'=>'..9..',
            ','=>'..10..',
            ' '=>'..11..',
        );
        foreach($arr as $k => $v){
            if($x){
                $xxx=str_replace($k,$v,$xxx);
            }else{
                $xxx=str_replace($v,$k,$xxx);
            }
        }
        return $xxx;
    }
}