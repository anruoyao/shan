function GetXmlHttpObject() {
    var xmlHttp = null;
    if (window.ActiveXObject) { try { xmlHttp = new ActiveXObject('Microsoft.XMLHttp'); } catch (e) { xmlHttp = new ActiveXObject('Msxml2.XMLHTTP'); } } else if (window.XMLHttpRequest) { xmlHttp = new XMLHttpRequest(); }
    return xmlHttp;
}

function x_get(URL00, OBJ, XID, ELSE, OTHER) {
    URL00 = URL00 + '&ttt=' + (new Date).getTime();
    var xmlHttp = null;
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) { alert('Browser does not support HTTP Request!'); return false; }
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var cDATA = xmlHttp.responseText;
            if (cDATA) {
                $id(OBJ) ? $id(OBJ).innerHTML = cDATA : '';
                if (XID == 'base') {
                    if (cDATA == 'ok') {
                        setcookie('sitemap', '');
                        setcookie('make_html', '');
                        var ss = '<ss class="red">ok，请在下方更新页面或配置伪静态规则</ss>';
                    } else {
                        var ss = '<ss class="red">操作失败，稍后重试</ss>';
                    }
                    $id(XID + ELSE).innerHTML = ss;
                    setTimeout(function() {
                        $id(XID + ELSE).innerHTML = djtArr[ELSE];
                        $id(XID).innerHTML = djtArr[ELSE];
                        doBox();
                    }, 3000);
                }
                if (XID == 'conBox') {
                    doBox();
                }
                if (XID == 'go') {
                    var seobox = $id('seobox');
                    if (seobox) {
                        var domain = $id('domain' + page).innerHTML;
                        if (page == '1') {
                            domain = 'all';
                            var x = '';
                        } else {
                            var x = '{697}';
                        }
                        var cfg = '';
                        var xxx = x + 'domain:' + domain + '{697}';
                        for (i = 1; i < 1000; i++) {
                            var x = $id('input' + page + '_' + i);
                            if (!x) {
                                break;
                            }
                            var c = x.className;
                            var v = x.value;
                            if (page == '1') {
                                if (c.indexOf('web') > -1) {
                                    cfg += v + '{697}';
                                    v = '';
                                }
                                if (c.indexOf('index') > -1) {
                                    cfg += v + '{697}';
                                    v = '';
                                }
                            }
                            var p = c + ':' + v;
                            xxx += p + '{697}';
                        }
                        cfg ? cfgtxt(cfg) : '';
                        seotxt(domain, xxx);
                    }
                    var x = cDATA.split('ok');
                    x = x[0];
                    cDATA = cDATA.replace(x, '');
                    var arr = cDATA.split(',');
                    var ok = arr[0];
                    x2x = arr[1];
                    page = parseInt(arr[2]);
                    total = parseInt(arr[3]);
                    var num = parseInt(arr[4]);
                    if (ok == 'ok') {
                        goBox(page, total, num);
                        var pageAll = Math.ceil(total / num);
                        if (page <= pageAll) {
                            go();
                        } else {
                            if (seobox) {
                                seo('yes');
                                tt = '';
                            } else {
                                var c01 = $id('jieqi2pbcode').checked;
                                var c02 = $id('cj2local').checked;
                                if (!c01 && !c02) {
                                    tt = '';
                                }
                            }
                            if (seobox) {
                                var goinner = '保存成功！';
                                var goinner2 = '保存设置';
                            } else {
                                var goinner = '更新成功！';
                                var goinner2 = '开始更新';
                            }
                            $id('go').innerHTML = goinner;
                            setTimeout(function() {
                                page = 1;
                                total = 1;
                                $id('go').innerHTML = goinner2;
                                if (auto && x2x == 'index') {
                                    $id('index').checked = '';
                                    $id('category').checked = 'checked';
                                    go(1);
                                } else if (auto && x2x == 'category') {
                                    $id('category').checked = '';
                                    $id('index_wap').checked = 'checked';
                                    go(1);
                                } else if (auto && x2x == 'index_wap') {
                                    $id('index_wap').checked = '';
                                    $id('category_wap').checked = 'checked';
                                    go(1);
                                } else if (auto && x2x == 'category_wap') {
                                    $id('conBox').innerHTML = '<p class="p1">OK!&nbsp;<a href="make/?ok">返回</a></p>';
                                }

                            }, 1000);
                            if (x2x == 'jieqi2pbcode' || x2x == 'cj2local') {
                                var xw = $id('xw');
                                var inner = xw.innerHTML;
                                xw.innerHTML = '<span style="float:left;margin-left:30px">(<font id="xtime">600</font> 秒后自动更新)</span>' + inner;
                                xtime();
                            }
                        }
                    } else {
                        tt = '';
                        if (seobox) {
                            var goinner = '保存失败';
                            var goinner2 = '保存设置';
                        } else {
                            var goinner = '更新失败';
                            var goinner2 = '开始更新';
                        }
                        $id('go').innerHTML = goinner + '，稍后重试';
                        setTimeout(function() {
                            page = 1;
                            total = 1;
                            $id('go').innerHTML = goinner2;
                        }, 1000);
                    }
                }
                if (XID == 'SET_MAKE_NUM') {
                    if (cDATA == 'error') {
                        $id('MAKE_NUM').innerHTML = '<x>操作失败，稍后重试</x>';
                        setTimeout(function() {
                            GET_MAKE_NUM();
                        }, 1000);
                    } else {
                        $id('MAKE_NUM').innerHTML = cDATA;
                    }
                }
                if (XID == 'doBox') {
                    gox();
                    if (auto) {
                        go(1);
                    }
                }
                if (XID == 'auto2dede') {
                    if (cDATA == 'ok') {
                        var ss = '<ss class="red">ok</ss>';
                        if (ELSE == '0') {
                            var c = '开启自动缓存';
                        } else {
                            var c = '关闭自动缓存';
                        }
                    } else {
                        var ss = '<ss class="red">操作失败，稍后重试</ss>';
                        if (ELSE == '0') {
                            var c = '关闭自动缓存';
                        } else {
                            var c = '开启自动缓存';
                        }
                    }
                    var a = $id(XID);
                    a.innerHTML = ss;
                    setTimeout(function() {
                        a.innerHTML = c;
                    }, 1000);
                }
            } else {
                if (XID == 'base') {
                    $id(XID + ELSE).innerHTML = '<ss class="red">操作失败，稍后重试</ss>';
                    setTimeout(function() {
                        $id(XID + ELSE).innerHTML = djtArr[ELSE];
                    }, 1000);
                }
                if (XID == 'Ready') {
                    $id('Ready' + ELSE).innerHTML = '失败，稍后重试...';
                }
                if (XID == 'go') {
                    tt = '';
                    if ($id('seobox')) {
                        var goinner = '保存失败';
                        var goinner2 = '保存设置';
                    } else {
                        var goinner = '更新失败';
                        var goinner2 = '开始更新';
                    }
                    $id('go').innerHTML = goinner + '，稍后重试';
                    setTimeout(function() {
                        page = 1;
                        total = 1;
                        $id('go').innerHTML = goinner2;
                    }, 1000);
                }
                if (XID == 'SET_MAKE_NUM') {
                    $id('MAKE_NUM').innerHTML = '<x>操作失败，稍后重试</x>';
                    setTimeout(function() {
                        GET_MAKE_NUM();
                    }, 1000);
                }
                if (XID == 'GET_MAKE_NUM') {
                    $id('MAKE_NUM').innerHTML = '<x>error</x>';
                }
                if (XID == 'auto2dede') {
                    var a = $id('auto2dede');
                    a.innerHTML = '<ss class="red">操作失败，稍后重试</ss>';
                    setTimeout(function() {
                        if (ELSE == '0') {
                            var c = '关闭自动缓存';
                        } else {
                            var c = '开启自动缓存';
                        }
                        a.innerHTML = c;
                    }, 1000);
                }
            }
        }
    }
    xmlHttp.open('GET', URL00, true);
    xmlHttp.send(null);
}

function base(djt) {
    var yes_ok = '';
    if (djt == '1') {
        var yes_ok = "【静态模式】：\n需要更新“首页、栏目页、书页、目录页、内容页”静态页面  ！！！\n需要更新“首页、栏目页、书页、目录页、内容页”静态页面  ！！！\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n确定启用静态模式吗？";
    } else if (djt == '2') {
        var yes_ok = "【伪静态模式】：\n需要配置服务器的“伪静态”功能  ！！！\n需要配置服务器的“伪静态”功能  ！！！\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n确定启用伪静态模式吗？\n（若无“伪静态规则”，可找掌柜要）";
    } else if (djt == '3') {
        var yes_ok = "【半静态模式】：\n需要更新“首页、栏目页、书页、目录页”静态页面  ！！！\n需要更新“首页、栏目页、书页、目录页”静态页面  ！！！\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n确定启用半静态模式吗？";
    } else if (djt == '4') {
        var yes_ok = "【半伪静态模式】：\n需要更新“首页、栏目页、书页、目录页”静态页面  ！！！\n需要更新“首页、栏目页、书页、目录页”静态页面  ！！！\n并且，需要配置服务器的“伪静态”功能  ！！！\n并且，需要配置服务器的“伪静态”功能  ！！！\n----->否则网站无法访问<-----\n----->否则网站无法访问<-----\n确定启用半静态模式吗？\n（若无“伪静态规则”，可找掌柜要）";
    }
    if (yes_ok) {
        var yes_ok = confirm(yes_ok);
        if (!yes_ok) {
            return false;
        }
    }
    $id('xw-box').innerHTML = '';
    $id('timeBox').style.display = 'none';
    $id('base' + djt).innerHTML = '<img src="' + 'make/image/loading.gif">';
    var url = 'make/ajax.php?my=base&djt=' + djt;
    x_get(url, '', 'base', djt);
}

function conBox() {
    var url = 'make/ajax.php?my=conBox';
    x_get(url, 'conBox', 'conBox');
}

function doBox() {
    var url = 'make/ajax.php?my=doBox';
    x_get(url, 'doBox', 'doBox');
}

function Ready(obj) {
    $id('Ready' + obj).innerHTML = '<img src="' + 'make/image/loading.gif">';
    var url = 'make/ajax.php?my=Ready&obj=' + obj;
    x_get(url, 'Ready' + obj, 'Ready', obj);
}

function go(x) {
    if (x) {
        var seobox = $id('seobox');
        if (seobox) {
            for (boxii = 1; boxii < 1000; boxii++) {
                var box = $id('box' + boxii);
                if (!box) {
                    break;
                }
            }
            total = boxii - 1;
            x2x = 'seobox';
        } else {
            var obj = document.getElementsByName("do");
            for (var i = 0; i < obj.length; i++) {
                if (obj[i].checked) {
                    x2x = obj[i].value;
                }
            }
        }
        $id('xw-box').innerHTML = '';
        $id('timeBox').style.display = 'block';
        $id('time').innerHTML = '0';
        tt = 1;
        time();
    }
    if (x2x == 'single1Title') {
        var single = $id('Title1').value;
        if (!single) {
            alert('请输入你要更新的小说名称！');
            return false;
        }
    } else if (x2x == 'single1ID') {
        var single = $id('ID1').value;
        var regPos = /^\d+$/;
        if (!single || !regPos.test(single)) {
            alert('请输入你要更新的小说ID！');
            return false;
        }
    } else if (x2x == 'single2Title') {
        var single = $id('Title2').value;
        if (!single) {
            alert('请输入你要更新的章节名称！');
            return false;
        }
    } else if (x2x == 'single2ID') {
        var single = $id('ID2').value;
        var regPos = /^\d+$/;
        if (!single || !regPos.test(single)) {
            alert('请输入你要更新的章节ID！');
            return false;
        }
    } else if (x2x == 'single1Title_wap') {
        var single = $id('Title1_wap').value;
        if (!single) {
            alert('请输入你要更新的小说名称！');
            return false;
        }
    } else if (x2x == 'single1ID_wap') {
        var single = $id('ID1_wap').value;
        var regPos = /^\d+$/;
        if (!single || !regPos.test(single)) {
            alert('请输入你要更新的小说ID！');
            return false;
        }
    } else if (x2x == 'single2Title_wap') {
        var single = $id('Title2_wap').value;
        if (!single) {
            alert('请输入你要更新的章节名称！');
            return false;
        }
    } else if (x2x == 'single2ID_wap') {
        var single = $id('ID2_wap').value;
        var regPos = /^\d+$/;
        if (!single || !regPos.test(single)) {
            alert('请输入你要更新的章节ID！');
            return false;
        }
    } else {
        var single = '';
    }
    if ($id('seobox')) {
        var x = '正在保存';
    } else {
        var x = '正在更新';
    }
    $id('go').innerHTML = x + '（<img src="' + 'make/image/loading.gif">）';
    var url = 'make/ajax.php?my=go&x2x=' + x2x + '&page=' + page + '&total=' + total + '&single=' + single;
    x_get(url, '', 'go');
}

function SET_MAKE_NUM(id) {
    var MAKE_NUM = $id('MAKE_NUM');
    var inner = MAKE_NUM.innerHTML;
    if (inner.indexOf('loading') > -1) {
        return false;
    }
    MAKE_NUM.innerHTML = '<img src="' + 'make/image/loading.gif">';
    var url = 'make/ajax.php?my=SET_MAKE_NUM&id=' + id;
    x_get(url, '', 'SET_MAKE_NUM');
}

function GET_MAKE_NUM() {
    $id('MAKE_NUM').innerHTML = '<img src="' + 'make/image/loading.gif">';
    var url = 'make/ajax.php?my=GET_MAKE_NUM';
    x_get(url, 'MAKE_NUM', 'GET_MAKE_NUM');
}

function auto2dede() {
    var inner = $id('auto2dede').innerHTML;
    if (inner.indexOf('loading') > -1) {
        return false;
    }
    if (inner.indexOf('关闭') > -1) {
        var djt = '0';
    } else {
        var djt = '1';
    }
    $id('auto2dede').innerHTML = '<img src="' + 'make/image/loading.gif">';
    var url = 'make/ajax.php?my=auto2dede&djt=' + djt;
    x_get(url, '', 'auto2dede', djt);
}

function seo(x) {
    var seobox = $id('seobox');
    if (seobox) {
        if (x == 'yes') {
            alert('设置成功，请更新页面！');
        } else {
            location.reload();
            return false;
        }
    } else {
        $id('seo').innerHTML = '更新页面';
        $id('go').innerHTML = '保存设置';
    }
    var url = 'make/ajax.php?my=seo';
    x_get(url, 'doBox');
}

function seotxt(domain, txt) {
    var url = 'make/ajax.php?my=seotxt&domain=' + domain + '&txt=' + txt;
    x_get(url, '', 'seotxt');
}

function cfgtxt(cfg) {
    var url = 'make/ajax.php?my=cfgtxt&cfg=' + cfg;
    x_get(url);
}