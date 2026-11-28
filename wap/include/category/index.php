<?php
require_once dirname(__FILE__) . '/../catalog.fun.php';
$sh_wap_category_route = isset($path[0]) ? $path[0] : '';
if (strpos($sh_wap_category_route, '&') !== false) {
    $sh_wap_category_route = explode('&', $sh_wap_category_route, 2)[1];
}
$sh_wap_category_route = explode('_', $sh_wap_category_route, 2);
$scode = $sh_wap_category_route[0];
$page = isset($sh_wap_category_route[1]) ? intval($sh_wap_category_route[1]) : 1;
if (!ctype_digit((string) $scode)) {
    $scode = preg_match('/^[a-zA-Z0-9-]+$/', $scode) ? $scode : '';
    $row = $scode ? $dsql->one("select scode from ay_content_sort where filename='$scode' limit 1", 1) : null;
    $scode = $row ? intval($row['scode']) : 0;
}
$scode = intval($scode);
$row = $scode ? $dsql->one('select scode,name,title,keywords,description,filename from ay_content_sort where status=1 and scode=' . $scode . ' limit 1', 1) : null;
if (!$row) {
    require dirname(__FILE__) . '/../../404.html';
    exit;
}
foreach (array('scode', 'name', 'title', 'keywords', 'description', 'filename') as $key) {
    $$key = $row[$key];
}
$sh_wap_catalog_options = sh_wap_catalog_options($page);
$template = 'category.html';
if ($sh_wap_catalog_options['view'] === 'all' || $sh_wap_catalog_options['shelf']) {
    require dirname(__FILE__) . '/../../template/catalog-list.html';
} else {
    require dirname(__FILE__) . '/../../template/category.html';
}
