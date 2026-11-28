<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年12月26日
 *  内容栏目控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\ContentSortModel;

class ContentSortController extends Controller
{

    private $count;

    private $blank;

    private $outData = array();

    private $model;

    public function __construct()
    {
        $this->model = new ContentSortModel();
    }

    // 内容栏目列表
    public function index()
    {
        $this->assign('list', true);
        $tree = $this->model->getList();
        $sorts = $this->makeSortList($tree);
        $this->assign('sorts', $sorts);
        
        // 内容模型
        $models = model('admin.content.Model');
        $this->assign('allmodels', $models->getSelectAll());
        $this->assign('models', $models->getSelect());
        
        // 内容栏目下拉表
        $sort_tree = $this->model->getSelect();
        $sort_select = $this->makeSortSelect($sort_tree);
        $this->assign('sort_select', $sort_select);
        
        // 模板文件
        $htmldir = $this->config('tpl_html_dir') ? '/' . $this->config('tpl_html_dir') : '';
        $this->assign('tpls', file_list(ROOT_PATH . current($this->config('tpl_dir')) . '/' . $this->model->getTheme() . $htmldir));
        
        // 前端地址连接符判断
        $url_break_char = $this->config('url_break_char') ?: '_';
        $this->assign('url_break_char', $url_break_char);
        
        // 获取会员分组
        $this->assign('groups', model('admin.member.MemberGroup')->getSelect());

        global $dsql;
        $x=$dsql->one("SHOW TABLES LIKE 'ay_member_code'",1);
        if($x){
            $pan=1;
        }
        $pbstr='pan';
        $pbstr=explode(',',$pbstr);
        foreach($pbstr as $v){
            $pbarr[ $v ]=$$v;
        }
        $this->assign('pbarr',$pbarr);
        
        $this->display('content/contentsort.html');
    }

    // 生成无限级内容栏目列表
    private function makeSortList($tree)
    {
        // 循环生成
        foreach ($tree as $value) {
            $this->count ++;
            $this->outData[$this->count] = new \stdClass();
            $this->outData[$this->count]->id = $value->id;
            $this->outData[$this->count]->blank = $this->blank;
            $this->outData[$this->count]->name = $value->name;
            $this->outData[$this->count]->subname = $value->subname;
            $this->outData[$this->count]->scode = $value->scode;
            $this->outData[$this->count]->pcode = $value->pcode;
            $this->outData[$this->count]->mcode = $value->mcode;
            $this->outData[$this->count]->listtpl = $value->listtpl;
            $this->outData[$this->count]->contenttpl = $value->contenttpl;
            $this->outData[$this->count]->ico = $value->ico;
            $this->outData[$this->count]->pic = $value->pic;
            $this->outData[$this->count]->keywords = $value->keywords;
            $this->outData[$this->count]->description = $value->description;
            $this->outData[$this->count]->outlink = $value->outlink;
            $this->outData[$this->count]->sorting = $value->sorting;
            $this->outData[$this->count]->status = $value->status;
            $this->outData[$this->count]->filename = $value->filename;
            $this->outData[$this->count]->type = $value->type;
            $this->outData[$this->count]->urlname = $value->urlname;
            $this->outData[$this->count]->create_user = $value->create_user;
            $this->outData[$this->count]->update_user = $value->update_user;
            $this->outData[$this->count]->create_time = $value->create_time;
            $this->outData[$this->count]->update_time = $value->update_time;
            
            if ($value->son) {
                $this->outData[$this->count]->son = true;
            } else {
                $this->outData[$this->count]->son = false;
            }
            
            // 子菜单处理
            if ($value->son) {
                $this->blank .= '　　';
                $this->makeSortList($value->son);
            }
        }
        
        // 循环完后回归缩进位置
        $this->blank = substr($this->blank, 6);
        return $this->outData;
    }

    // 内容栏目增加
    public function add()
    {
        if ($_POST) {
            if (! ! $multiplename = post('multiplename')) {
                $multiplename = str_replace('，', ',', $multiplename);
                $pcode = post('pcode', 'var');
                $type = post('type');
                $mcode = post('mcode');
                $listtpl = basename(post('listtpl'));
                $contenttpl = basename(post('contenttpl'));
                $status = post('status');
                
                if (! $pcode) { // 父编码默认为0
                    $pcode = 0;
                }
                
                if (! $mcode) {
                    alert_back('栏目模型必须选择！');
                }
                
                if (! $type) {
                    alert_back('栏目类型不能为空！');
                }
                
                $names = explode(',', $multiplename);
                $lastcode = $this->model->getLastCode();
                $scode = get_auto_code($lastcode);
                foreach ($names as $key => $value) {
                    $data[] = array(
                        'acode' => session('acode'),
                        'pcode' => $pcode,
                        'scode' => $scode,
                        'name' => $value,
                        'mcode' => $mcode,
                        'listtpl' => $listtpl,
                        'contenttpl' => $contenttpl,
                        'status' => $status,
                        'gid' => 0,
                        'gtype' => 4,
                        'subname' => '',
                        'filename' => '',
                        'outlink' => '',
                        'ico' => '',
                        'pic' => '',
                        'title' => '',
                        'keywords' => '',
                        'description' => '',
                        'sorting' => 255,
                        'create_user' => session('username'),
                        'update_user' => session('username')
                    );
                    $scode = get_auto_code($scode);
                }
            } else {
                // 获取数据
                $scode = get_auto_code($this->model->getLastCode()); // 自动编码;
                $pcode = post('pcode', 'var');
                $name = post('name');
                $type = post('type');
                $mcode = post('mcode');
                $listtpl = basename(post('listtpl'));
                $contenttpl = basename(post('contenttpl'));
                $status = post('status');
                $subname = post('subname');
                $filename = post('filename');
                $outlink = post('outlink');
                $ico = post('ico');
                $pic = post('pic');
                $title = post('title');
                $keywords = post('keywords');
                $description = post('description');
                
                $gid = post('gid', 'int') ?: 0;
                $gtype = post('gtype', 'int') ?: 4;
                $gnote = post('gnote');
                
                $def1 = post('def1');
                $def2 = post('def2');
                $def3 = post('def3');
                
                if (! $scode) {
                    alert_back('编码不能为空！');
                }
                
                if (! $pcode) { // 父编码默认为0
                    $pcode = 0;
                }
                
                if (! $name) {
                    alert_back('栏目名不能为空！');
                }
                
                if (! $mcode) {
                    alert_back('栏目模型必须选择！');
                }
                
                if (! $type) {
                    alert_back('栏目类型不能为空！');
                }
                
                if ($filename && ! preg_match('/^[a-zA-Z0-9\-]+$/', $filename)) {
                    alert_back('URL名称只允许字母、数字、横线组成!');
                }
                
                if ($filename && $this->model->checkUrlname($filename)) {
                    alert_back('URL名称与模型URL名称冲突，请换一个名称！');
                }
                
                // 缩放缩略图
                if ($ico) {
                    resize_img(ROOT_PATH . $ico, '', $this->config('ico.max_width'), $this->config('ico.max_height'));
                }
                
                // 检查编码
                if ($this->model->checkSort("scode='$scode'")) {
                    alert_back('该内容栏目编号已经存在，不能再使用！');
                }
                
                // 检查自定义URL名称
                if ($filename) {
                    while ($this->model->checkFilename($filename)) {
                        $filename = $filename . '_' . mt_rand(1, 20);
                    }
                }
                
                // 构建数据
                $data = array(
                    'acode' => session('acode'),
                    'pcode' => $pcode,
                    'scode' => $scode,
                    'name' => $name,
                    'mcode' => $mcode,
                    'listtpl' => $listtpl,
                    'contenttpl' => $contenttpl,
                    'status' => $status,
                    'gid' => $gid,
                    'gtype' => $gtype,
                    'gnote' => $gnote,
                    'subname' => $subname,
                    'def1' => $def1,
                    'def2' => $def2,
                    'def3' => $def3,
                    'filename' => $filename,
                    'outlink' => $outlink,
                    'ico' => $ico,
                    'pic' => $pic,
                    'title' => $title,
                    'keywords' => $keywords,
                    'description' => $description,
                    'sorting' => 255,
                    'create_user' => session('username'),
                    'update_user' => session('username')
                );
            }
            
            // 执行添加
            if ($this->model->addSort($data)) {
                if ($type == 1 && ! $outlink) { // 在填写了外链时不生成单页
                    if ($multiplename) {
                        foreach ($data as $key => $value) {
                            $this->addSingle($value['scode'], $value['name']);
                        }
                    } else {
                        $this->addSingle($scode, $name);
                    }
                }
                $this->log('新增数据内容栏目' . $scode . '成功！');
                success('新增成功！', url('/admin/ContentSort/index'));
            } else {
                $this->log('新增数据内容栏目' . $scode . '失败！');
                error('新增失败！', - 1);
            }
        }
    }

    // 生成内容栏目下拉选择
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

    // 内容栏目删除
    public function del()
    {
        // 执行批量删除
        if ($_POST) {
            if (! ! $list = post('list')) {
                if ($this->model->delSortList($list)) {
                    $this->log('批量删除栏目成功！');
                    success('批量删除成功！', - 1);
                } else {
                    $this->log('批量删除栏目失败！');
                    error('批量删除失败！', - 1);
                }
            } else {
                alert_back('请选择要删除的栏目！');
            }
        }
        
        if (! $scode = get('scode', 'var')) {
            error('传递的参数值错误！', - 1);
        }
        if ($this->model->delSort($scode)) {
            $this->log('删除数据内容栏目' . $scode . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除数据内容栏目' . $scode . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 内容栏目修改
    public function mod(){
        global $dsql;
        if (! ! $submit = post('submit')) {
            switch ($submit) {
                case 'sorting': // 修改列表排序
                    $listall = post('listall');
                    if ($listall) {
                        $sorting = post('sorting');
                        foreach ($listall as $key => $value) {
                            if ($sorting[$key] === '' || ! is_numeric($sorting[$key]))
                                $sorting[$key] = 255;
                            $this->model->modSortSorting($value, "sorting=" . $sorting[$key]);
                        }
                        $this->log('批量修改栏目排序成功！');
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
                            $dsql->amd("update ay_content_sort set status=1 where scode=$v limit 1");
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
                            $dsql->amd("update ay_content_sort set status=0 where scode=$v limit 1");
                        }
                        success('设置成功！',-1);
                    }else{
                        alert_back('请选择要设置的项目！');
                    }
                    break;
            }
        }
        
        if (! $scode = get('scode', 'var')) {
            error('传递的参数值错误！', - 1);
        }
        
        // WAP 推荐使用现有栏目编辑权限与表单令牌，单独保存，不改栏目或书籍属性。
        if (get('action') === 'waprecommend') {
            $this->wapRecommendations($scode);
            return;
        }

        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modSort($scode, "$field='$value',update_user='" . session('username') . "'")) {
                $this->log('修改数据内容栏目' . $scode . '状态' . $value . '成功！');
                location(- 1);
            } else {
                $this->log('修改数据内容栏目' . $scode . '状态' . $value . '失败！');
                alert_back('修改失败！');
            }
        }
        
        // 修改操作
        if ($_POST) {
            
            // 获取数据
            $pcode = post('pcode', 'var');
            $name = post('name');
            $mcode = post('mcode');
            $type = post('type');
            $listtpl = basename(post('listtpl'));
            $contenttpl = basename(post('contenttpl'));
            $status = post('status');
            $subname = post('subname');
            $filename = post('filename');
            $outlink = post('outlink');
            $ico = post('ico');
            $pic = post('pic');
            $title = post('title');
            $keywords = post('keywords');
            $description = post('description');
            $modsub = post('modsub', 'int');
            
            $gid = post('gid', 'int') ?: 0;
            $gtype = post('gtype', 'int') ?: 4;
            $gnote = post('gnote');
            
            $def1 = post('def1');
            $def2 = post('def2');
            $def3 = post('def3');
            
            if (! $pcode) { // 父编码默认为0
                $pcode = 0;
            }
            
            if (! $name) {
                alert_back('栏目名不能为空！');
            }
            
            if (! $mcode) {
                alert_back('栏目模型必须选择！');
            }
            
            if (! $type) {
                alert_back('栏目类型不能为空！');
            }
            
            if ($filename && ! preg_match('/^[a-zA-Z0-9\-]+$/', $filename)) {
                alert_back('URL名称只允许字母、数字、横线组成!');
            }
            
            if ($filename && $this->model->checkUrlname($filename)) {
                alert_back('URL名称与模型URL名称冲突，请换一个名称！');
            }
            
            // 缩放缩略图
            if ($ico) {
                resize_img(ROOT_PATH . $ico, '', $this->config('ico.max_width'), $this->config('ico.max_height'));
            }
            
            if ($filename) {
                while ($this->model->checkFilename($filename, "scode<>'$scode'")) {
                    $filename = $filename . '-' . mt_rand(1, 20);
                }
            }
            
            // 构建数据
            $data = array(
                'pcode' => $pcode,
                'name' => $name,
                'mcode' => $mcode,
                'listtpl' => $listtpl,
                'contenttpl' => $contenttpl,
                'status' => $status,
                'gid' => $gid,
                'gtype' => $gtype,
                'gnote' => $gnote,
                'subname' => $subname,
                'def1' => $def1,
                'def2' => $def2,
                'def3' => $def3,
                'filename' => $filename,
                'outlink' => $outlink,
                'ico' => $ico,
                'pic' => $pic,
                'title' => $title,
                'keywords' => $keywords,
                'description' => $description,
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->modSort($scode, $data, $modsub)) {
                // 如果修改为单页并且跳转，则删除单页内容，否则判断是否存在内容，不存在则添加
                if ($type == 1 && $outlink) {
                    $this->model->delContent($scode);
                } elseif ($type == 1 && ! $this->model->findContent($scode)) {
                    $this->addSingle($scode, $name);
                }
                
                $this->log('修改数据内容栏目' . $scode . '成功！');
                success('修改成功！', url('/admin/ContentSort/index'));
            } else {
                location(- 1);
            }
        } else { // 调取修改内容
            $this->assign('mod', true);
            
            $sort = $this->model->getSort($scode);
            if (! $sort) {
                error('编辑的内容已经不存在！', - 1);
            }
            $this->assign('sort', $sort);
            
            // 父编码下拉选择
            $sort_tree = $this->model->getSelect();
            $sort_select = $this->makeSortSelect($sort_tree, $sort->pcode);
            $this->assign('sort_select', $sort_select);
            
            // 模板文件
            $htmldir = $this->config('tpl_html_dir') ? '/' . $this->config('tpl_html_dir') : '';
            $this->assign('tpls', file_list(ROOT_PATH . current($this->config('tpl_dir')) . '/' . $this->model->getTheme() . $htmldir));
            
            // 内容模型
            $models = model('admin.content.Model');
            $this->assign('models', $models->getSelect());
            
            // 获取会员分组
            $this->assign('groups', model('admin.member.MemberGroup')->getSelect());
            
            $this->display('content/contentsort.html');
        }
    }

    private function wapRecommendations($scode)
    {
        global $dsql;
        require_once ROOT_PATH . '/wap/include/catalog.fun.php';
        if (!ctype_digit((string) $scode) || !($sort = $this->model->getSort($scode))) {
            error('栏目不存在或不在当前管理区域。', -1);
        }
        $scode = intval($scode);
        $sections = sh_wap_catalog_sections();
        $settings = sh_wap_catalog_settings($scode);
        $configModel = new \app\admin\model\system\ConfigModel();

        if ($_POST) {
            $values = array();
            try {
                // 全部校验成功后再写入，避免部分推荐位被错误清空。
                foreach ($sections as $section => $label) {
                    $input = post('wap_' . $section);
                    if ($input === null) throw new \InvalidArgumentException('请完整提交四个推荐位。');
                    $ids = sh_wap_catalog_ids($input);
                    if ($ids) {
                        $where = sh_wap_catalog_scope($scode);
                        if ($section === 'serial') $where .= " and subtitle<>'1'";
                        $books = $dsql->all('select id from ay_content where status=1' . $where . ' and id in (' . implode(',', $ids) . ')', 1);
                        if (count((array) $books) !== count($ids)) {
                            throw new \InvalidArgumentException($label . '中有不可选书籍，请选择本分类的已上架作品。' . ($section === 'serial' ? '连载推荐仅接受连载作品。' : ''));
                        }
                    }
                    $values[$section] = implode(',', $ids);
                }
                $channel = post('wap_channel');
                if (!in_array($channel, array('auto', 'boy', 'girl'), true)) {
                    throw new \InvalidArgumentException('请选择有效的阅读频道。');
                }
                $values['channel'] = $channel;
            } catch (\InvalidArgumentException $error) {
                alert_back($error->getMessage());
                return;
            }
            foreach ($values as $section => $value) {
                $key = sh_wap_catalog_key($scode, $section);
                if ($configModel->checkConfig("name='$key'")) {
                    $saved = $configModel->modValue($key, $value);
                    // 未发生变化时，部分驱动返回 0，也属于正常保存。
                    if ($saved === false) error('推荐位保存失败，请重试。', -1);
                } else {
                    $saved = $configModel->addConfig(array('name' => $key, 'value' => $value, 'type' => 2, 'sorting' => 255, 'description' => 'WAP分类推荐'));
                    if (!$saved) error('推荐位保存失败，请重试。', -1);
                }
            }
            $this->log('修改 WAP 分类 ' . $scode . ' 推荐位成功！');
            success('推荐位已保存！', url('/admin/ContentSort/mod/scode/' . $scode . '/action/waprecommend'));
            return;
        }

        $ids = array();
        foreach ($sections as $section => $label) {
            $ids[$section] = array();
            foreach (sh_wap_catalog_recommendations($scode, $section, 20, $settings) as $book) {
                $ids[$section][] = intval($book['id']);
            }
        }
        $books = (array) $dsql->all('select id,title,author,subtitle from ay_content where status=1' . sh_wap_catalog_scope($scode) . ' order by id desc', 1);
        foreach ($books as &$book) {
            $book['title'] = sh_wap_catalog_text($book['title']);
            $book['author'] = sh_wap_catalog_text($book['author']);
        }
        unset($book);
        $channelKey = sh_wap_catalog_key($scode, 'channel');
        $this->assign('wap_recommend', array(
            'scode' => $scode,
            'name' => sh_wap_catalog_text($sort->name),
            'sections' => $sections,
            'ids' => $ids,
            'books' => (array) $books,
            'channel' => isset($settings[$channelKey]) ? $settings[$channelKey] : 'auto'
        ));
        $this->display('content/waprecommend.html');
    }

    // 添加栏目时执行单页内容增加
    public function addSingle($scode, $title)
    {
        // 构建数据
        $data = array(
            'acode' => session('acode'),
            'scode' => $scode,
            'subscode' => '',
            'title' => $title,
            'titlecolor' => '#333333',
            'subtitle' => '',
            'filename' => '',
            'author' => session('realname'),
            'source' => '本站',
            'outlink' => '',
            'date' => date('Y-m-d H:i:s'),
            'ico' => '',
            'pics' => '',
            'content' => '',
            'tags' => '',
            'enclosure' => '',
            'keywords' => '',
            'description' => '',
            'sorting' => 255,
            'status' => 1,
            'istop' => 0,
            'isrecommend' => 0,
            'isheadline' => 0,
            'gid' => 0,
            'gtype' => 4,
            'gnote' => '',
            'visits' => 0,
            'likes' => 0,
            'oppose' => 0,
            'create_user' => session('username'),
            'update_user' => session('username')
        );
        
        // 执行添加
        if ($this->model->addSingle($data)) {
            return true;
        } else {
            return false;
        }
    }
}
