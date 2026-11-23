<?php
/**
 * 读者端展示函数库
 * 说明：仅负责前台页面展示与查询组装，不涉及渠道分发、签约、稿费、章节计费等业务逻辑。
 * 所有输出到 HTML 的动态内容均做转义处理。
 */

/* ---------------- 基础工具 ---------------- */

function sh_e($str)
{
    return htmlspecialchars((string) $str, ENT_QUOTES, 'UTF-8');
}

function sh_num($n)
{
    $n = intval($n);
    if ($n >= 100000000) {
        return round($n / 100000000, 1) . '亿';
    }
    if ($n >= 10000) {
        return round($n / 10000, 1) . '万';
    }
    return (string) $n;
}

function sh_words($n)
{
    $n = intval($n);
    if ($n >= 10000) {
        return round($n / 10000, 1) . '万字';
    }
    return $n . '字';
}

function sh_time_ago($datetime)
{
    $ts = strtotime($datetime);
    if (!$ts) {
        return '';
    }
    $diff = time() - $ts;
    if ($diff < 60) {
        return '刚刚';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . '分钟前';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . '小时前';
    }
    if ($diff < 86400 * 30) {
        return floor($diff / 86400) . '天前';
    }
    return date('Y-m-d', $ts);
}

function sh_short($text, $len = 90)
{
    $text = trim(str_replace(array("\r", "\n", "\t", '&nbsp;'), ' ', strip_tags((string) $text)));
    $text = preg_replace('/\s+/u', ' ', $text);
    if (function_exists('mb_substr')) {
        if (mb_strlen($text, 'UTF-8') > $len) {
            return mb_substr($text, 0, $len, 'UTF-8') . '…';
        }
        return $text;
    }
    if (strlen($text) > $len) {
        return substr($text, 0, $len) . '...';
    }
    return $text;
}

/* ---------------- 分类与性别 ---------------- */

function sh_sex_scodes($sex = '')
{
    $male = array(11, 22, 33, 44, 55, 66);
    $female = array(67, 69, 70);
    if ($sex === 'male') {
        return $male;
    }
    if ($sex === 'female') {
        return $female;
    }
    return array_merge($male, $female);
}

function sh_sex_where($sex = '')
{
    if ($sex !== 'male' && $sex !== 'female') {
        return '';
    }
    return ' and scode in (' . implode(',', sh_sex_scodes($sex)) . ')';
}

function sh_cat_map()
{
    static $map = null;
    if ($map !== null) {
        return $map;
    }
    global $dsql;
    $map = array();
    if (!$dsql) {
        return $map;
    }
    $rows = $dsql->all("select scode,name,filename from ay_content_sort where status=1 order by sorting asc,id asc", 1);
    foreach ($rows as $row) {
        $map[intval($row['scode'])] = $row;
    }
    return $map;
}

function sh_cat($scode)
{
    $map = sh_cat_map();
    $scode = intval($scode);
    if (isset($map[$scode])) {
        return $map[$scode];
    }
    return array('scode' => $scode, 'name' => '其他', 'filename' => '');
}

function sh_cat_name($scode)
{
    $cat = sh_cat($scode);
    return $cat['name'];
}

function sh_cat_url($scode)
{
    return pbcode('category', intval($scode));
}

/* ---------------- 链接 ---------------- */

function sh_book_url($id)
{
    return pbcode('book', intval($id));
}

function sh_article_url($xid)
{
    return pbcode('article', intval($xid));
}

function sh_author_url($author)
{
    return pbcode('author', $author);
}

/* ---------------- 封面 ---------------- */

function sh_cover_img($ico, $title, $class = '')
{
    $src = function_exists('ico') ? ico($ico) : $ico;
    if (!$src) {
        $src = 'uploads/empty.jpg';
    }
    // 本地封面缺失时直接使用占位图，避免 404
    $path = parse_url($src, PHP_URL_PATH);
    if ($path && strpos($path, '/uploads/') === 0 && !file_exists(ROOT_PATH . $path)) {
        $src = 'uploads/empty.jpg';
    }
    $html = '<img src="' . sh_e($src) . '" alt="' . sh_e($title) . '" loading="lazy" decoding="async"';
    if ($class) {
        $html .= ' class="' . sh_e($class) . '"';
    }
    $html .= ' onerror="this.src=\'uploads/empty.jpg\'">';
    return $html;
}

/* ---------------- 书籍查询 ---------------- */

function sh_books($where = '', $order = 'update_time desc', $limit = 10, $offset = 0)
{
    global $dsql;
    $limit = intval($limit);
    $offset = intval($offset);
    if ($limit <= 0) {
        $limit = 10;
    }
    $fields = 'id,scode,title,author,subtitle,subscode,description,ico,visits,likes,num_dy,num_dy_first,score,update_time,create_time,isrecommend';
    $sql = "select $fields from ay_content where status=1 $where order by $order limit $offset,$limit";
    return $dsql->all($sql, 1);
}

function sh_book_count($where = '')
{
    global $dsql;
    $row = $dsql->one("select count(*) as c from ay_content where status=1 $where", 1);
    return $row ? intval($row['c']) : 0;
}

function sh_status_text($book)
{
    return (isset($book['subtitle']) && $book['subtitle'] === '1') ? '完结' : '连载';
}

/* ---------------- 书籍卡片 ---------------- */

function sh_book_card($b)
{
    $id = intval($b['id']);
    $title = sh_e($b['title']);
    $author = sh_e($b['author']);
    $cat = sh_cat($b['scode']);
    $url = sh_book_url($id);
    $desc = sh_short($b['description'], 64);
    $status = sh_status_text($b);
    $html = '<article class="sh-book">';
    $html .= '<a class="sh-book__cover" href="' . $url . '" title="' . $title . '">';
    $html .= sh_cover_img($b['ico'], $b['title']);
    $html .= '<span class="sh-book__state sh-book__state--' . ($status === '完结' ? 'over' : 'ing') . '">' . $status . '</span>';
    $html .= '</a>';
    $html .= '<div class="sh-book__body">';
    $html .= '<h3 class="sh-book__title"><a href="' . $url . '">' . $title . '</a></h3>';
    $html .= '<p class="sh-book__meta"><a href="' . sh_author_url($b['author']) . '">' . $author . '</a><span>·</span><a href="' . sh_cat_url($b['scode']) . '">' . sh_e($cat['name']) . '</a></p>';
    if ($desc) {
        $html .= '<p class="sh-book__desc">' . sh_e($desc) . '</p>';
    }
    $html .= '<p class="sh-book__foot"><span>' . sh_words($b['subscode']) . '</span><span>' . sh_num($b['visits']) . ' 人气</span></p>';
    $html .= '</div></article>';
    return $html;
}

/**
 * 紧凑书籍行（榜单、推荐位使用）
 *
 * @param array $b 书籍数据
 * @param int $rank 名次，0 表示不显示
 * @param string $metric 右侧展示的数值
 */
function sh_book_row($b, $rank = 0, $metric = '')
{
    $id = intval($b['id']);
    $url = sh_book_url($id);
    $title = sh_e($b['title']);
    $author = sh_e($b['author']);
    $cat = sh_cat($b['scode']);
    $html = '<li class="sh-row">';
    if ($rank) {
        $rankClass = $rank <= 3 ? ' is-top' : '';
        $html .= '<span class="sh-row__rank' . $rankClass . '">' . str_pad($rank, 2, '0', STR_PAD_LEFT) . '</span>';
    }
    $html .= '<a class="sh-row__cover" href="' . $url . '" title="' . $title . '">' . sh_cover_img($b['ico'], $b['title']) . '</a>';
    $html .= '<div class="sh-row__info">';
    $html .= '<a class="sh-row__title" href="' . $url . '" title="' . $title . '">' . $title . '</a>';
    $html .= '<p class="sh-row__meta">' . $author . '<span>·</span>' . sh_e($cat['name']) . '</p>';
    $html .= '</div>';
    if ($metric !== '') {
        $html .= '<span class="sh-row__metric">' . sh_e($metric) . '</span>';
    }
    $html .= '</li>';
    return $html;
}

/* ---------------- 首页模块 ---------------- */

function sh_home_blocks($sex = '')
{
    $where = sh_sex_where($sex);
    $blocks = array(
        array('key' => 'sale', 'title' => '畅销精品', 'sub' => '按累计订阅排序', 'order' => 'num_dy desc,visits desc,id desc', 'where' => '', 'metric' => 'num_dy', 'unit' => '订阅', 'more' => '/paihang/?type=sale'),
        array('key' => 'trend', 'title' => '人气风向', 'sub' => '按点赞热度排序', 'order' => 'likes desc', 'where' => '', 'metric' => 'likes', 'unit' => '点赞热度', 'more' => '/paihang/?type=vote'),
        array('key' => 'editor', 'title' => '编辑力荐', 'sub' => '编辑部手工挑选', 'order' => 'sorting asc,score desc,id desc', 'where' => ' and isrecommend=1', 'metric' => 'score', 'unit' => '评分', 'more' => '/shuku/'),
        array('key' => 'hot', 'title' => '热读精选', 'sub' => '按阅读热度排序', 'order' => 'visits desc,id desc', 'where' => '', 'metric' => 'visits', 'unit' => '阅读', 'more' => '/shuku/?sort=hot#allbooks'),
        array('key' => 'serial', 'title' => '火热连载', 'sub' => '最近更新的连载', 'order' => 'update_time desc', 'where' => " and subtitle<>'1'", 'metric' => 'visits', 'unit' => '阅读', 'more' => '/paihang/?type=update'),
        array('key' => 'new', 'title' => '潜力佳作', 'sub' => '最近上架作品', 'order' => 'create_time desc,id desc', 'where' => '', 'metric' => 'subscode', 'unit' => '字数', 'more' => '/paihang/?type=new'),
    );
    $html = '';
    $idx = 0;
    foreach ($blocks as $block) {
        $rows = sh_books($where . $block['where'], $block['order'], 4);
        $html .= '<section class="sh-block" style="--sh-block-i:' . $idx . '">';
        $html .= '<header class="sh-block__head"><div><h3>' . $block['title'] . '</h3><p>' . $block['sub'] . '</p></div>';
        $html .= '<a class="sh-more" href="' . $block['more'] . '">更多<i>›</i></a></header>';
        $html .= '<ol class="sh-block__list">';
        $rank = 0;
        foreach ($rows as $b) {
            $rank++;
            if ($block['metric'] === 'subscode') {
                $metric = sh_words($b['subscode']);
            } else {
                $metric = sh_num($b[$block['metric']]) . ' ' . $block['unit'];
            }
            $html .= sh_book_row($b, $rank, $metric);
        }
        if (!$rows) {
            $html .= '<li class="sh-block__empty">暂无作品</li>';
        }
        $html .= '</ol></section>';
        $idx++;
    }
    return $html;
}

function sh_home_categories($sex = '')
{
    $cats = sh_cat_map();
    $scodes = sh_sex_scodes($sex);
    $html = '<div class="sh-catgrid">';
    foreach ($cats as $scode => $cat) {
        if (!in_array($scode, $scodes)) {
            continue;
        }
        $count = sh_book_count(' and scode=' . $scode);
        $rows = sh_books(' and scode=' . $scode, 'visits desc', 3);
        $html .= '<a class="sh-catcard" href="' . sh_cat_url($scode) . '">';
        $html .= '<div class="sh-catcard__covers">';
        foreach ($rows as $b) {
            $html .= '<span>' . sh_cover_img($b['ico'], $b['title']) . '</span>';
        }
        $html .= '</div>';
        $html .= '<div class="sh-catcard__info"><h3>' . sh_e($cat['name']) . '</h3><p>' . $count . ' 部作品</p></div>';
        $html .= '<span class="sh-catcard__arrow">›</span>';
        $html .= '</a>';
    }
    $html .= '</div>';
    return $html;
}

function sh_home_cat_lists($sex = '')
{
    $cats = sh_cat_map();
    $scodes = sh_sex_scodes($sex);
    $html = '<div class="sh-catlist">';
    foreach ($cats as $scode => $cat) {
        if (!in_array($scode, $scodes)) {
            continue;
        }
        $rows = sh_books(' and scode=' . $scode, 'update_time desc', 6);
        $html .= '<section class="sh-catlist__col">';
        $html .= '<header><h3><a href="' . sh_cat_url($scode) . '">' . sh_e($cat['name']) . '</a></h3>';
        $html .= '<a class="sh-more" href="' . sh_cat_url($scode) . '">全部<i>›</i></a></header><ul>';
        foreach ($rows as $b) {
            $html .= '<li><a href="' . sh_book_url($b['id']) . '" title="' . sh_e($b['title']) . '">' . sh_e($b['title']) . '</a><em>' . sh_e($b['author']) . '</em></li>';
        }
        $html .= '</ul></section>';
    }
    $html .= '</div>';
    return $html;
}

function sh_home_new_books($num = 8, $sex = '')
{
    $rows = sh_books(sh_sex_where($sex), 'create_time desc,id desc', intval($num));
    if (!$rows) { return sh_empty('本频道暂无新书'); }
    $html = '';
    foreach ($rows as $b) {
        $html .= '<a class="sh-newbook" href="' . sh_book_url($b['id']) . '">';
        $html .= '<span class="sh-newbook__cover">' . sh_cover_img($b['ico'], $b['title']) . '</span>';
        $html .= '<span class="sh-newbook__body"><strong>' . sh_e($b['title']) . '</strong>';
        $html .= '<em>' . sh_e($b['author']) . '</em>';
        $html .= '<span>' . sh_status_text($b) . ' · ' . sh_words($b['subscode']) . '</span></span>';
        $html .= '</a>';
    }
    return $html;
}

function sh_home_updates($num = 14, $sex = '')
{
    global $dsql;
    $num = intval($num);
    $filter = $sex === 'male' || $sex === 'female' ? ' and c.scode in (' . implode(',', sh_sex_scodes($sex)) . ')' : '';
    $rows = $dsql->all("select a.xid,a.xtitle,a.id,c.title,c.author,c.scode,c.update_time
        from ay_article a inner join ay_content c on c.id=a.id
        where a.status=1 and c.status=1 $filter order by a.xid desc limit $num", 1);
    if (!$rows) { return sh_empty('本频道暂无更新'); }
    $html = '<ul class="sh-updates">';
    foreach ($rows as $r) {
        $cat = sh_cat($r['scode']);
        $html .= '<li>';
        $html .= '<div class="sh-updates__main"><a class="sh-updates__book" href="' . sh_book_url($r['id']) . '">' . sh_e($r['title']) . '</a>';
        $html .= '<a class="sh-updates__chapter" href="' . sh_article_url($r['xid']) . '" title="' . sh_e($r['xtitle']) . '">' . sh_e($r['xtitle']) . '</a></div>';
        $html .= '<div class="sh-updates__meta"><span>' . sh_e($r['author']) . '</span><a href="' . sh_cat_url($r['scode']) . '">' . sh_e($cat['name']) . '</a><time>' . sh_time_ago($r['update_time']) . '</time></div>';
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function sh_home_top($num = 8, $sex = '')
{
    $rows = sh_books(sh_sex_where($sex), 'visits desc', intval($num));
    if (!$rows) {
        return sh_empty('本频道暂无作品');
    }
    $html = '<ol class="sh-hotlist">';
    $rank = 0;
    foreach ($rows as $b) {
        $rank++;
        $html .= '<li><span class="sh-hotlist__rank' . ($rank <= 3 ? ' is-top' : '') . '">' . $rank . '</span>';
        $html .= '<a class="sh-hotlist__title" href="' . sh_book_url($b['id']) . '">' . sh_e($b['title']) . '</a>';
        $html .= '<span class="sh-hotlist__hot">' . sh_num($b['visits']) . '</span></li>';
    }
    $html .= '</ol>';
    return $html;
}

/* ---------------- 排行榜 ---------------- */

function sh_rank_types()
{
    return array(
        'sale' => array('label' => '畅销榜', 'desc' => '按累计订阅章节数排序', 'order' => 'num_dy desc,visits desc,id desc', 'field' => 'num_dy', 'unit' => '累计订阅'),
        'month' => array('label' => '月票榜', 'desc' => '站内暂无月票统计', 'order' => '', 'field' => '', 'unit' => '月票'),
        'vote' => array('label' => '推荐榜', 'desc' => '按站内点赞热度排序', 'order' => 'likes desc,id desc', 'field' => 'likes', 'unit' => '点赞热度'),
        'urge' => array('label' => '催更榜', 'desc' => '站内暂无催更统计', 'order' => '', 'field' => '', 'unit' => '催更'),
        'fav' => array('label' => '收藏榜', 'desc' => '按收藏作品的读者人数排序', 'order' => '', 'field' => 'fav_count', 'unit' => '收藏'),
        'new' => array('label' => '新书榜', 'desc' => '最近上架的新作品', 'order' => 'create_time desc,id desc', 'field' => 'subscode', 'unit' => ''),
        'update' => array('label' => '更新榜', 'desc' => '最近更新的作品', 'order' => 'update_time desc', 'field' => 'visits', 'unit' => '阅读'),
    );
}

function sh_rank_periods()
{
    return array(
        'day' => array('label' => '日榜', 'days' => 1),
        'week' => array('label' => '周榜', 'days' => 7),
        'month' => array('label' => '月榜', 'days' => 30),
        'all' => array('label' => '总榜', 'days' => 0),
    );
}

function sh_rank_type_info($type)
{
    $types = sh_rank_types();
    if (isset($types[$type])) {
        return $types[$type];
    }
    return $types['sale'];
}

function sh_rank_period_info($period)
{
    $periods = sh_rank_periods();
    if (isset($periods[$period])) {
        return $periods[$period];
    }
    return $periods['week'];
}

function sh_rank_metric_text($book, $type)
{
    if ($type === 'update') {
        return sh_time_ago($book['update_time']);
    }
    if ($type === 'new') {
        return sh_words($book['subscode']);
    }
    $info = sh_rank_type_info($type);
    $field = $info['field'];
    return sh_num(isset($book[$field]) ? $book[$field] : 0);
}

function sh_rank_metric_unit($type)
{
    if ($type === 'update') {
        return '最近更新';
    }
    if ($type === 'new') {
        return '字数';
    }
    $info = sh_rank_type_info($type);
    return $info['unit'];
}

function sh_rank_rows($type = 'sale', $period = 'week', $limit = 50)
{
    $types = sh_rank_types();
    if (!isset($types[$type])) {
        $type = 'sale';
    }
    $periods = sh_rank_periods();
    if (!isset($periods[$period])) {
        $period = 'week';
    }
    $days = intval($periods[$period]['days']);
    $order = $types[$type]['order'];
    $limit = intval($limit);

    if ($type === 'fav') {
        global $dsql;
        $date_filter = $days > 0 ? " where s.create_time >= date_sub(now(), interval $days day)" : '';
        return $dsql->all(
            "select b.id,b.scode,b.title,b.author,b.subtitle,b.subscode,b.description,b.ico,f.fav_count
             from ay_content b
             join (select s.id,count(distinct s.uid) as fav_count from ay_member_stow s$date_filter group by s.id) f on b.id=f.id
             where b.status=1 order by f.fav_count desc,b.id desc limit $limit", 1
        );
    }
    if ($order === '') {
        return array();
    }

    $where = '';
    if ($days > 0) {
        // 数据量较小时自动放宽统计窗口，避免榜单为空
        $field = ($type === 'new') ? 'create_time' : 'update_time';
        $where = " and $field >= date_sub(now(), interval $days day)";
        $count = sh_book_count($where);
        if ($count < 10) {
            $widen = array('day' => 7, 'week' => 30, 'month' => 90);
            $newDays = isset($widen[$period]) ? $widen[$period] : $days;
            $where = " and $field >= date_sub(now(), interval $newDays day)";
        }
    }
    return sh_books($where, $order, $limit);
}

/* ---------------- 分类页 ---------------- */

function sh_category_sort()
{
    return array(
        'update' => array('label' => '最近更新', 'order' => 'update_time desc'),
        'hot' => array('label' => '人气最高', 'order' => 'visits desc'),
        'new' => array('label' => '最新上架', 'order' => 'create_time desc,id desc'),
        'words' => array('label' => '字数最多', 'order' => 'subscode desc'),
        'vote' => array('label' => '推荐最多', 'order' => 'likes desc'),
    );
}

function sh_library_filter_options()
{
    return array(
        'state' => array('label' => '状态', 'values' => array(
            'serial' => array('连载', " and subtitle<>'1'"),
            'finished' => array('完本', " and subtitle='1'")
        )),
        'length' => array('label' => '字数', 'values' => array(
            'under15' => array('15万以下', ' and subscode<150000'),
            '15to50' => array('15–50万', ' and subscode>=150000 and subscode<500000'),
            '50to100' => array('50–100万', ' and subscode>=500000 and subscode<1000000'),
            'over100' => array('100万以上', ' and subscode>=1000000')
        )),
        'recent' => array('label' => '更新', 'values' => array(
            '3' => array('3天内', ' and update_time>=date_sub(now(), interval 3 day)'),
            '7' => array('7天内', ' and update_time>=date_sub(now(), interval 7 day)'),
            '30' => array('30天内', ' and update_time>=date_sub(now(), interval 30 day)')
        ))
    );
}

function sh_library_filter_query($input)
{
    $filters = array();
    $where = '';
    foreach (sh_library_filter_options() as $key => $option) {
        $value = $input[$key] ?? '';
        if (is_string($value) && isset($option['values'][$value])) {
            $filters[$key] = $value;
            $where .= $option['values'][$value][1];
        }
    }
    return array('values' => $filters, 'where' => $where);
}

function sh_library_url($base, $sort, $filters)
{
    $join = strpos($base, '?') === false ? '?' : '&';
    return $base . $join . http_build_query(array_merge(array('sort' => $sort), $filters));
}

function sh_library_nav($base, $sort, $filters)
{
    $html = '<div class="sh-library-filters">';
    foreach (sh_library_filter_options() as $key => $option) {
        $html .= '<nav class="sh-library-filter" aria-label="' . sh_e($option['label']) . '"><span>' . sh_e($option['label']) . '</span>';
        $active = $filters[$key] ?? '';
        $next = $filters;
        unset($next[$key]);
        $options = array('' => array('全部')) + $option['values'];
        foreach ($options as $value => $item) {
            $params = $next;
            if ($value !== '') {
                $params[$key] = $value;
            }
            $current = (string) $value === $active ? ' aria-current="true"' : '';
            $html .= '<a href="' . sh_e(sh_library_url($base, $sort, $params)) . '#allbooks"' . $current . '>' . sh_e($item[0]) . '</a>';
        }
        $html .= '</nav>';
    }
    return $html . '</div>';
}

function sh_book_page($where = '', $sort = 'update', $page = 1, $num = 24)
{
    $sorts = sh_category_sort();
    if (!isset($sorts[$sort])) {
        $sort = 'update';
    }
    $page = max(1, intval($page));
    $num = intval($num);
    $total = sh_book_count($where);
    $pages = $total > 0 ? ceil($total / $num) : 1;
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $num;
    $rows = sh_books($where, $sorts[$sort]['order'], $num, $offset);
    return array('rows' => $rows, 'total' => $total, 'pages' => $pages, 'page' => $page);
}

function sh_category_page($scode, $sort = 'update', $page = 1, $num = 24)
{
    $scode = intval($scode);
    $where = $scode > 0 ? ' and scode=' . $scode : '';
    return sh_book_page($where, $sort, $page, $num);
}

function sh_category_stats($scode)
{
    global $dsql;
    $scode = intval($scode);
    $where = $scode > 0 ? ' and scode=' . $scode : '';
    $row = $dsql->one("select count(*) as c, sum(subscode) as w from ay_content where status=1 $where", 1);
    $count = $row ? intval($row['c']) : 0;
    $words = $row && $row['w'] ? intval($row['w']) : 0;
    $last = $dsql->one("select update_time from ay_content where status=1 $where order by update_time desc limit 1", 1);
    return array(
        'count' => $count,
        'words' => $words,
        'last' => $last ? $last['update_time'] : '',
    );
}

function sh_overview_cards()
{
    $cats = sh_cat_map();
    $html = '';
    foreach ($cats as $scode => $cat) {
        $count = sh_book_count(' and scode=' . $scode);
        $covers = sh_books(' and scode=' . $scode, 'visits desc', 3);
        $html .= '<article class="sh-overview__card">';
        $html .= '<header><h3><a href="' . sh_cat_url($scode) . '">' . sh_e($cat['name']) . '</a></h3><span>' . $count . ' 部</span></header>';
        $html .= '<div class="sh-overview__covers">';
        foreach ($covers as $c) {
            $html .= '<span><a href="' . sh_book_url($c['id']) . '">' . sh_cover_img($c['ico'], $c['title']) . '</a></span>';
        }
        $html .= '</div><a class="sh-overview__more" href="' . sh_e(sh_cat_url($scode)) . '">进入分类 ›</a></article>';
    }
    return $html;
}

function sh_pagination($urlTpl, $page, $pages, $total = 0)
{
    $page = max(1, intval($page));
    $pages = max(1, intval($pages));
    if ($pages <= 1) {
        return '';
    }
    $html = '<nav class="sh-pager" aria-label="分页">';
    if ($page > 1) {
        $html .= '<a class="sh-pager__prev" href="' . sh_e(sprintf($urlTpl, $page - 1)) . '">上一页</a>';
    } else {
        $html .= '<span class="sh-pager__prev is-off">上一页</span>';
    }
    $start = max(1, $page - 2);
    $end = min($pages, $page + 2);
    if ($start > 1) {
        $html .= '<a href="' . sh_e(sprintf($urlTpl, 1)) . '">1</a>';
        if ($start > 2) {
            $html .= '<span class="sh-pager__gap">…</span>';
        }
    }
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $page) {
            $html .= '<span class="is-active">' . $i . '</span>';
        } else {
            $html .= '<a href="' . sh_e(sprintf($urlTpl, $i)) . '">' . $i . '</a>';
        }
    }
    if ($end < $pages) {
        if ($end < $pages - 1) {
            $html .= '<span class="sh-pager__gap">…</span>';
        }
        $html .= '<a href="' . sh_e(sprintf($urlTpl, $pages)) . '">' . $pages . '</a>';
    }
    if ($page < $pages) {
        $html .= '<a class="sh-pager__next" href="' . sh_e(sprintf($urlTpl, $page + 1)) . '">下一页</a>';
    } else {
        $html .= '<span class="sh-pager__next is-off">下一页</span>';
    }
    if ($total) {
        $html .= '<span class="sh-pager__total">共 ' . intval($total) . ' 条</span>';
    }
    $html .= '</nav>';
    return $html;
}

/* ---------------- 搜索 ---------------- */

function sh_search_clean($key)
{
    $key = trim(strip_tags((string) $key));
    $key = str_replace(array("'", '"', '\\', '%', '_', "\r", "\n"), '', $key);
    if (function_exists('mb_substr')) {
        $key = mb_substr($key, 0, 20, 'UTF-8');
    } else {
        $key = substr($key, 0, 20);
    }
    return $key;
}

function sh_search_page($key, $page = 1, $num = 20)
{
    global $dsql;
    $key = sh_search_clean($key);
    $page = max(1, intval($page));
    $num = intval($num);
    if ($key === '') {
        return array('rows' => array(), 'total' => 0, 'pages' => 1, 'page' => 1, 'key' => '');
    }
    $like = $key;
    $where = " and (title like '%$like%' or author like '%$like%')";
    $row = $dsql->one("select count(*) as c from ay_content where status=1 $where", 1);
    $total = $row ? intval($row['c']) : 0;
    $pages = $total > 0 ? ceil($total / $num) : 1;
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $num;
    $rows = sh_books($where, 'visits desc', $num, $offset);
    return array('rows' => $rows, 'total' => $total, 'pages' => $pages, 'page' => $page, 'key' => $key);
}

function sh_search_url($key, $page = 1)
{
    $key = urlencode($key);
    if (intval($page) <= 1) {
        return pbcode('so', $key);
    }
    return pbcode('so', $key, intval($page));
}

/* ---------------- 书籍详情 ---------------- */

function sh_chapter_count($id)
{
    global $dsql;
    $id = intval($id);
    $row = $dsql->one("select count(*) as c from ay_article where status=1 and id=$id", 1);
    return $row ? intval($row['c']) : 0;
}

function sh_chapter_list($id, $limit = 0)
{
    global $dsql;
    $id = intval($id);
    $limit = intval($limit);
    $sql = "select xid,xtitle from ay_article where status=1 and id=$id order by xid asc";
    if ($limit > 0) {
        $sql .= " limit 0," . $limit;
    }
    $rows = $dsql->all($sql, 1);
    $html = '';
    $i = 0;
    foreach ($rows as $r) {
        $i++;
        $html .= '<a class="sh-chapter" href="' . sh_article_url($r['xid']) . '" data-title="' . sh_e($r['xtitle']) . '">';
        $html .= '<span class="sh-chapter__no">' . str_pad($i, 3, '0', STR_PAD_LEFT) . '</span>';
        $html .= '<span class="sh-chapter__title">' . sh_e($r['xtitle']) . '</span>';
        $html .= '</a>';
    }
    if (!$html) {
        $html = '<p class="sh-empty">暂无章节，敬请期待。</p>';
    }
    return $html;
}

function sh_same_books($scode, $excludeId, $num = 6)
{
    $rows = sh_books(' and scode=' . intval($scode) . ' and id<>' . intval($excludeId), 'visits desc', intval($num));
    $html = '';
    foreach ($rows as $b) {
        $html .= '<a class="sh-minibook" href="' . sh_book_url($b['id']) . '">';
        $html .= '<span class="sh-minibook__cover">' . sh_cover_img($b['ico'], $b['title']) . '</span>';
        $html .= '<span class="sh-minibook__body"><strong>' . sh_e($b['title']) . '</strong><em>' . sh_e($b['author']) . '</em></span>';
        $html .= '</a>';
    }
    return $html;
}

function sh_author_say($xid)
{
    global $dsql;
    $xid = intval($xid);
    $row = $dsql->one("select author_say from ay_article where xid=$xid limit 1", 1);
    return $row && $row['author_say'] ? $row['author_say'] : '';
}

/* ---------------- 作者主页 ---------------- */

function sh_author_clean($author)
{
    return str_replace(array("'", '"', '\\'), '', trim((string) $author));
}

function sh_author_profile_columns()
{
    static $names = null;
    if ($names !== null) {
        return $names;
    }
    global $dsql;
    $names = array();
    $allowed = array('author_bio', 'author_title', 'author_honor');
    $rows = $dsql->all("show columns from ay_member where Field in ('author_bio','author_title','author_honor')", 1);
    foreach ((array) $rows as $column) {
        if (in_array($column['Field'], $allowed, true)) {
            $names[] = $column['Field'];
        }
    }
    return $names;
}

function sh_honored_authors()
{
    if (!in_array('author_honor', sh_author_profile_columns(), true)) {
        return array();
    }
    global $dsql;
    return $dsql->all("select b.author,b.works,m.headpic,m.author_honor from ay_member m
        join (select author,min(uid) as owner,count(*) as works from ay_content
            where status=1 and author<>'' group by author having count(distinct uid)=1) b
        on m.ucode=b.owner and m.nickname=b.author
        where trim(m.author_honor)<>'' order by b.works desc,b.author asc", 1);
}

function sh_author_honors($value)
{
    return array_unique(array_filter(array_map('trim', explode(',', (string) $value))));
}

function sh_author_stats($author)
{
    global $dsql;
    $author = sh_author_clean($author);
    if ($author === '') {
        return array('count' => 0, 'words' => 0, 'last' => '', 'headpic' => '', 'bio' => '', 'title' => '', 'honors' => array());
    }
    $row = $dsql->one("select count(*) as c, sum(subscode) as w, max(update_time) as u,
        min(uid) as owner, count(distinct uid) as owners from ay_content where status=1 and author='$author'", 1);
    $member = array();
    $owner = intval($row['owner'] ?? 0);
    if ($owner && intval($row['owners'] ?? 0) === 1) {
        // 只在作品归属唯一时展示公开资料，兼容尚无作者资料字段的旧库。
        $fields = implode(',', array_merge(array('headpic'), sh_author_profile_columns()));
        $member = $dsql->one("select $fields from ay_member where ucode=$owner and nickname='$author' limit 1", 1);
    }
    return array(
        'count' => intval($row['c'] ?? 0),
        'words' => intval($row['w'] ?? 0),
        'last' => $row['u'] ?? '',
        'headpic' => $member['headpic'] ?? '',
        'bio' => trim($member['author_bio'] ?? ''),
        'title' => trim($member['author_title'] ?? ''),
        'honors' => sh_author_honors($member['author_honor'] ?? '')
    );
}

function sh_author_books($author, $page = 1, $num = 24)
{
    global $dsql;
    $author = sh_author_clean($author);
    $page = max(1, intval($page));
    $num = intval($num);
    if ($author === '') {
        return array('rows' => array(), 'total' => 0, 'pages' => 1, 'page' => 1);
    }
    $row = $dsql->one("select count(*) as c from ay_content where status=1 and author='$author'", 1);
    $total = $row ? intval($row['c']) : 0;
    $pages = $total > 0 ? ceil($total / $num) : 1;
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $num;
    $rows = sh_books(" and author='$author'", 'update_time desc', $num, $offset);
    return array('rows' => $rows, 'total' => $total, 'pages' => $pages, 'page' => $page);
}

function sh_author_avatar($author, $headpic = '')
{
    if ($headpic) {
        return '<img src="' . sh_e(ico($headpic)) . '" alt="' . sh_e($author) . '" loading="lazy" decoding="async">';
    }
    $first = function_exists('mb_substr') ? mb_substr($author, 0, 1, 'UTF-8') : substr($author, 0, 1);
    return '<span class="sh-author__initial">' . sh_e($first) . '</span>';
}

function sh_author_directory($num = 60)
{
    global $dsql;
    $num = intval($num);
    $rows = $dsql->all("select author,count(*) as c,sum(subscode) as w from ay_content where status=1 and author<>'' group by author order by c desc,w desc limit $num", 1);
    $html = '<div class="sh-authordir">';
    foreach ($rows as $r) {
        $html .= '<a class="sh-authordir__item" href="' . pbcode('author', $r['author']) . '">';
        $html .= '<span class="sh-authordir__avatar">' . sh_author_avatar($r['author']) . '</span>';
        $html .= '<span class="sh-authordir__body"><strong>' . sh_e($r['author']) . '</strong><em>' . intval($r['c']) . ' 部作品 · ' . sh_words($r['w']) . '</em></span>';
        $html .= '<span class="sh-authordir__arrow">›</span></a>';
    }
    $html .= '</div>';
    return $html;
}

/* ---------------- 输出空状态 ---------------- */

function sh_empty($text = '暂无内容')
{
    return '<div class="sh-empty"><span>·</span><p>' . sh_e($text) . '</p><span>·</span></div>';
}
