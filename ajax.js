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
    if (xmlHttp == null) {
        alert('Browser does not support HTTP Request!');
        return false;
    }
    xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            var tipbox = $id('tipbox');
            var cDATA = xmlHttp.responseText;
            if (cDATA) {
                if (XID == 'check') {
                    if (cDATA == 'fail') {
                        tipbox.innerHTML = '支付平台不正确';
                        $id('btn').innerHTML = '立即提交';
                    } else if (cDATA == 'no') {
                        tipbox.innerHTML = '插件信息不完整';
                        $id('btn').innerHTML = '立即提交';
                    } else {
                        data = [pay_fn, pay_appid, pay_appsecret, rmb2jinbi, jinbi2jifen, tx2rmb, tx2sxf, pay_dz];
                        step(4);
                    }
                }
                if (XID == 'txt2sql') {
                    var text = $id('text');
                    var inner = text.innerHTML;
                    var btn = $id('btn');
                    if (cDATA == 'error') {
                        txtid = 0;
                        btn.innerHTML = '重新安装';
                        text.innerHTML = inner + error;
                    } else {
                        text.innerHTML = inner + cDATA;
                        if (cDATA.indexOf('success') > -1) {
                            txtid = 0;
                            span = '<span class="green">成功初始化插件，即将继续下一步！</span>';
                            $id('tipp').innerHTML = span;
                            var x = text.innerHTML;
                            text.innerHTML = x + '<p>' + span + '</p>';
                            btn.innerHTML = '即将完成';
                            step(5);
                        } else if (cDATA.indexOf('fail') > -1) {
                            txtid = 0;
                            btn.innerHTML = '重新安装';
                        } else {
                            txtid = txtid + 1;
                            txt2sql();
                        }
                        text.scrollTop = 3000;
                    }
                }
                if (XID == 'step') {
                    if (cDATA == 'eb') {
                        code4msg(3);
                    } else if (cDATA == 'nocode') {
                        code4msg(1);
                        installsetcookie('pancode', '');
                    } else if (cDATA == 'errorcode') {
                        code4msg(2);
                        installsetcookie('pancode', '');
                    } else {
                        if (OTHER) {
                            installsetcookie('pancode', OTHER);
                        }
                        $id('step').innerHTML = cDATA;
                        $id('step').className = 'box step' + ELSE;
                        var hb = $id('hbox');
                        if (hb) {
                            var t = hb.innerHTML;
                            document.title = t;
                        }
                        if (ELSE == 4) {
                            txt2sql();
                        } else if (ELSE == 5) {
                            $id('pay_fn').innerHTML = pay_fn;
                            var hash = window.location.hash;
                            if (hash.indexOf('#3.1v') > -1) {
                                // $id('go').click();
                            } else {
                                // $id('gud').click();
                            }
                        }
                    }
                }
                if (XID == 'code4msg') {
                    var c = $id('code4msg');
                    if (!c) {
                        var c = document.createElement('div');
                        c.className = 'code4msg';
                        c.setAttribute('id', 'code4msg');
                        c.innerHTML = cDATA;
                        $id('body').appendChild(c);
                    } else {
                        c.style.display = 'block';
                        c.innerHTML = cDATA;
                    }
                }
            } else {
                if (XID == 'check') {
                    tipbox.innerHTML = '连接出错，请稍后重试';
                    $id('btn').innerHTML = '立即提交';
                }
                if (XID == 'txt2sql') {
                    var text = $id('text');
                    if (txtid == 0) {
                        text.innerHTML = error;
                    } else {
                        var inner = text.innerHTML;
                        text.innerHTML = inner + error;
                    }
                    txtid = 0;
                    var btn = $id('btn');
                    btn.innerHTML = '重新安装';
                }
                if (XID == 'step') {
                    $id('body').innerHTML = 'error...';
                }
                if (XID == 'code4msg') {
                    alert('连接出错，请稍后重试');
                }
            }
        }
    }
    xmlHttp.open('GET', URL, true);
    xmlHttp.send(null);
}
var pay_fn = '';
var pay_appid = '';
var pay_appsecret = '';
var rmb2jinbi = '';
var jinbi2jifen = '';
var tx2rmb = '';
var tx2sxf = '';
var pay_dz = '';

function check() {
    var loading = $id('loading');
    if (loading) {
        return false;
    }
    pay_fn = $id('pay_fn').value;
    pay_appid = $id('pay_appid').value;
    pay_appsecret = $id('pay_appsecret').value;
    rmb2jinbi = $id('rmb2jinbi').value;
    jinbi2jifen = $id('jinbi2jifen').value;
    tx2rmb = $id('tx2rmb').value;
    tx2sxf = $id('tx2sxf').value;
    pay_dz = $id('pay_dz').value;
    if (pay_dz.indexOf('http') == -1) {
        alert('平台地址不正确！');
        $id('pay_dz').select();
        return false;
    }
    if (!pay_fn || !pay_appid || !pay_appsecret || !rmb2jinbi || !jinbi2jifen || !tx2rmb || !tx2sxf || !pay_dz) {
        alert('插件信息不完整');
        return false;
    }
    $id('btn').innerHTML = '<img id="loading" src="image/loading.gif" />立即提交';
    var url = 'ajax.php?my=check&pay_fn=' + pay_fn + '&pay_appid=' + pay_appid + '&pay_appsecret=' + pay_appsecret + '&rmb2jinbi=' + rmb2jinbi + '&jinbi2jifen=' + jinbi2jifen + '&tx2rmb=' + tx2rmb + '&tx2sxf=' + tx2sxf + '&tx2sxf=' + pay_dz;
    x_get(url, '', 'check');
    return false;
}

var txtid = 0;
var error = '<p class="red">初始化插件出错，请重新安装！</p>';

function txt2sql() {
    var url = 'ajax.php?my=txt2sql&data=' + data + '&txtid=' + txtid;
    x_get(url, '', 'txt2sql');
}

function step(id) {
    if ($id('step_loading')) {
        return false;
    }
    if (!id || typeof(id) == 'undefined') {
        var hash = window.location.hash;
        if (hash.indexOf('#3') > -1) {
            var arr = hash.split('.');
            var pancode = arr[1];
            if (pancode) {
                installsetcookie('pancode', pancode);
            }
            step(3);
            return false;
        }
        id = 1;
    }
    var pancode = installgetcookie('pancode');
    if (id == 1) {
        if (pancode == null) {
            pancode = '';
        }
    } else if (id == 2) {
        var p = $id('pancode');
        if (p) {
            pancode = p.value;
        }
        if (!pancode) {
            code4msg(1);
            return false;
        }
    }
    if (id == 2 || id == 3) {
        var btn = $id('btn');
        if (btn) {
            var inner = btn.innerHTML;
            btn.innerHTML = '<img id="step_loading" src="image/loading.gif" />' + inner;
        }
    }
    var url = 'ajax.php?my=step&id=' + id + '&pancode=' + pancode;
    x_get(url, '', 'step', id, pancode);
}

function code4msg(id) {
    if (!id) {
        var c = $id('code4msg');
        c.style.display = 'none';
        c.innerHTML = '';
        c = $id('pancode');
        if (c) {
            c.focus();
            c.select();
        }
        $id('btn').innerHTML = '开始安装';
        return false;
    }
    var url = 'ajax.php?my=code4msg&id=' + id;
    x_get(url, '', 'code4msg');
}