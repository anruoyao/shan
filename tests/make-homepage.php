<?php
// 独立测试首页生成，不启动 CMS、不连接数据库、不请求渠道接口。
define('ROOT_PATH', sys_get_temp_dir() . '/shbook-make-' . uniqid());
mkdir(ROOT_PATH);
require dirname(__DIR__) . '/make/include/make.fun.php';

$fetched = '<!doctype html><html><body>new homepage</body></html>';
$requested = '';
function xxx_url($url, $timeout = null)
{
    global $fetched, $requested;
    $requested = $url;
    return $fetched;
}

function check($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$PC_URL = 'https://configured.example/';
$WAP_URL = 'https://configured.example/wap/';
$HTTP_HOST = '127.0.0.1:8080';
$pcbaseall = 'http://127.0.0.1:8080/';
$_SERVER['SERVER_PORT'] = '8080';
$output = ROOT_PATH . '/pc.html';

try {
    check(gomk('index', 1, 1, '') === 'ok,index,2,1,1', '生成成功必须返回原有协议');
    check($requested === 'http://127.0.0.1:8080/index.php?upcache=1', '本地生成必须抓取本地模板');
    check(file_get_contents($output) === $fetched, '新页面必须真正写入');
    check($PC_URL === 'https://configured.example/' && $WAP_URL === 'https://configured.example/wap/', '生成后必须恢复配置 URL');

    $previous = file_get_contents($output);
    foreach (array(false, '', '<html>incomplete', '<html>face02.png</html>') as $fetched) {
        check(gomk('index', 1, 1, '') === 'error', '失败或错误页面必须报错');
        check(file_get_contents($output) === $previous, '抓取失败不能覆盖已有首页');
        check($PC_URL === 'https://configured.example/', '失败后也必须恢复 URL');
    }

    $fetched = '<html>production homepage</html>';
    $HTTP_HOST = 'reader.example';
    check(gomk('index', 1, 1, '') === 'ok,index,2,1,1', '配置域名生成应成功');
    check($requested === 'https://configured.example/index.php?upcache=1', '非本机请求必须保留配置域名');

    $HTTP_HOST = 'localhost:8443';
    $_SERVER['HTTPS'] = 'on';
    check(gomk('index', 1, 1, '') === 'ok,index,2,1,1', '本地 HTTPS 应成功');
    check($requested === 'https://localhost:8443/index.php?upcache=1', '必须保留 HTTPS 和端口');

    // 目标为目录时写入必然失败，避免依赖执行用户的权限。
    unlink($output);
    mkdir($output);
    check(gomk('index', 1, 1, '') === 'error', '写入失败不能返回成功');
    check($PC_URL === 'https://configured.example/', '写入失败后必须恢复 URL');
    echo "PASS: 首页生成、URL 恢复、错误页面和写入失败回归\n";
} finally {
    if (is_dir($output)) {
        rmdir($output);
    } elseif (is_file($output)) {
        unlink($output);
    }
    rmdir(ROOT_PATH);
}
