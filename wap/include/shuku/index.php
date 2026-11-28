<?php
require_once dirname(__FILE__) . '/../catalog.fun.php';
$scode = 0;
$page = isset($path[2]) ? intval($path[2]) : 1;
$sh_wap_catalog_options = sh_wap_catalog_options($page);
$template = 'shuku.html';
if ($sh_wap_catalog_options['view'] === 'all') {
    require dirname(__FILE__) . '/../../template/catalog-list.html';
} else {
    require dirname(__FILE__) . '/../../template/shuku.html';
}
