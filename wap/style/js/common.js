function $id(id) { return document.getElementById(id); }

function setcookie(name, value, days) {
    days = 30;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getcookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

var tap1 = "fanyi";

function win2top() {
    window.scrollTo(0, 0);
}

function nav_logout() {
    event.returnValue = confirm("确定要退出会员登录状态吗？");
}

document.onkeydown = function(e) {
    var theEvent = window.event || e;
    var keycode = theEvent.keyCode || theEvent.which;
    if (keycode == '13') {
        var curIDxx = event.srcElement.id;
        if (curIDxx == 'wd') {
            so();
        }
    }
};

function chStyle(obj, id) {
    if (obj == 'zt') {
        $id(obj).className = 'left ' + id;
        setcookie('zt_cookie', id, 30);
        $id('C-Box').className = 'C-Box ' + id;
    }
    if (obj == 'ry') {
        $id(obj).className = 'right ' + id;
        setcookie('ry_cookie', id, 30);
        $id('MainBox').className = 'MainBox ' + id;
    }
}

var srcArr = '';
var synth = '';
var msg = '';
// synth = window.speechSynthesis;
// if (synth) {
//     synth.cancel(msg);
//     msg = new SpeechSynthesisUtterance();
// }

function tingplay() {
    if ($id('url2disk')) {
        return false;
    }
    var ti = $id('ting-img');
    var src = ti.getAttribute('crs');
    var er = ti.getAttribute('onerror');
    var tfi = $id('ting-flow-img');
    tfi.src = src;
    tfi.setAttribute('onerror', er);
    var autoplaycookie = getcookie('autoplaycookie');
    if (autoplaycookie === null) {
        autoplaycookie = TING_AUTO_PLAY;
    }
    var tingper = getcookie('ting-control');
    if (tingper) {
        $id('ting-control').className = 'ting-control per' + tingper;
    } else {
        tingper = per;
        $id('ting-control').className = 'ting-control per' + tingper;
    }
    tingapi += per;
    tingapi = tingapi + '&' + tex + '=';
    var content = $id('bodybox').getElementsByTagName('p');
    var pnum = content.length;
    urls = '';
    for (i = 0; i < pnum; i++) {
        var v = content[i].innerHTML;
        v = v.replace(/<[^<>]+>|&nbsp;|nbsp;|nbsp|&/g, '');
        if (!v) {
            continue;
        }
        v = encodeURI(v);
        v = v + '{697}';
        urls += v;
    }
    urls += '{697}';
    urls = urls.replace('{697}{697}', '');
    srcArr = urls.split('{697}');
    if (autoplaycookie == '1') {
        $id('tingautoplay').innerHTML = '√';
    } else {
        $id('tingautoplay').innerHTML = '×';
    }
    if (typeof(hash) == 'undefined') {
        tingapi = '';
    }
    setcookie('autoplaycookie', autoplaycookie);
    $id('tingbox').innerHTML = tingbox;
    var content = new Audio();
    content.id = 'contentplayer';
    content.preload = true;
    content.controls = true;
    content.loop = false;
    var src = tingapi + srcArr.shift();
    src = src.replace('speaker=' + per, 'speaker=' + tingper);
    content.src = src;
    wss();
    content.addEventListener('ended', playEndedHandler, false);
    document.getElementById("tingbox").appendChild(content);
    var tingspdnum = getcookie('tingspdnum');
    if (!tingspdnum) {
        tingspdnum = 8;
    }
    tingspd(tingspdnum, '', 'no');
    if (autoplaycookie == '1' || hash == '#ting') {
        var issafariBrowser = /Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent);
        if (issafariBrowser) {
            tingtip('play?');
        } else {
            document.addEventListener("WeixinJSBridgeReady", function() {
                pplay();
            }, false);
            pplay();
        }
    }

    function playEndedHandler() {
        var src = srcArr.shift();
        if (src) {
            var tingper = getcookie('ting-control');
            if (!tingper) {
                tingper = per;
            }
            src = tingapi + src;
            src = src.replace('speaker=' + per, 'speaker=' + tingper);
            content.src = src;
            wss();
            var tingspdnum = getcookie('tingspdnum');
            if (!tingspdnum) {
                tingspdnum = 8;
            }
            tingspd(tingspdnum, '', 'no');
            pplay();
        } else {
            content.load();
            var autoplaycookie = getcookie('autoplaycookie');
            if (autoplaycookie == '1') {
                tingtip('go!');
            } else {
                tingtip('next?');
            }
        }
    }
    var content = $id('contentplayer');
    content.controls = false;
    content.ontimeupdate = function() {
        var cur = content.currentTime;
        var tcur = secondsFormat(cur);
        var all = content.duration;
        if (!all) {
            all = 0;
        }
        var tall = secondsFormat(all);
        $id('ting-time').innerHTML = tcur + ' / ' + tall;
        tingjindu('ting-cur', cur, all);
        tingjindu('tf-cur', cur, all, 75);
    }
    content.onplay = function() {
        content.controls = false;
        setTimeout(function() {
            $id('tingplaybox').className = 'tingplaybox play';
        }, 697);
    }
    content.onpause = function() {
        $id('tingplaybox').className = 'tingplaybox';
    }
    var tingv02 = getcookie('tingv02');
    if (tingv02) {
        var volume = tingv02;
        content.volume = volume;
    } else {
        var volume = content.volume;
    }
    tingjindu('ting-v-02', volume, 1, 50);
    var tingmuted = getcookie('tingmuted');
    if (tingmuted == '1') {
        content.muted = true;
    }
    content.onvolumechange = function() {
        var volume = content.volume;
        var muted = content.muted;
        if (!volume || muted) {
            $id('ting-muted').className = 'ting-muted cur';
            setcookie('tingmuted', '1');
        } else {
            $id('ting-muted').className = 'ting-muted';
            setcookie('tingmuted', '');
        }
        tingjindu('ting-v-02', volume, 1, 50);
        setcookie('tingv02', volume);
    }
}

function tingautoplay(x) {
    var auto = $id('tingautoplay').innerHTML;
    if (auto == '√' || x == 'x') {
        $id('tingautoplay').innerHTML = '×';
        setcookie('autoplaycookie', '0');
    } else {
        $id('tingautoplay').innerHTML = '√';
        setcookie('autoplaycookie', '1');
    }
}

function tingtip(id) {
    if (id == 'next?' || id == 'play?') {
        $id('tingmark').style.display = 'block';
        $id('tingtip').style.display = 'block';
        if (id == 'next?') {
            $id('tingc01').style.display = 'block';
            $id('tingc02').style.display = 'none';
            $id('tingyes01').style.display = 'block';
            $id('tingyes02').style.display = 'none';
        } else {
            $id('tingc01').style.display = 'none';
            $id('tingc02').style.display = 'block';
            $id('tingyes01').style.display = 'none';
            $id('tingyes02').style.display = 'block';
        }
        return false;
    } else if (id == 'go!') {
        tingautoplay();
        setcookie('autoplaycookie', '1');
        if ($id('keyright')) {
            var href = $id('keyright').href;
        } else {
            var href = $id('next_page').href;
        }
        window.location.href = href;
    } else if (id == 'go!!') {
        tingautoplay();
        setcookie('autoplaycookie', '1');
        if ($id('keyleft')) {
            var href = $id('keyleft').href;
        } else {
            var href = $id('pre_page').href;
        }
        window.location.href = href;
    } else if (id == 'play!') {
        setcookie('autoplaycookie', '1');
        pplay();
    } else {
        setcookie('autoplaycookie', '0');
    }
    if (typeof(hash) == 'undefined') {
        return false;
    }
    $id('tingmark').style.display = 'none';
    $id('tingtip').style.display = 'none';
}

function secondsFormat(t) {
    t = Math.floor(t);
    var s = t % 60;
    var m = (t - s) / 60;
    if (s < 10) {
        s = '0' + s;
    }
    if (m < 10) {
        m = '0' + m;
    }
    t = m + ':' + s;
    return t;
}

function tingerror() {
    var inner = $id('ting-title').innerHTML;
    $id('ting-title').innerHTML = '<span style="color:red">听书功能暂不可用，请稍后重试~</span>';
    setTimeout(function() {
        $id('ting-title').innerHTML = inner;
    }, 1000);
    $id('tingplaybox').className = 'tingplaybox';
}

function tingjindu(id, cur, all, num) {
    if (!num) {
        num = 100;
    }
    var jindu = cur / all;
    var x = $id(id);
    if (x) {
        x.style.width = (jindu * num) + '%';
    }
}

function tingready() {
    var autoplaycookie = getcookie('autoplaycookie');
    if (autoplaycookie === null) {
        autoplaycookie = TING_AUTO_PLAY;
    }
    if (autoplaycookie == '1' || hash == '#ting') {
        pplay();
        return false;
    } else {
        $id('tingautoplay').innerHTML = '×';
    }
    var tingper = getcookie('ting-control');
    if (tingper) {
        $id('ting-control').className = 'ting-control per' + tingper;
    } else {
        $id('ting-control').className = 'ting-control per' + per;
    }
    var tingspdnum = getcookie('tingspdnum');
    if (tingspdnum) {
        tingspd(tingspdnum, '', '', 'no');
    }
}

function tingspd(num, jiajian, noplay, no) {
    var contentplayer = $id('contentplayer');
    if (!contentplayer && no != 'no') {
        pplay();
        tingspd(num, jiajian, noplay);
        return false;
    }
    var spd = $id('spd');
    if (contentplayer) {
        var src = contentplayer.src;
    } else {
        var src = '';
    }
    for (i = 1; i < 16; i++) {
        var x = i / 10;
        var x01 = 'speed=' + x + '&';
        if (src.indexOf(x01) > -1 || no == 'no') {
            if (!num) {
                if (jiajian == 'jia') {
                    var num = i + 1;
                    if (num > 15) {
                        num = 15;
                    }
                } else {
                    var num = i - 1;
                    if (num < 3) {
                        num = 3;
                    }
                }
            }
            spd.innerHTML = num;
            var x = num / 10;
            x02 = 'speed=' + x + '&';
            src = src.replace(x01, x02);
            break;
        }
    }
    $id('spd1').className = '';
    $id('spd0').className = '';
    $id('spd2').className = '';
    if (num == 3) {
        $id('spd1').className = 'cur';
    } else if (num == 8) {
        $id('spd0').className = 'cur';
    } else if (num == 13) {
        $id('spd2').className = 'cur';
    }
    if (typeof(hash) == 'undefined') {
        src = '';
    }
    setcookie('tingspdnum', num);
    if (noplay != 'no' && no != 'no') {
        contentplayer.src = src;
        pplay();
    }
}

function tingflow(id) {
    synth ? synth.cancel(msg) : '';
    var tf = $id('ting-flow');
    if (id == 'i') {
        var t = $id('tingplaybox').offsetTop;
        t = t - 50;
        window.scrollTo(0, t);
    } else if (id == 'p') {
        var cn = tf.className;
        if (cn.indexOf('play') > -1) {
            tingcontrols('pause');
        } else {
            pplay();
        }
    } else if (id == 'n') {
        var arr = urls.split('{697}');
        var total01 = arr.length;
        var total02 = srcArr.length;
        var next = total01 - total02;
        if (next == total01) {
            tingtip('go!');
        } else {
            var contentplayer = $id('contentplayer');
            var src = contentplayer.src;
            var arr = src.split(tex + '=');
            src = arr[0] + tex + '=';
            var arr = urls.split('{697}');
            src = src + arr[next];
            contentplayer.src = src;
            srcArr = [];
            var ii = '';
            var len = arr.length;
            for (i = 0; i < len; i++) {
                if (i > next) {
                    ii++;
                    srcArr[ii - 1] = arr[i];
                }
            }
            pplay(next);
        }
    } else if (id == 'f') {
        var arr = urls.split('{697}');
        var total01 = arr.length;
        var total02 = srcArr.length;
        var next = total01 - total02;
        next = next - 2;
        if (next < 0) {
            tingtip('go!!');
        } else {
            var contentplayer = $id('contentplayer');
            var src = contentplayer.src;
            var arr = src.split(tex + '=');
            src = arr[0] + tex + '=';
            var arr = urls.split('{697}');
            src = src + arr[next];
            contentplayer.src = src;
            srcArr = [];
            var ii = '';
            var len = arr.length;
            for (i = 0; i < len; i++) {
                if (i > next) {
                    ii++;
                    srcArr[ii - 1] = arr[i];
                }
            }
            pplay(next);
        }
    } else if (id == 'c') {
        tingcontrols('pause');
        tingautoplay('x');
        tf.className = 'ting-flow';
    } else {
        tf.className = 'ting-flow play';
        pplay();
    }
}

function tingscroll(x01, x02, jj) {
    if (jj == 'jia' && x01 < x02) {
        x01 = x01 + 10;
        window.scrollTo(0, x01);
        setTimeout(function() {
            tingscroll(x01, x02, jj);
        }, 1);
    }
    if (jj == 'jian' && x01 > x02) {
        x01 = x01 - 10;
        window.scrollTo(0, x01);
        setTimeout(function() {
            tingscroll(x01, x02, jj);
        }, 1);
    }
}

function share4xx(id) {
    var box = $id('ma-img-box-' + id);
    var dis = box.style.display;
    if (dis == 'block') {
        box.style.display = 'none';
        return false;
    }
    var img = $id('ma-img-' + id);
    var src = img.innerHTML;
    if (src) {
        box.style.display = 'block';
        return false;
    }
    var src = img.getAttribute('data');
    img.innerHTML = '<img src="' + src + '" />微信扫一扫';
    box.style.display = 'block';
}

function fnav(id) {
    var arr = $id('fnav').getElementsByTagName('li');
    for (var i = 0; i < arr.length; i++) {
        arr[i].className = '';
    }
    arr[id - 1].className = 'cur';
}

function shownav() {
    var lastread = $id('lastread');
    var toggle = $id('toggle-nav');
    var nav = $id('nav-switch');
    var dis = nav.style.display;
    if (dis == 'block') {
        toggle.className = 'icon icon-more';
        nav.style.display = 'none';
        if (lastread) {
            $id('header').style.background = 'none';
        }
    } else {
        toggle.className = 'icon icon-more active';
        nav.style.display = 'block';
        if (lastread) {
            $id('header').style.background = '#fff';
        }
    }
}

function showcat(id, x01, x02) {
    var s = x01 + 1;
    for (i = s; i < x02; i++) {
        var btn = $id('btntab' + i);
        if (!btn) {
            break;
        }
        btn.className = 'btn-tab';
        $id('tab' + i).style.display = 'none';
    }
    $id('btntab' + id).className = 'btn-tab active';
    $id('tab' + id).style.display = 'block';
}

function showso() {
    var lastread = $id('lastread');
    var so = $id('searchcontainer');
    var dis = so.style.display;
    if (dis == 'block') {
        so.style.display = 'none';
        if (lastread) {
            $id('header').style.background = 'none';
        }
    } else {
        so.style.display = 'block';
        if (lastread) {
            $id('header').style.background = '#fff';
        }
    }
}

function bodybox() {
    var body = $id('Body');
    var bodybg = getcookie('bodybg');
    if (bodybg == null) {
        bodybg = 'hy';
    }
    body.className = bodybg;

    var fontsize = getcookie('fontsize');
    if (fontsize == null) {
        fontsize = '16';
        setcookie('fontsize', fontsize);
    }
    var x01 = 40 - 12;
    var x02 = parseInt(fontsize) - 12;
    var x = x02 / x01;
    var bfb = (x * 100) + '%';
    $id('jd-font').style.width = bfb;
    if (fontsize == '16') {
        $id('jd-mr').style.left = 'calc(' + bfb + ' - 22px)';
    }
    var bodybox = $id('bodybox');
    var contentbox = $id('content-box');
    var contentbody = $id('content-body');
    bodybox.style.top = '0px';
    bodybox.style.left = '0px';
    bodybox.style.fontSize = fontsize + 'px';
    $id('pg01').innerHTML = 1;
    var cn = contentbox.className;
    if (cn.indexOf('normal') > '-1') {
        bodybox.style.width = '100%';
        body.style.overflowY = 'auto';
        contentbody.style.height = 'auto';
        $id('pg02').innerHTML = 1;
        var ps = bodybox.getElementsByTagName('p');
        var length = ps.length;
        var half = length / 2;
        half = parseInt(half);
        if (half > 5) {
            var arcaxd = $id('arcaxd');
            if (arcaxd) {
                var phalf = ps[half];
                var inner = arcaxd.innerHTML;
                arcaxd.innerHTML = '';
                var x = document.createElement('div');
                x.innerHTML = inner;
                bodybox.insertBefore(x, phalf);
            }
        }
        var axd107 = $id('axd107');
        if (axd107) {
            axd107.style.height = '60px';
        }
        return false;
    }
    var axd107 = $id('axd107');
    if (axd107) {
        axd107.style.height = '60px';
        var h107 = 60;
    } else {
        var h107 = 0;
    }
    var ps = bodybox.getElementsByTagName('p');
    var w = document.documentElement.clientWidth;
    var h = document.documentElement.clientHeight;
    h = h - 60 - h107;
    var height = 0;
    var style = 'width:' + w + 'px;height:' + h + 'px';
    var bodyboxcon = '<div class="box" style="' + style + '">';
    var arcaxd = $id('arcaxd');
    if (arcaxd) {
        var plast = ps[ps.length - 1];
        plast.style.height = h + 'px';
        plast.innerHTML = arcaxd.innerHTML;
    }
    var iii = 1;
    for (i = 0; i < ps.length; i++) {
        var li = ps[i];
        var inner = li.innerHTML;
        inner = inner.replace(/^\s*|\s*$/g, '');
        if (!inner) {
            continue;
        }
        var ph = li.offsetHeight;
        height = height + parseInt(ph);
        if (height > h) {
            iii++;
            height = ph;
            var p = '<p class="p' + (i + 1) + '">' + inner + '</p>';
            bodyboxcon = bodyboxcon + '</div><div class="box" style="' + style + '">' + p;
        } else {
            var p = '<p>' + inner + '</p>';
            bodyboxcon = bodyboxcon + p;
        }
    }
    bodyboxcon = bodyboxcon + '</div>';
    if (cn.indexOf('lr') > '-1') {
        contentbody.style.height = 'auto';
        bodybox.style.height = h + 'px';
        bodybox.style.width = (w * iii) + 'px';
    } else {
        contentbody.style.height = h + 'px';
        bodybox.style.height = (h * iii) + 'px';
        bodybox.style.width = w + 'px';
    }
    bodybox.innerHTML = bodyboxcon;
    $id('pg02').innerHTML = iii;
}

var pging = '';

function pg(my) {
    if (!$id('content-body') || !$id('pre_page') || !$id('next_page')) {
        location.reload();
        return false;
    }
    var cn = $id('content-box').className;
    if (cn.indexOf('ready') > '-1') {
        pgshow(2);
        return false;
    } else if (cn.indexOf('pgnav') > '-1' && my != 'pgchapter') {
        pgshow(3);
        return false;
    }
    if (pging) {
        return false;
    }
    pging = '1';
    var bodybox = $id('bodybox');
    if (cn.indexOf('lr') > '-1') {
        var x00 = document.documentElement.clientWidth;
        var x01 = bodybox.offsetLeft;
        var xx = bodybox.offsetWidth;
    } else {
        var x00 = $id('content-body').offsetHeight;
        var x01 = bodybox.offsetTop;
        x01 = x01 - 30;
        var xx = bodybox.offsetHeight;
    }
    var pg01 = $id('pg01');
    var pg01inner = pg01.innerHTML;
    pg01inner = parseInt(pg01inner);
    var pg02 = $id('pg02');
    var pg02inner = pg02.innerHTML;
    pg02inner = parseInt(pg02inner)
    if (my == 'back') {
        if (x01 == '0' || x01 > 0) {
            var href = $id('pre_page').href;
            if (href.indexOf('java') > -1) {
                alert('已经是第一章啦！');
                pging = '';
            } else {
                document.location.href = href;
            }
        } else {
            var x02 = x01 + x00;
            pgact(x01, x02);
            pg01inner = pg01inner - 1;
            pg01.innerHTML = pg01inner;
        }
    } else if (my == 'c') {
        pgshow('c');
        pging = '';
    } else if (my == 'go') {
        if (x01 == (-xx + x00) || x01 < (-xx + x00)) {
            var href = $id('next_page').href;
            if (href.indexOf('java') > -1) {
                alert('已经是最后一章啦！');
                pging = '';
            } else {
                document.location.href = href;
            }
        } else {
            pg01inner = pg01inner + 1;
            if (pg01inner > pg02inner) {
                return false;
            }
            pg01.innerHTML = pg01inner;
            var x02 = x01 - x00;
            pgact(x01, x02);
        }
    } else if (my == 'pgchapter') {
        pgshow('pgchapter');
        pging = '';
    }
}

function pgact(x01, x02) {
    var contentbox = $id('content-box');
    var cn = contentbox.className;
    var bodybox = $id('bodybox');
    var width = bodybox.offsetWidth;
    if (navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)) {
        var x = 20;
    } else {
        var x = 20;
    }
    if (x01 == x02) {
        pging = '';
        return false;
    } else if (x01 > x02) {
        x01 = x01 - x;
        if (x01 < x02) {
            x01 = x02;
        }
    } else if (x01 < x02) {
        x01 = x01 + x;
        if (x01 > x02) {
            x01 = x02;
        }
    }
    if (cn.indexOf('lr') > '-1') {
        bodybox.style.left = x01 + 'px';
    } else {
        bodybox.style.top = x01 + 'px';
    }
    setTimeout(function() {
        pgact(x01, x02);
    }, 1);
}

function pgshow(my) {
    var contentbox = $id('content-box');
    var cn = contentbox.className;
    var pgready = getcookie('pgready');
    var bodyboxcookie = getcookie('bodyboxcookie');
    if (bodyboxcookie == null) {
        bodyboxcookie = 'lr';
        setcookie('bodyboxcookie', bodyboxcookie);
    }
    if (my == '1') {
        if (bodyboxcookie == 'normal') {
            contentbox.className = 'content-box ' + bodyboxcookie;
        } else {
            if (pgready == null) {
                contentbox.className = 'content-box ready ' + bodyboxcookie;
            } else {
                contentbox.className = 'content-box ' + bodyboxcookie;
            }
            pgtip(bodyboxcookie);
            touchpg('pgl');
            touchpg('pgc');
            touchpg('pgr');
        }
    } else if (my == '2') {
        cn = cn.replace(' ready', '');
        contentbox.className = cn + ' pgnav';
        setcookie('pgready', '1');
        jindu(id, xid);
    } else if (my == 'c') {
        contentbox.className = cn + ' pgnav';
        jindu(id, xid);
    } else if (my == 'pgchapter') {
        var pgchapter = $id('pgchapter');
        var c = pgchapter.className;
        if (c == 'pgchapter') {
            pgchapter.className = 'pgchapter cur';
            if ($id('cboximg')) {
                chapte4pg(id, xid);
            } else {
                pgchapterloc();
            }
        } else {
            pgchapter.className = 'pgchapter';
        }
    } else {
        $id('content-box').className = 'content-box ' + bodyboxcookie;
        pgmark('no');
    }
}

function pgtime() {
    var date = new Date();
    var hh = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
    var mm = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes());
    var time = hh + mm;
    $id('pgtime').innerHTML = time;
}

function pgmark(my) {
    var pgmark = $id('pgmark');
    if (my == 'no') {
        pgmark.className = 'pgmark';
        return false;
    }
    var cn = pgmark.className;
    if (cn.indexOf('cur') > -1) {
        if (cn.indexOf(my) > -1) {
            pgmark.className = 'pgmark';
        } else {
            pgmark.className = 'pgmark cur ' + my;
        }
    } else {
        pgmark.className = 'pgmark cur ' + my;
    }
}

function normalpgnav(my) {
    var contentbox = $id('content-box');
    var normalbg = $id('normalbg');
    if (my) {
        contentbox.className = 'content-box normal pgnav';
        pgmark('set');
    } else {
        contentbox.className = 'content-box normal';
        $id('pgmark').className = 'pgmark';
    }
}

function pgchapterloc() {
    var cbox = $id('cbox');
    var t = cbox.scrollTop;
    var h = cbox.offsetHeight;
    var lit = $id('li' + xid).offsetTop;
    var top = lit - (h / 2);
    cbox.scrollTop = top;
}

function pglog() {
    var LoginBox = $id('LoginBox');
    var inner = LoginBox.innerHTML;
    LoginBox.innerHTML = '';
    $id('LoginBoxPg').innerHTML = inner;
}

function pgfy(my) {
    if (my == 'lr' || my == 'ud') {
        setcookie('autoSwitch2', '');
    }
    var fyimg = $id('fyimg');
    var fy = $id('fy-' + my);
    var inner = fy.innerHTML;
    if (!fyimg) {
        var x = '<img id="fyimg" src="style/image/loading.gif" />';
        fy.innerHTML = x + inner;
    } else {
        fy.innerHTML = '稍后重试...';
        window.setTimeout(function() {
            fy.innerHTML = inner;
        }, 1000);
        return false;
    }
    setcookie('bodyboxcookie', my);
    var contentbox = $id('content-box');
    var cn = contentbox.className;
    if (cn.indexOf('normal') > -1) {
        $id('Body').style.overflow = 'hidden';
    }
    cn = cn.replace(' normal', '');
    cn = cn.replace(' lr', '');
    cn = cn.replace(' ud', '');
    contentbox.className = cn + ' ' + my;
    bodybox();
    location.reload();
}

function pgzt(my) {
    var fontsize = getcookie('fontsize');
    fontsize = parseInt(fontsize);
    if (my == 'jian') {
        fontsize = fontsize - 2;
        if (fontsize < 12) {
            fontsize = 12;
        }
    } else if (my == 'mr') {
        fontsize = 16;
    } else {
        fontsize = fontsize + 2;
        if (fontsize > 40) {
            fontsize = 40;
        }
    }
    setcookie('fontsize', fontsize);
    bodybox();
}

function pgbg(my, auto) {
    var bodybg = getcookie('bodybg');
    if (!my) {
        if (bodybg == null) {
            bodybg = 'hy';
        }
        $id('Body').className = bodybg;
        return false;
    }
    if (auto == 'auto') {
        if (bodybg == 'yj') {
            my = 'hy';
        } else {
            my = 'yj';
        }
    }
    setcookie('bodybg', my);
    bodybox();
}

var x01 = 0;
var triggered = false; // 避免连续触发

function touchpg(id) {
    var wei = document.getElementById(id);

    wei.ontouchstart = function(e) {
        var touch = e.touches[0];
        var direction = getcookie('bodyboxcookie'); // lr 或 tb

        if (direction == 'lr') {
            x01 = touch.clientX;
        } else {
            x01 = touch.clientY;
        }
        triggered = false; // 每次重新开始
    };

    wei.ontouchmove = function(e) {
        e.preventDefault();

        if (triggered) return; // 已经触发过翻页则不再继续判断

        var touch = e.touches[0];
        var direction = getcookie('bodyboxcookie');

        var x02 = (direction == 'lr') ? touch.clientX : touch.clientY;
        var diff = x02 - x01;

        if (diff > 30) {
            triggered = true;
            pg('back');
        } else if (diff < -30) {
            triggered = true;
            pg('go');
        }
    };

    wei.ontouchend = function() {
        x01 = 0;
        triggered = false;
    };
}

function pgtip(my) {
    if (my == 'lr') {
        var x = '翻页方式：左右滑动/左右点击 (点击屏幕中间呼出菜单)';
    } else {
        var x = '翻页方式：上下滑动/左右点击 (点击屏幕中间呼出菜单)';
    }
    window.setTimeout(function() {
        $id('pgtip').innerHTML = x;
    }, 1500);
}

function ma2wap(x) {
    if (x == 'x') {
        setcookie("ma2wap", "x");
        $id('ma2wap').style.display = 'none';
        return false;
    }
    var w = document.documentElement.clientWidth;
    if (w < 697) {
        return false;
    }
    if (!navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)) {
        var x = getcookie('ma2wap');
        if (x !== 'x') {
            var imgsrc = $id('ma2wap-img').getAttribute('imgsrc');
            var x = '<img src="' + imgsrc + '" />手机访问，效果更好';
            $id('ma2wap-img').innerHTML = x;
            $id('ma2wap').style.display = 'block';
        }
    }
}

function randtime() {
    setTimeout(function() {
        $id('time').innerHTML = $id('time').innerHTML - 1;
        randtime();
    }, 1000);
}

function Trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

function SoNow() {
    var ToKey = encodeURI(Trim($id('key').value));
    if (!ToKey) {
        alert('请输入您要搜索的内容！');
        return false;
    } else {
        var ToUrl = $id('ToUrl').value;
        ToUrl = ToUrl.replace('/697/', '/' + ToKey + '/');
        window.location.href = ToUrl;
        return false;
    }
    return false;
}

function closemark() {
    $id('mark-box').style.display = 'none';
    $id('mark-body').innerHTML = '';
    document.documentElement.style.overflowY = 'auto';
    document.body.style.overflowY = 'auto';
}

function imgsrc(img) {
    for (var i = 0; i < img.length; ++i) {
        var i_ = img[i];
        var o = i_.isIntersecting;
        if (o == undefined || o) {
            var x = i_.target;
            var crs = x.getAttribute('crs');
            if (crs) {
                x.setAttribute('src', crs);
            }
            if (o) {
                observer.unobserve(x);
            }
        }
    }
}

function sectime(url) {
    var st = $id('st');
    if (!st) {
        return false;
    }
    var inner = st.innerHTML;
    inner = parseInt(inner);
    setTimeout(function() {
        if (inner > 1) {
            st.innerHTML = inner - 1;
            sectime(url);
        } else {
            if (url && url != 'undefined') {
                location.href = url;
            }
        }
    }, 1000);
}

function AutoReader(options = {}) {

    var luauto = 0;

    const config = {
        autoRead: document.getElementById("autoRead"),
        speedSlider: document.getElementById("speed"),
        speedValue: document.getElementById("speedValue"),
        autoNext: document.getElementById("autoNext"),
        autoNext2: document.getElementById("autoNext2"),
        autoRestart: document.getElementById("autoRestart"),
        neturl: $id('next_page') ? $id('next_page').href : "",
        firsturl: options.firsturl || "",
        tip_: document.querySelector(".tip_")
    };

    const readCookieBool = (name, def) => {
        const v = getcookie(name);
        if (v === null || v === undefined || v === "") return def;
        return v === "true";
    };

    let scrollSpeed = parseInt(
        getcookie('scrollSpeed') ||
        (config.speedSlider && config.speedSlider.value ? config.speedSlider.value : 10),
        10
    );
    let isAutoReading = readCookieBool('isAutoReading', false);
    let autoSwitch = readCookieBool('autoSwitch', true);
    let autoSwitch2 = readCookieBool('autoSwitch2', false);
    let restartAfterFinish = readCookieBool('restartAfterFinish', true);

    const throttledScroll = throttle(checkScroll, 1000);

    if (autoSwitch2) {

        window.addEventListener("scroll", throttledScroll);
        checkContentHeight();

        document.querySelector(".autoNext").style.opacity = '0.2';
    }

    function initUI() {
        if (config.tip_) {
            isAutoReading = false;
            autoSwitch = false;
            autoSwitch2 = false;
            restartAfterFinish = false;
        }

        if (config.speedSlider) config.speedSlider.value = scrollSpeed;
        if (config.speedValue) config.speedValue.textContent = scrollSpeed;

        if (config.autoRead) config.autoRead.checked = isAutoReading;
        if (config.autoNext) config.autoNext.checked = autoSwitch;
        if (config.autoNext2) config.autoNext2.checked = autoSwitch2;
        if (config.autoRestart) config.autoRestart.checked = restartAfterFinish;
    }

    function bindEvents() {
        if (config.speedSlider) {
            config.speedSlider.addEventListener("input", () => {
                scrollSpeed = config.speedSlider.value;
                config.speedValue.textContent = scrollSpeed;
                setcookie('scrollSpeed', scrollSpeed);
                if ($id('content-box')) {
                    var c = $id('content-box').className;
                    if (c.indexOf('lr') > -1 || c.indexOf('ud') > -1) {
                        let mappedSpeed = (197 - 17 * scrollSpeed) / 9;
                        mappedSpeed = Math.ceil(mappedSpeed);
                        $id('settingbtn').innerHTML = '<span id="st">' + mappedSpeed + '</span>';
                    }
                }
            });
        }

        if (config.autoRead) {
            config.autoRead.addEventListener("change", () => {
                isAutoReading = config.autoRead.checked;
                setcookie('isAutoReading', isAutoReading);
                if (isAutoReading) {
                    tryStartAutoScroll();
                } else {
                    $id('settingbtn').className = 'settingbtn';
                    luauto = 0;
                }
            });
        }

        if (config.autoNext) {
            config.autoNext.addEventListener("change", () => {
                if (!autoSwitch2) {
                    autoSwitch = config.autoNext.checked;
                    setcookie('autoSwitch', autoSwitch);
                } else {
                    config.autoNext.checked = autoSwitch;
                }
            });
        }

        if (config.autoNext2) {
            config.autoNext2.addEventListener("change", () => {
                if (autoSwitch2) {
                    window.removeEventListener("scroll", throttledScroll);
                    document.querySelector(".autoNext").style.opacity = '1';
                } else {
                    window.addEventListener("scroll", throttledScroll);
                    document.querySelector(".autoNext").style.opacity = '0.2';
                    if ($id('content-box')) {
                        var c = $id('content-box').className;
                        if (c.indexOf('lr') > -1 || c.indexOf('ud') > -1) {
                            pgfy('normal');
                        }
                    }
                }
                autoSwitch2 = config.autoNext2.checked;
                setcookie('autoSwitch2', autoSwitch2);
            });
        }

        if (config.autoRestart) {
            config.autoRestart.addEventListener("change", () => {
                restartAfterFinish = config.autoRestart.checked;
                setcookie('restartAfterFinish', restartAfterFinish);
            });
        }
    }

    function autoScroll() {
        if (!isAutoReading) return;
        if ($id('content-box')) {
            var c = $id('content-box').className;
        } else {
            var c = '';
        }
        if (c.indexOf('lr') > -1 || c.indexOf('ud') > -1) {
            if (luauto == 1) {
                pg('go');
            }
            luauto = 1;
            let mappedSpeed = (197 - 17 * scrollSpeed) / 9;
            mappedSpeed_ = mappedSpeed * 1000;
            setTimeout(autoScroll, mappedSpeed_);
            mappedSpeed = Math.ceil(mappedSpeed);
            if (!$id('st')) {
                $id('settingbtn').innerHTML = '<span id="st">' + mappedSpeed + '</span>';
            } else {
                if ($id('st').innerHTML == '1') {
                    $id('settingbtn').innerHTML = '<span id="st">' + mappedSpeed + '</span>';
                }
            }
            $id('settingbtn').className = 'settingbtn cur';
            sectime();
        } else {

            const pageHeight = document.documentElement.scrollHeight;
            window.scrollBy(0, 3);

            if (window.scrollY + window.innerHeight >= pageHeight) {
                if (config.neturl.includes('alert') || $id('lastchapter')) {
                    if (restartAfterFinish) location.href = config.firsturl;
                    isAutoReading = false;
                    return;
                } else {
                    if (!autoSwitch2) {
                        if (autoSwitch) location.href = config.neturl;
                        isAutoReading = false;
                    } else {
                        // 
                    }
                    return;
                }
            }
            setTimeout(autoScroll, 100 / scrollSpeed);
        }
    }

    function tryStartAutoScroll() {
        if (!isAutoReading) return;
        if ($id('url2disk')) {
            setTimeout(() => {
                if ($id('url2disk')) {
                    setTimeout(() => {
                        if (!$id('url2disk')) autoScroll();
                    }, 3000);
                } else {
                    autoScroll();
                }
            }, 3000);
        } else {
            autoScroll();
        }
    }

    function toggleSettingsBox() {
        if (config.tip_) {
            alert('登录订阅才可启用自动阅读功能哦！');
            return false;
        }
        const settingsBox = document.querySelector(".settings-box");
        settingsBox.style.display = settingsBox.style.display === 'block' ? 'none' : 'block';
    }

    return {
        init() {
            initUI();
            bindEvents();
            if (isAutoReading) tryStartAutoScroll();
        },
        toggleSettingsBox
    };
}

function throttle(fn, delay) {
    let lastTime = 0;
    let timer = null;
    return function(...args) {
        const now = Date.now();
        const remaining = delay - (now - lastTime);

        if (remaining <= 0) {
            if (timer) {
                clearTimeout(timer);
                timer = null;
            }
            fn.apply(this, args);
            lastTime = now;
        } else if (!timer) {
            timer = setTimeout(() => {
                fn.apply(this, args);
                lastTime = Date.now();
                timer = null;
            }, remaining);
        }
    };
}

function onNearBottom() {
    ajaxcontent(id, xid);
}

function checkScroll() {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const docHeight = document.documentElement.scrollHeight;
    const distanceToBottom = docHeight - (scrollTop + windowHeight);
    if (distanceToBottom <= 1000) {
        onNearBottom();
    }
}

function checkContentHeight() {
    const docHeight = document.documentElement.scrollHeight;
    const windowHeight = window.innerHeight;
    if (docHeight <= windowHeight) {
        onNearBottom();
    }
}

function initZbtjSwiper() {
    const slides = Array.from(
        document.querySelectorAll('.swiper_item_zbtj')
    );
    const infos = Array.from(
        document.querySelectorAll('.infobox > div')
    );
    const total = slides.length;
    const activeClasses = ['active1', 'active2', 'active3', 'active4', 'active5'];
    const slotMaps = {
        1: [0],
        2: [0, 2],
        3: [0, 2, 1],
        4: [0, 2, 4, 1],
        5: [0, 2, 4, 3, 1]
    };
    let current = 0;
    let startX = 0;
    let deltaX = 0;
    let dragging = false;
    let timer = null;

    function fix(i) {
        return (i % total + total) % total;
    }

    function update() {
        slides.forEach(slide => {
            activeClasses.forEach(c => slide.classList.remove(c));
        });

        const slots = slotMaps[total] || slotMaps[5];
        for (let i = 0; i < total; i++) {
            const relative = fix(i - current);
            const slot = slots[relative];
            if (slot !== undefined) {
                slides[i].classList.add(activeClasses[slot]);
            }
        }

        infos.forEach(el => el.classList.remove('cur'));
        if (infos[fix(current)]) {
            infos[fix(current)].classList.add('cur');
        }
    }

    function next() {
        current = fix(current + 1);
        update();
    }

    function prev() {
        current = fix(current - 1);
        update();
    }

    function startAuto() {
        timer = setInterval(next, 3000);
    }

    function stopAuto() {
        clearInterval(timer);
    }

    function restartAuto() {
        stopAuto();
        startAuto();
    }
    const container = document.querySelector('.uni-swiper-slides');
    container.addEventListener('pointerdown', (e) => {
        dragging = true;
        startX = e.clientX;
        deltaX = 0;
        stopAuto();
        e.preventDefault();
        if (container.setPointerCapture) {
            container.setPointerCapture(e.pointerId);
        }
    });
    container.addEventListener('pointermove', (e) => {
        if (!dragging) return;
        deltaX = e.clientX - startX;
    });

    function endDrag() {
        if (!dragging) return;
        dragging = false;

        if (Math.abs(deltaX) > 50) {
            deltaX > 0 ? prev() : next();
        }

        restartAuto();
    }
    container.addEventListener('pointerup', endDrag);
    container.addEventListener('pointercancel', endDrag);
    infos.forEach((el, i) => {
        el.addEventListener('click', () => {
            current = i;
            update();
            restartAuto();
        });
    });
    update();
    startAuto();
}
