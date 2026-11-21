function GetXmlHttpObject() {
    var xmlHttp = null;
    if (window.ActiveXObject) {
        try {
            xmlHttp = new ActiveXObject('Microsoft.XMLHttp');
        } catch (e) {
            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
        }
    } else if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }
    return xmlHttp;
}

function x_get(URL, OBJ, XID, ELSE, OTHER) {
    URL = URL + '&ttt=' + (new Date).getTime();
    var xmlHttp = null;
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) { alert('Browser does not support HTTP Request!'); return false; }
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var cDATA = xmlHttp.responseText;
            if (cDATA) {
                $id(OBJ) ? $id(OBJ).innerHTML = cDATA : '';
                if (XID.indexOf('DEL') > -1) {
                    if (XID.indexOf('look') > -1) {
                        var arr = cDATA.split('{697}');
                        var x = arr[0];
                        setcookie('Readed', x);
                    }
                    var d = $id('delbox' + ELSE);
                    var t = $id('title' + ELSE);
                    d.href = "javascript:";
                    if (XID.indexOf('all') > -1) {
                        d.innerHTML = '已清空';
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        d.innerHTML = '已删除';
                        t.style.textDecoration = 'line-through';
                        t.style.color = '#f53';
                        if (XID.indexOf('fav') > -1) {
                            setcookie('addCollect' + ELSE, '');
                        }
                        if (XID.indexOf('look') > -1) {
                            setcookie('look' + ELSE, '');
                        }
                        if (XID.indexOf('good') > -1 || XID.indexOf('bad') > -1) {
                            setcookie('addGood' + ELSE, '');
                            setcookie('addBad' + ELSE, '');
                        }
                    }
                }
                if (XID == 'minlist') {
                    $id('minlist').innerHTML = cDATA;
                }
                if (XID == 'biglist') {
                    $id('biglist').innerHTML = cDATA;
                }
            } else {
                if (XID.indexOf('DEL') > -1) {
                    var a = $id('delbox' + ELSE);
                    a.innerHTML = '失败...';
                    setTimeout(function() {
                        a.innerHTML = '删除记录';
                    }, 500);
                }
                if (XID == 'minlist' || XID == 'biglist') {
                    $id('minlist').innerHTML = '<p class="nolist">数据有误，请稍后重试...</p>';
                }
            }
        }
    }
    xmlHttp.open('GET', URL, true);
    xmlHttp.send(null);
}

function MissData(my, id) {
    if (id == undefined) {
        id = '';
    }
    $id('delbox' + id).innerHTML = '删除中...';
    url = 'include/member/ajax.php?my=' + my + '&id=' + id;
    if (my.indexOf('look') > -1) {
        var Readed = getcookie('Readed');
        url += '&lc=' + Readed;
    }
    x_get(url, '', my, id);
}

function wb(sid) {
    var x = top.location.href;
    x = sid + '{x}' + x;
    url = 'include/member/ajax.php?my=wb&sid=' + x;
    x_get(url);
}

function minlist() {
    var Readed = getcookie('Readed');
    url = 'include/member/ajax.php?my=minlist&Readed=' + Readed;
    x_get(url, '', 'minlist');
}

function biglist(page) {
    var Readed = getcookie('Readed');
    url = 'include/member/ajax.php?my=biglist&Readed=' + Readed + '&page=' + page;
    x_get(url, '', 'biglist');
}