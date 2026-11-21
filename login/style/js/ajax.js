function $id(id) { return document.getElementById(id); }

function NextTime(id, re) {
    setTimeout(function() {
        var NTime = parseInt($id(id).innerHTML) - 1;
        if (NTime > 0) {
            $id(id).innerHTML = NTime;
            NextTime(id, re);
        } else {
            if (re == '1') {
                top.location.reload();
            } else {
                top.location = LOGIN_URL;
            }
        }
    }, 1000);
}

function GetXmlHttpObject() {
    var xmlHttp = null;
    if (window.ActiveXObject) { try { xmlHttp = new ActiveXObject('Microsoft.XMLHttp'); } catch (e) { xmlHttp = new ActiveXObject('Msxml2.XMLHTTP'); } } else if (window.XMLHttpRequest) { xmlHttp = new XMLHttpRequest(); }
    return xmlHttp;
}

function x_get(URL, OBJ, XID, ELSE, OTHER, MORE, MORE2) {
    URL = URL + '&ttt=' + (new Date).getTime();
    var xmlHttp = null;
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null) { alert('Browser does not support HTTP Request!'); return false; }
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var cDATA = xmlHttp.responseText;
            if (cDATA) {
                $id(OBJ) ? $id(OBJ).innerHTML = cDATA : '';
                if (XID == 'login') {
                    var arr = cDATA.split('{x}');
                    var url = arr[1];
                    var url2 = arr[2];
                    if (url) {
                        var x = url.split('//');
                        var x01 = x[0];
                        x = x[1];
                        x = x.split('/');
                        var x02 = x[0];
                        url = x01 + '//' + x02 + '/';
                        var arr = MORE.split(',');
                        var u = arr[0];
                        var n = arr[1];
                        var href = url + 'login/?my=' + OTHER + '&u=' + u + '&n=' + n + '&hash=' + url2;
                        top.location.href = href;
                    } else {
                        if (url2 && url2.indexOf('http')>-1) {
                            LOGIN_URL = url2;
                        }
                        $id('login').innerHTML = '(<span id="NextTime">2</span>) Login Success!';
                        NextTime('NextTime');
                    }
                }
                if (XID == 'weibo') {
                    if (cDATA == 'Fail') {
                        $id('login').innerHTML = '(<span id="NextTime">2</span>) Sorry Login Fail...';
                        NextTime('NextTime');
                    } else {
                        var cDATA = cDATA.split('-');
                        id = cDATA[0];
                        my = cDATA[1];
                        nickname = cDATA[2];
                        headpic = cDATA[3];
                        hash = cDATA[4];
                        username = cDATA[5];
                        wb = cDATA[6];
                        if (!nickname) {
                            $id('login').innerHTML = '(<span id="NextTime">2</span>) Sorry Login Fail...';
                            NextTime('NextTime');
                        } else {
                            login(id, my, nickname, headpic, hash, username);
                        }
                    }
                }
            } else {
                $id('login').innerHTML = '(<span id="NextTime">2</span>) Sorry Login Fail...';
                NextTime('NextTime');
            }
        }
    }
    xmlHttp.open('GET', URL, true);
    xmlHttp.send(null);
}

function login(id, x, nickname, headpic, hash, username) {
    headpic = encodeURIComponent(headpic);
    var url = 'ajax.php?my=login&id=' + id + '&x=' + x + '&nickname=' + nickname + '&headpic=' + headpic + '&hash=' + hash + '&username=' + username;
    x_get(url, '', 'login', id, x, username + ',' + nickname);
}

function weibo(id, code, wb) {
    var url = 'ajax.php?my=weibo&id=' + id + '&code=' + code + '&wb=' + wb;
    x_get(url, '', 'weibo');
}