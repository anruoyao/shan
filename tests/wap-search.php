<?php
// CLI 隔离验证：所有写入只落在当前连接的 MySQL 临时表中。
namespace core\basic {
    class Controller
    {
        protected function log($message) {}
    }

    // 保留实际 ConfigController / ConfigModel，隔离框架和数据库连接。
    class Model
    {
        public static $pdo;
        private $tableName;
        private $columns;
        private $condition;
        public function table($name) { $this->tableName = $name; $this->columns = '*'; $this->condition = '1=1'; return $this; }
        public function field($columns) { $this->columns = $columns; return $this; }
        public function where($condition) { $this->condition = $condition; return $this; }
        public function find() { return self::$pdo->query('select ' . $this->columns . ' from ' . $this->tableName . ' where ' . $this->condition . ' limit 1')->fetch(\PDO::FETCH_OBJ); }
        public function insert($data) { return self::$pdo->exec('insert into ' . $this->tableName . ' (' . implode(',', array_keys($data)) . ") values ('" . implode("','", array_values($data)) . "')"); }
        public function update($data) { return self::$pdo->exec('update ' . $this->tableName . ' set ' . $data . ' where ' . $this->condition); }
    }
}

namespace {
    if (PHP_SAPI !== 'cli') {
        http_response_code(404);
        exit;
    }
    define('ROOT_PATH', dirname(__DIR__));
    define('RUN_PATH', sys_get_temp_dir());
    require ROOT_PATH . '/wap/include/search.fun.php';
    require ROOT_PATH . '/apps/admin/model/system/ConfigModel.php';
    require ROOT_PATH . '/apps/admin/controller/system/ConfigController.php';

    class SearchAction extends RuntimeException {}
    function get($key) { return isset($_GET[$key]) ? $_GET[$key] : null; }
    function alert_back($message) { throw new SearchAction('invalid:' . $message); }
    function error($message, $url = null) { throw new SearchAction('error:' . $message); }
    function success($message, $url = null) { throw new SearchAction('saved:' . $message); }
    function url($path, $full = null) { return $path; }
    function get_tab($tab) { return '?tab=' . $tab; }
    function path_delete($path) {} // 不删除实际运行缓存。
    function decode_string($value) { return htmlspecialchars_decode(stripcslashes($value), ENT_QUOTES); }
    function escape_string($value) { return addslashes(htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8')); }
    function pbcode($type) { return '/wap/?pbcode/' . $type . '/'; }
    function check_search($condition, $message) { if (!$condition) throw new RuntimeException($message); }

    if (!extension_loaded('pdo_mysql')) {
        fwrite(STDERR, "隔离测试需要 PHP CLI 启用 pdo_mysql。\n");
        exit(1);
    }
    $db = (require ROOT_PATH . '/config/database.php')['database'];
    try {
        $pdo = new PDO('mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'] . ';charset=utf8mb4', $db['user'], $db['passwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $error) {
        fwrite(STDERR, "无法连接隔离测试数据库，请检查本机配置。\n");
        exit(1);
    }
    \core\basic\Model::$pdo = $pdo;
    $dsql = new class($pdo) {
        private $pdo;
        public function __construct($pdo) { $this->pdo = $pdo; }
        public function all($sql, $unused = null) { return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC); }
        public function one($sql, $unused = null) { return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC); }
    };
    $pdo->exec('create temporary table ay_config (id int auto_increment primary key,name varchar(30),value varchar(200),type char(1),sorting int,description varchar(30))');
    $pdo->exec('create temporary table ay_content (id int primary key,title varchar(200),author varchar(200),description text,ico varchar(100),subscode int,subtitle varchar(2),status int,visits int)');
    $insert = $pdo->prepare('insert into ay_content values (?,?,?,?,?,?,?,?,?)');
    foreach (array(array(1,'北齐','作者甲',1,2),array(2,'北齐之冒牌天子','作者甲',1,50),array(3,'未发布的北齐','北齐',0,999),array(4,'其他作品','北齐',1,80),array(5,'百分之100%_完成','作者乙',1,3),array(6,'无通配符作品','作者乙',1,4),array(7,"O'Reilly / A&B + 阅读",'作者乙',1,4)) as $book) {
        $insert->execute(array($book[0],htmlspecialchars($book[1],ENT_QUOTES,'UTF-8'),$book[2],'简介','',10000,'0',$book[3],$book[4]));
    }
    for ($i = 10; $i <= 35; $i++) $insert->execute(array($i,'分页作品' . $i,'作者丙','简介','',10000,'0',1,1));

    check_search(sh_wap_search_text(array('北齐')) === '', '数组查询参数必须被忽略');
    check_search(sh_wap_search_text(" \t北齐\n 千秋 ") === '北齐 千秋', '空白应规范化');
    check_search(sh_wap_search_results('')['total'] === 0, '空搜索不得列出全站作品');
    check_search(array_column(sh_wap_search_results('北齐')['rows'],'id') == array(1,4,2), '标题精确命中优先，其次作者精确命中；未上架作品不可出现');
    check_search(sh_wap_search_results('作者甲')['total'] === 2, '支持作者搜索');
    check_search(array_column(sh_wap_search_results('%_')['rows'],'id') == array(5), '百分号和下划线应作为普通文字搜索');
    check_search(array_column(sh_wap_search_results("O'Reilly / A&B + 阅读")['rows'],'id') == array(7), '标点、引号和实体应能搜索');
    check_search(sh_wap_search_results("' OR 1=1 --")['total'] === 0, '输入不得扩展 SQL 条件');
    check_search(sh_wap_search_results('分页作品', 99)['page'] === 2, '越界页码应收敛到最后一页');
    check_search(count(sh_wap_search_results('分页作品', 2)['rows']) === 6, '分页不能重复或漏掉剩余书籍');
    $url = sh_wap_search_url('A&B / + 北齐', 2);
    check_search(strpos($url, '&q=A%26B%20%2F%20%2B%20') !== false && strpos($url, '&pg=2') !== false, '搜索链接必须正确编码特殊字符和页码');
    check_search(!sh_wap_search_settings()['configured'] && count(sh_wap_search_hotwords(sh_wap_search_settings())) > 0, '未设置时应展示本站热门在架作品');

    $defaults = array('wap_search_placeholder'=>'搜索书名或作者','wap_search_rank_label'=>'推荐榜','wap_search_hot_enabled'=>'1','wap_search_rank_enabled'=>'1','wap_search_rank_type'=>'vote','wap_search_history_enabled'=>'1','wap_search_history_limit'=>'20','wap_search_hotwords'=>"北齐\n山海夜行录\n北齐\nO'Reilly / A&B");
    function save_search($overrides = array()) {
        global $defaults;
        $_GET = array('action'=>'wapsearch');
        $_POST = array_merge($defaults, $overrides);
        try { (new \app\admin\controller\system\ConfigController())->index(); } catch (SearchAction $action) { return $action->getMessage(); }
        throw new RuntimeException('设置保存没有返回结果');
    }
    check_search(strpos(save_search(array('close_site'=>'1')), 'saved:') === 0, '有效配置应保存成功');
    check_search(!$pdo->query("select id from ay_config where name='close_site'")->fetchColumn(), '搜索表单不能写入其他站点设置');
    check_search(sh_wap_search_hotwords(sh_wap_search_settings()) === array('北齐','山海夜行录',"O'Reilly / A&B"), '手动热词应保留顺序、去重并正确还原引号');
    check_search(strpos(save_search(), 'saved:') === 0, '重复保存无变更值应成功');
    $before = $pdo->query('select name,value from ay_config order by name')->fetchAll(PDO::FETCH_ASSOC);
    foreach (array(array('wap_search_hotwords'=>str_repeat('词',31)),array('wap_search_hotwords'=>implode("\n",range(1,21))),array('wap_search_hotwords'=>array('北齐')),array('wap_search_hotwords'=>'<script>alert(1)</script>'),array('wap_search_history_limit'=>'0'),array('wap_search_history_limit'=>'31'),array('wap_search_rank_type'=>'unknown'),array('wap_search_hot_enabled'=>array('1')),array('wap_search_placeholder'=>'')) as $invalid) {
        check_search(strpos(save_search($invalid), 'invalid:') === 0, '无效配置应在写入前被拒绝');
        check_search($pdo->query('select name,value from ay_config order by name')->fetchAll(PDO::FETCH_ASSOC) === $before, '错误配置不得部分写入');
    }
    check_search(strpos(save_search(array('wap_search_hot_enabled'=>'0')), 'saved:') === 0, '允许隐藏热门词');
    $hidden = sh_wap_search_settings();
    check_search(sh_wap_search_hotwords($hidden) === array() && count($hidden['hotwords']) === 3, '隐藏热词应保留编辑内容');
    check_search(strpos(save_search(array('wap_search_hotwords'=>'')), 'saved:') === 0, '允许清空热门词');
    check_search(sh_wap_search_hotwords(sh_wap_search_settings()) === array(), '保存空列表后不得自动恢复热门作品');
    check_search(strpos(save_search(array('wap_search_hotwords'=>'唯一热词')), 'saved:') === 0 && sh_wap_search_hotwords(sh_wap_search_settings()) === array('唯一热词'), '缩短列表后不应展示旧热词');

    define('M', 'admin');
    require ROOT_PATH . '/core/view/Parser.php';
    $templateRoot = ROOT_PATH . '/apps/admin/view/default';
    token_get_all(\core\view\Parser::compile($templateRoot, $templateRoot . '/system/config.html'), TOKEN_PARSE);
    echo "PASS: 标题与作者、精确命中、特殊字符、发布状态、分页、配置保存与去重顺序、空列表、显示开关、错误校验及后台模板编译\n";
}
