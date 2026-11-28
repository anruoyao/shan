<?php
require_once dirname(__FILE__) . '/../search.fun.php';
global $template, $key, $page;
$legacyKey = isset($path[2]) ? explode('&', $path[2], 2)[0] : '';
$input = isset($_GET['q']) ? $_GET['q'] : (isset($_POST['wd']) ? $_POST['wd'] : urldecode($legacyKey));
$key = sh_wap_search_text($input);
$legacyPage = isset($path[3]) ? $path[3] : 1;
$page = isset($_GET['pg']) && is_scalar($_GET['pg']) ? intval($_GET['pg']) : intval($legacyPage);
$sh_wap_search_settings = sh_wap_search_settings();
$sh_wap_search_results = sh_wap_search_results($key, $page);
$page = $sh_wap_search_results['page'];
$sh_wap_search_hotwords = sh_wap_search_hotwords($sh_wap_search_settings);
$template = 'so.html';
require_once dirname(__FILE__) . '/../../template/' . $template;
