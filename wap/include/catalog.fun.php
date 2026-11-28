<?php
// WAP 分类展示与人工推荐；仅使用现有内容、栏目和配置表。
function sh_wap_catalog_e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function sh_wap_catalog_text($value)
{
    return trim(preg_replace('/\s+/u', ' ', strip_tags(decode_string((string) $value))));
}

function sh_wap_catalog_sections()
{
    return array('hot' => '热门推荐', 'serial' => '连载推荐', 'editor' => '编辑推荐', 'new' => '新书入库');
}

function sh_wap_catalog_ids($value)
{
    if (!is_string($value) || !preg_match('/^[\d\s,，、]*$/u', $value)) {
        throw new InvalidArgumentException('书籍 ID 只能填写数字，以逗号或换行分隔。');
    }
    $ids = array();
    foreach (preg_split('/[\s,，、]+/u', trim($value), -1, PREG_SPLIT_NO_EMPTY) as $id) {
        if (!preg_match('/^[1-9]\d{0,9}$/', $id) || (float) $id > 4294967295) {
            throw new InvalidArgumentException('请填写有效的书籍 ID。');
        }
        $ids[] = intval($id);
    }
    $ids = array_values(array_unique($ids));
    if (count($ids) > 20 || strlen(implode(',', $ids)) > 200) {
        throw new InvalidArgumentException('每个推荐位最多设置 20 本书。');
    }
    return $ids;
}

function sh_wap_catalog_key($scode, $section)
{
    return 'wap_cat_' . intval($scode) . '_' . $section;
}

function sh_wap_catalog_settings($scode)
{
    global $dsql;
    $keys = array();
    foreach (array_merge(array_keys(sh_wap_catalog_sections()), array('channel')) as $section) {
        $keys[] = "'" . sh_wap_catalog_key($scode, $section) . "'";
    }
    $rows = $dsql->all('select name,value from ay_config where name in (' . implode(',', $keys) . ')', 1);
    $settings = array();
    foreach ((array) $rows as $row) {
        $settings[$row['name']] = $row['value'];
    }
    return $settings;
}

function sh_wap_catalog_categories()
{
    global $dsql;
    static $categories;
    if ($categories !== null) return $categories;
    $categories = array();
    $rows = $dsql->all('select scode,pcode,name,ico from ay_content_sort where status=1 and mcode=18 order by sorting,id', 1);
    foreach ((array) $rows as $row) {
        $row['name'] = sh_wap_catalog_text($row['name']);
        $categories[intval($row['scode'])] = $row;
    }
    return $categories;
}

function sh_wap_catalog_scope_ids($scode)
{
    $scode = intval($scode);
    if (!$scode) return array();
    $ids = array($scode);
    foreach (sh_wap_catalog_categories() as $category) {
        $parent = intval($category['pcode']);
        $visited = array();
        $categories = sh_wap_catalog_categories();
        while ($parent && isset($categories[$parent]) && !isset($visited[$parent])) {
            if ($parent === $scode) {
                $ids[] = intval($category['scode']);
                break;
            }
            $visited[$parent] = true;
            $parent = intval($categories[$parent]['pcode']);
        }
    }
    return array_values(array_unique($ids));
}

function sh_wap_catalog_scope($scode, $alias = '')
{
    $ids = sh_wap_catalog_scope_ids($scode);
    return $ids ? ' and ' . $alias . 'scode in (' . implode(',', $ids) . ')' : '';
}

function sh_wap_catalog_url($scode = 0, $options = array())
{
    $url = $scode ? pbcode('category', intval($scode)) : pbcode('shuku');
    if ($options) $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($options, '', '&', PHP_QUERY_RFC3986);
    return $url;
}

function sh_wap_catalog_channel($category, $settings = null)
{
    if ($settings === null) $settings = sh_wap_catalog_settings($category['scode']);
    $key = sh_wap_catalog_key($category['scode'], 'channel');
    if (isset($settings[$key]) && in_array($settings[$key], array('boy', 'girl'), true)) return $settings[$key];
    return preg_match('/女频|女生|言情|耽美|纯爱/u', $category['name']) ? 'girl' : 'boy';
}

function sh_wap_catalog_directory()
{
    global $dsql;
    $counts = array();
    foreach ((array) $dsql->all('select scode,count(*) as total from ay_content where status=1 group by scode', 1) as $row) {
        $counts[intval($row['scode'])] = intval($row['total']);
    }
    $items = array();
    foreach (sh_wap_catalog_categories() as $category) {
        $category['count'] = 0;
        foreach (sh_wap_catalog_scope_ids($category['scode']) as $id) {
            $category['count'] += isset($counts[$id]) ? $counts[$id] : 0;
        }
        if (!$category['ico']) {
            $cover = $dsql->one("select ico from ay_content where status=1 and ico<>''" . sh_wap_catalog_scope($category['scode']) . ' order by isrecommend desc,visits desc,id desc limit 1', 1);
            $category['ico'] = $cover ? $cover['ico'] : '';
        }
        $category['channel'] = sh_wap_catalog_channel($category);
        $items[] = $category;
    }
    return $items;
}

function sh_wap_catalog_recommendations($scode, $section, $limit = 20, $settings = null)
{
    global $dsql;
    $sections = sh_wap_catalog_sections();
    if (!isset($sections[$section])) return array();
    if ($settings === null) $settings = sh_wap_catalog_settings($scode);
    $key = sh_wap_catalog_key($scode, $section);
    $where = sh_wap_catalog_scope($scode);
    if ($section === 'serial') $where .= " and subtitle<>'1'";
    if (array_key_exists($key, $settings)) {
        try {
            $ids = sh_wap_catalog_ids($settings[$key]);
        } catch (InvalidArgumentException $error) {
            return array();
        }
        if (!$ids) return array();
        $where .= ' and id in (' . implode(',', $ids) . ')';
        $order = 'field(id,' . implode(',', $ids) . ')';
    } else {
        // 兼容旧站后台的人工勾选；保存空列表后保持为空，不自动补书。
        $fields = array('hot' => 'isrecommend', 'serial' => 'hrlz', 'editor' => 'bjlj');
        if (!isset($fields[$section])) return array();
        $where .= ' and ' . $fields[$section] . '=1';
        $order = 'sorting asc,id desc';
    }
    $limit = max(1, min(20, intval($limit)));
    return (array) $dsql->all('select id,scode,title,author,description,ico,subscode,subtitle,tags from ay_content where status=1' . $where . ' order by ' . $order . ' limit ' . $limit, 1);
}

function sh_wap_catalog_book($book)
{
    $categories = sh_wap_catalog_categories();
    $book['url'] = pbcode('book', intval($book['id']));
    $book['title'] = sh_wap_catalog_text($book['title']);
    $book['author'] = sh_wap_catalog_text($book['author']);
    $book['description'] = sh_wap_catalog_text($book['description']);
    $book['cover'] = ico($book['ico'], 1);
    $book['category'] = isset($categories[intval($book['scode'])]) ? $categories[intval($book['scode'])]['name'] : '';
    $book['words'] = intval($book['subscode']) >= 10000 ? round(intval($book['subscode']) / 10000, 1) . '万字' : intval($book['subscode']) . '字';
    $book['state'] = (string) $book['subtitle'] === '1' ? '完本' : '连载';
    $tags = preg_split('/[\s,，、|]+/u', sh_wap_catalog_text($book['tags']), -1, PREG_SPLIT_NO_EMPTY);
    $book['tag'] = $tags ? reset($tags) : '';
    return $book;
}

function sh_wap_catalog_list($scode, $sort, $state, $page)
{
    global $dsql;
    $orders = array('update' => 'update_time desc,id desc', 'hot' => 'visits desc,id desc', 'new' => 'create_time desc,id desc', 'words' => 'subscode desc,id desc');
    if (!isset($orders[$sort])) $sort = 'update';
    $where = sh_wap_catalog_scope($scode);
    if ($state === 'serial') $where .= " and subtitle<>'1'";
    if ($state === 'finished') $where .= " and subtitle='1'";
    $count = $dsql->one('select count(*) as total from ay_content where status=1' . $where, 1);
    $total = $count ? intval($count['total']) : 0;
    $pages = max(1, intval(ceil($total / 20)));
    $page = max(1, min($pages, intval($page)));
    $rows = $dsql->all('select id,scode,title,author,description,ico,subscode,subtitle,tags from ay_content where status=1' . $where . ' order by ' . $orders[$sort] . ' limit ' . (($page - 1) * 20) . ',20', 1);
    return array('rows' => (array) $rows, 'total' => $total, 'page' => $page, 'pages' => $pages);
}

function sh_wap_catalog_options($legacyPage = 1)
{
    $options = array('view' => '', 'shelf' => '', 'sort' => 'update', 'state' => 'all', 'pg' => max(1, intval($legacyPage)));
    $allowed = array('view' => array('all'), 'shelf' => array_keys(sh_wap_catalog_sections()), 'sort' => array('update', 'hot', 'new', 'words'), 'state' => array('all', 'serial', 'finished'));
    foreach ($allowed as $key => $values) {
        if (isset($_GET[$key]) && is_string($_GET[$key]) && in_array($_GET[$key], $values, true)) $options[$key] = $_GET[$key];
    }
    if (isset($_GET['pg']) && is_scalar($_GET['pg'])) $options['pg'] = max(1, intval($_GET['pg']));
    if ($options['pg'] > 1) $options['view'] = 'all';
    return $options;
}
