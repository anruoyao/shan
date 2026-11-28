<?php
// 在同一连接的 MySQL 临时表中验证推荐保存，不修改站点数据。
namespace core\basic {
    class Controller
    {
        public $vars = array();
        public $template;
        protected function assign($key, $value) { $this->vars[$key] = $value; }
        protected function display($file) { $this->template = $file; }
        protected function log($message) {}
    }

    // 使用真实 ContentSortModel / ConfigModel，测试连接的临时表隔离写入。
    class Model
    {
        public static $pdo;
        private $tableName;
        private $columns;
        private $conditions;
        private $joins;
        public function table($name) { $this->tableName = $name; $this->columns = '*'; $this->conditions = array(); $this->joins = ''; return $this; }
        public function field($columns) { $this->columns = is_array($columns) ? implode(',', $columns) : $columns; return $this; }
        public function where($condition) { $this->conditions[] = $condition; return $this; }
        public function join($join) { $this->joins = ' ' . $join[2] . ' join ' . $join[0] . ' on ' . $join[1]; return $this; }
        public function find() { return self::$pdo->query('select ' . $this->columns . ' from ' . $this->tableName . $this->joins . ' where ' . implode(' and ', $this->conditions) . ' limit 1')->fetch(\PDO::FETCH_OBJ); }
        public function insert($data) {
            $statement = self::$pdo->prepare('insert into ' . $this->tableName . ' (' . implode(',', array_keys($data)) . ') values (' . implode(',', array_fill(0, count($data), '?')) . ')');
            return $statement->execute(array_values($data));
        }
        public function update($data) { return self::$pdo->exec('update ' . $this->tableName . ' set ' . $data . ' where ' . implode(' and ', $this->conditions)); }
    }
}

namespace {
    if (PHP_SAPI !== 'cli') {
        http_response_code(404);
        exit;
    }
    define('ROOT_PATH', dirname(__DIR__));
    require ROOT_PATH . '/apps/admin/model/content/ContentSortModel.php';
    require ROOT_PATH . '/apps/admin/model/system/ConfigModel.php';
    require ROOT_PATH . '/apps/admin/controller/content/ContentSortController.php';
    require ROOT_PATH . '/wap/include/catalog.fun.php';

    class CatalogAction extends RuntimeException {}
    function error($message, $url = null) { throw new CatalogAction('error:' . $message); }
    function alert_back($message) { throw new CatalogAction('invalid:' . $message); }
    function success($message, $url = null) { throw new CatalogAction('saved:' . $message); }
    function get($key, $type = null) { return isset($_GET[$key]) ? $_GET[$key] : null; }
    function post($key) { return isset($_POST[$key]) ? $_POST[$key] : null; }
    function session($key) { return $key === 'acode' ? 'cn' : ''; }
    function url($path) { return $path; }
    function decode_string($value) { return htmlspecialchars_decode(stripslashes($value), ENT_QUOTES); }
    function pbcode($type, $id = 0) { return '/wap/?' . $type . '/' . $id . '/'; }
    function check_catalog($condition, $message) { if (!$condition) throw new RuntimeException($message); }

    if (!extension_loaded('pdo_mysql')) {
        fwrite(STDERR, "此隔离测试需要 PHP CLI 启用 pdo_mysql。\n");
        exit(1);
    }
    $db = (require ROOT_PATH . '/config/database.php')['database'];
    try {
        $pdo = new PDO('mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'] . ';charset=utf8mb4', $db['user'], $db['passwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $error) {
        fwrite(STDERR, "隔离测试无法连接数据库，请检查本机数据库配置。\n");
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
    $pdo->exec('create temporary table ay_content_sort (scode int primary key,pcode int,name varchar(50),ico varchar(100),mcode int,status int,sorting int,id int,acode varchar(5))');
    $pdo->exec('create temporary table ay_model (mcode int,type int)');
    $pdo->exec("insert into ay_model values (18,2)");
    $pdo->exec("insert into ay_content_sort values (22,0,'玄幻·奇幻','',18,1,1,1,'cn'),(23,22,'东方玄幻','',18,1,2,2,'cn'),(44,0,'都市·游戏','',18,1,3,3,'cn'),(67,0,'女频·现言','',18,1,4,4,'cn')");
    $pdo->exec('create temporary table ay_content (id int primary key,scode int,title varchar(100),author varchar(100),description text,ico varchar(100),subscode int,subtitle varchar(2),tags varchar(100),status int,isrecommend int,hrlz int,bjlj int,sorting int,visits int,create_time datetime,update_time datetime)');
    $insert = $pdo->prepare('insert into ay_content values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    foreach (array(array(101,22,1,'0'),array(102,22,1,'1'),array(103,23,1,'0'),array(104,44,1,'0'),array(105,22,0,'0')) as $row) {
        $insert->execute(array($row[0],$row[1],'书籍' . $row[0],'作者','简介','',10000,$row[3],'玄幻',$row[2],1,1,1,255,$row[0],'2026-11-01 00:00:00','2026-11-28 00:00:00'));
    }

    check_catalog(sh_wap_catalog_ids("103，101\n103、102") === array(103,101,102), 'ID 应去重并保持人工顺序');
    foreach (array('1 or 1=1', '-1', '0', '4294967296', array(101), implode(',', range(1,21))) as $invalid) {
        try { sh_wap_catalog_ids($invalid); throw new RuntimeException('错误的 ID 未被拒绝'); } catch (InvalidArgumentException $expected) {}
    }
    check_catalog(sh_wap_catalog_scope_ids(22) === array(22,23), '父分类必须包含子分类');
    check_catalog(sh_wap_catalog_channel(array('scode'=>67,'name'=>'女频·现言'), array()) === 'girl', '女频应归入女生频道');
    check_catalog(array_column(sh_wap_catalog_recommendations(22,'hot'), 'id') == array(103,102,101), '首次访问应沿用后台人工推荐并排除其他分类和未上架书');
    check_catalog(array_column(sh_wap_catalog_recommendations(22,'serial'), 'id') == array(103,101), '连载推荐必须排除完本');
    check_catalog(sh_wap_catalog_recommendations(22,'new') === array(), '新书入库不可用自动排序替代人工推荐');

    $_GET = array('scode'=>'22','action'=>'waprecommend');
    $_POST = array();
    $controller = new \app\admin\controller\content\ContentSortController();
    $controller->mod();
    check_catalog($controller->template === 'content/waprecommend.html', '后台设置入口应使用独立模板');
    check_catalog(count($controller->vars['wap_recommend']['books']) === 3, '选书器应只显示本分类与子分类的已上架书籍');

    function save_catalog($values) {
        $_POST = array_merge(array('wap_hot'=>'103,101','wap_serial'=>'101','wap_editor'=>'102','wap_new'=>'103','wap_channel'=>'boy'), $values);
        try { (new \app\admin\controller\content\ContentSortController())->mod(); } catch (CatalogAction $result) { return $result->getMessage(); }
        throw new RuntimeException('保存流程没有结束');
    }
    check_catalog(strpos(save_catalog(array()), 'saved:') === 0, '有效的人工推荐应保存成功');
    check_catalog(array_column(sh_wap_catalog_recommendations(22,'hot'), 'id') == array(103,101), '前台必须遵循后台保存的人工顺序');
    check_catalog(array_column(sh_wap_catalog_recommendations(22,'new'), 'id') == array(103), '新书入库应显示人工设置的书籍');
    $before = $pdo->query('select name,value from ay_config order by name')->fetchAll(PDO::FETCH_ASSOC);
    foreach (array(array('wap_hot'=>'104'),array('wap_hot'=>'105'),array('wap_serial'=>'102'),array('wap_hot'=>'101;drop table ay_content'),array('wap_channel'=>'invalid')) as $invalid) {
        check_catalog(strpos(save_catalog($invalid), 'invalid:') === 0, '无效的推荐提交必须被拒绝');
        check_catalog($pdo->query('select name,value from ay_config order by name')->fetchAll(PDO::FETCH_ASSOC) === $before, '校验失败不得部分写入推荐位');
    }
    check_catalog(strpos(save_catalog(array('wap_hot'=>'')), 'saved:') === 0, '允许主动清空推荐位');
    check_catalog(sh_wap_catalog_recommendations(22,'hot') === array(), '主动清空后不能恢复旧推荐或自动补书');
    check_catalog(strpos(save_catalog(array('wap_hot'=>'')), 'saved:') === 0, '重复保存应正常处理无变更的字段');
    $list = sh_wap_catalog_list(22,'update','finished',1);
    check_catalog(array_column($list['rows'],'id') == array(102), '全部作品的完本筛选必须限定当前分类');
    check_catalog(strpos(sh_wap_catalog_url(22,array('view'=>'all','pg'=>2)), '&view=all&pg=2') !== false, '动态路径应正确附加查询参数');

    // 后台标签解析器会处理 PHP 数组下标，必须验证编译后的模板仍可执行。
    define('M', 'admin');
    require ROOT_PATH . '/core/view/Parser.php';
    $templateRoot = ROOT_PATH . '/apps/admin/view/default';
    token_get_all(\core\view\Parser::compile($templateRoot, $templateRoot . '/content/waprecommend.html'), TOKEN_PARSE);

    echo "PASS: 人工推荐顺序、父子分类、空推荐位、后台读取与保存、错误提交原子校验、连载状态、筛选、URL 参数及后台模板编译\n";
}
