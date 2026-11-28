<?php
// WAP 搜索的展示、配置和只读查询；不依赖新增表或字段。
function sh_wap_search_e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sh_wap_search_text($value, $limit = 60)
{
    if (!is_string($value) || !preg_match('//u', $value)) return '';
    $value = trim(preg_replace('/[\s\x00-\x1f\x7f]+/u', ' ', $value));
    preg_match('/^.{0,' . intval($limit) . '}/us', $value, $match);
    return isset($match[0]) ? $match[0] : '';
}

function sh_wap_search_rank_types()
{
    return array('sale' => '畅销', 'month' => '月票', 'vote' => '推荐', 'urge' => '催更', 'fav' => '收藏', 'new' => '新书', 'update' => '更新');
}

function sh_wap_search_settings()
{
    global $dsql;
    $settings = array('placeholder' => '请输入您想搜索的任何内容', 'hot_enabled' => '1', 'rank_enabled' => '1', 'rank_label' => '推荐榜', 'rank_type' => 'vote', 'history_enabled' => '1', 'history_limit' => '20', 'hotwords' => array(), 'configured' => false);
    $values = array();
    foreach ((array) $dsql->all("select name,value from ay_config where name like 'wap!_search!_%' escape '!'", 1) as $row) {
        $values[$row['name']] = decode_string($row['value']);
    }
    foreach (array('placeholder' => 40, 'rank_label' => 12) as $key => $limit) {
        if (isset($values['wap_search_' . $key]) && $values['wap_search_' . $key] !== '') {
            $settings[$key] = sh_wap_search_text($values['wap_search_' . $key], $limit);
        }
    }
    foreach (array('hot_enabled', 'rank_enabled', 'history_enabled') as $key) {
        if (isset($values['wap_search_' . $key]) && in_array($values['wap_search_' . $key], array('0', '1'), true)) {
            $settings[$key] = $values['wap_search_' . $key];
        }
    }
    $types = sh_wap_search_rank_types();
    if (isset($values['wap_search_rank_type'], $types[$values['wap_search_rank_type']])) $settings['rank_type'] = $values['wap_search_rank_type'];
    if (isset($values['wap_search_history_limit'])) $settings['history_limit'] = (string) max(1, min(30, intval($values['wap_search_history_limit'])));
    $settings['configured'] = array_key_exists('wap_search_hot_count', $values);
    if ($settings['configured']) {
        $count = max(0, min(20, intval($values['wap_search_hot_count'])));
        for ($i = 1; $i <= $count; $i++) {
            $key = 'wap_search_hot_' . $i;
            $word = isset($values[$key]) ? sh_wap_search_text($values[$key], 30) : '';
            if ($word !== '' && !in_array($word, $settings['hotwords'], true)) $settings['hotwords'][] = $word;
        }
    }
    return $settings;
}

function sh_wap_search_hotwords($settings)
{
    global $dsql;
    if ($settings['hot_enabled'] !== '1') return array();
    if ($settings['configured']) return $settings['hotwords'];
    // 首次设置前用本站真实在架作品，保存空列表后不再补充。
    $words = array();
    foreach ((array) $dsql->all('select title from ay_content where status=1 order by visits desc,id desc limit 8', 1) as $book) {
        $word = sh_wap_search_text(strip_tags(decode_string($book['title'])), 30);
        if ($word !== '' && !in_array($word, $words, true)) $words[] = $word;
    }
    return $words;
}

function sh_wap_search_config_values($input)
{
    $values = array();
    foreach (array('placeholder' => array(40, '搜索框提示'), 'rank_label' => array(12, '榜单入口文字')) as $key => $rule) {
        $name = 'wap_search_' . $key;
        if (!isset($input[$name]) || !is_string($input[$name])) throw new InvalidArgumentException('请完整填写搜索设置。');
        $text = sh_wap_search_text($input[$name], $rule[0] + 1);
        if ($text === '' || preg_match_all('/./us', $text) > $rule[0] || strip_tags($text) !== $text || preg_match_all('/./us', htmlspecialchars($text, ENT_QUOTES, 'UTF-8')) > 200) throw new InvalidArgumentException($rule[1] . '需填写 1–' . $rule[0] . ' 个纯文本字符，特殊符号过多时请缩短。');
        $values[$name] = $text;
    }
    foreach (array('hot_enabled', 'rank_enabled', 'history_enabled') as $key) {
        $name = 'wap_search_' . $key;
        if (!isset($input[$name]) || !in_array($input[$name], array('0', '1'), true)) throw new InvalidArgumentException('请选择有效的显示设置。');
        $values[$name] = $input[$name];
    }
    $types = sh_wap_search_rank_types();
    if (!isset($input['wap_search_rank_type']) || !is_string($input['wap_search_rank_type']) || !isset($types[$input['wap_search_rank_type']])) throw new InvalidArgumentException('请选择有效的榜单类型。');
    $values['wap_search_rank_type'] = $input['wap_search_rank_type'];
    if (!isset($input['wap_search_history_limit']) || !is_string($input['wap_search_history_limit']) || !preg_match('/^(?:[1-9]|[12][0-9]|30)$/', $input['wap_search_history_limit'])) throw new InvalidArgumentException('历史记录条数需为 1–30。');
    $values['wap_search_history_limit'] = $input['wap_search_history_limit'];
    if (!isset($input['wap_search_hotwords']) || !is_string($input['wap_search_hotwords']) || !preg_match('//u', $input['wap_search_hotwords']) || strlen($input['wap_search_hotwords']) > 10000) throw new InvalidArgumentException('请填写有效的热门搜索词，每行一个。');
    $words = array();
    foreach (preg_split('/\r\n|\r|\n/u', $input['wap_search_hotwords']) as $line) {
        $word = sh_wap_search_text($line, 31);
        if ($word === '') continue;
        if (preg_match_all('/./us', $word) > 30 || strip_tags($word) !== $word) throw new InvalidArgumentException('每个热门搜索词最多 30 个纯文本字符。');
        if (!in_array($word, $words, true)) $words[] = $word;
    }
    if (count($words) > 20) throw new InvalidArgumentException('热门搜索词最多设置 20 个。');
    // 每词独立存储，兼容旧站 ay_config.value 的 200 字符限制。
    for ($i = 1; $i <= 20; $i++) $values['wap_search_hot_' . $i] = isset($words[$i - 1]) ? $words[$i - 1] : '';
    $values['wap_search_hot_count'] = (string) count($words);
    return $values;
}

function sh_wap_search_url($query = '', $page = 1)
{
    $url = pbcode('so');
    if ($query !== '') $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query(array('q' => $query, 'pg' => max(1, intval($page))), '', '&', PHP_QUERY_RFC3986);
    return $url;
}

function sh_wap_search_results($query, $page = 1)
{
    global $dsql;
    $query = sh_wap_search_text($query);
    if ($query === '') return array('rows' => array(), 'total' => 0, 'page' => 1, 'pages' => 1);
    // 兼容后台入库时的 HTML 实体；LIKE 中的 % 和 _ 按普通字符查找。
    $term = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
    $exact = addslashes($term);
    $like = addslashes(str_replace(array('!', '%', '_'), array('!!', '!%', '!_'), $term));
    $where = "status=1 and (title like '%$like%' escape '!' or author like '%$like%' escape '!')";
    $count = $dsql->one('select count(*) as total from ay_content where ' . $where, 1);
    $total = $count ? intval($count['total']) : 0;
    $pages = max(1, intval(ceil($total / 20)));
    $page = max(1, min($pages, intval($page)));
    $rows = $dsql->all('select id,title,author,description,ico,subscode,subtitle from ay_content where ' . $where . " order by (title='$exact') desc,(author='$exact') desc,visits desc,id desc limit " . (($page - 1) * 20) . ',20', 1);
    return array('rows' => (array) $rows, 'total' => $total, 'page' => $page, 'pages' => $pages);
}
