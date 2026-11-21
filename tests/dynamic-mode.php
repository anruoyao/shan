<?php
require dirname(__DIR__) . '/make/include/make.fun.php';

function check($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

check($staticArr === array('0' => '动态'), '只能提供动态模式');
check(setdjt('0') === 'ok', '动态模式应报告成功');
foreach (array('1', '2', '3', '4') as $mode) {
    check(setdjt($mode) === 'error', '旧静态模式不得重新启用');
}

$staticJobs = array(
    'index',
    'index_wap',
    'category',
    'category12',
    'category_wap',
    'category_wap12',
    'content1',
    'content1Ready_wap',
    'content2',
    'single1Title',
    'single2ID_wap'
);
foreach ($staticJobs as $job) {
    check(gomk($job, 1, 1, '') === 'error', $job . ' 静态页面任务必须停用');
}

$makeSource = file_get_contents(dirname(__DIR__) . '/make/include/make.fun.php');
check(strpos($makeSource, 'value="index"') === false, 'Make 页面不应显示首页静态生成选项');
check(strpos($makeSource, 'value="index_wap"') === false, 'Make 页面不应显示手机版静态生成选项');

echo "PASS: 动态模式固定、静态模式切换拒绝、页面生成任务停用" . PHP_EOL;
