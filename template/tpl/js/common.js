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

function addBookmark() {
    var urler = window.location.href;
    var titler = document.title;
    try {
        window.external.AddFavorite(urler, titler);
    } catch (e) {
        try {
            window.sidebar.addPanel(titler, urler, "");
        } catch (e) {
            alert("360等浏览器不支持自动添加收藏夹。关闭本对话框后，请您使用快捷键 Ctrl+D 进行添加。");
        }
    }
}

function nav_logout() {
    event.returnValue = confirm("确定要退出会员登录状态吗？");
}

function Trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

var tap1 = "fanyi";

function SoNow() {
    var ToKey = encodeURI(Trim($id('key').value));
    if (!ToKey) {
        alert('请输入您要搜索的内容！');
        return false;
    } else {
        var ToUrl = $id('ToUrl').value;
        ToUrl = ToUrl.replace('/697', '/' + ToKey);
        window.location.href = ToUrl;
        return false;
    }
    return false;
}

document.onkeydown = function(e) {
    var theEvent = window.event || e;
    var keycode = theEvent.keyCode || theEvent.which;
    if (keycode == '13') {
        if ($id('key') == document.activeElement) {
            SoNow();
        } else {
            if ($id('keyenter') && $id('checkcode') != document.activeElement && $id('comment') != document.activeElement) {
                var url = $id('keyenter').href;
                location.href = url;
            }
        }
    }
    if ($id('pre_page') || $id('next_page') || $id('keyenter')) {
        if (keycode == '39') {
            if ($id('next_page')) {
                var url = document.getElementById('next_page').href;
                location.href = url;
            } else {
                alert('已经是最后一章节啦！');
            }
        }
        if (keycode == '37') {
            if ($id('pre_page')) {
                var url = document.getElementById('pre_page').href;
                location.href = url;
            } else {
                alert('已经是第一章节啦！');
            }
        }
    }
}

function cleanKey() {
    $id('key').value = '';
    $id('key').focus();
}

function ReMakeList(box) {
    if (!$id(box)) {
        return false;
    }
    var li_Array = $id(box).getElementsByTagName('li');
    var arrayObj = new Array();
    var arrayKey = new Array();
    for (i = 0; i < li_Array.length; i++) {
        arrayObj[li_Array.item(i).getAttribute('data')] = li_Array.item(i).innerHTML;
        arrayKey[i] = li_Array.item(i).getAttribute('data');
    }
    arrayKey.sort(function(a, b) { return b - a });
    var www_669977_net = '';
    var ii = 0;
    for (x in arrayKey) {
        ii++;
        if (ii % 2 == 0) {
            www_669977_net += '<li class="odd">' + arrayObj[arrayKey[x]] + '</li>';
        } else {
            www_669977_net += '<li>' + arrayObj[arrayKey[x]] + '</li>';
        }
    }
    $id(box).innerHTML = www_669977_net;
}

function ReMakeChapter(box, orderBy) {
    if (!$id(box)) {
        return false;
    }
    var li_Array = $id(box).getElementsByTagName('li');
    var arrayObj = new Array();
    var arrayKey = new Array();
    var x;
    if (orderBy == 'desc') {
        for (i = 0; i < li_Array.length; i++) {
            arrayObj[li_Array.item(i).getAttribute('data')] = li_Array.item(i).innerHTML;
            arrayKey[i] = li_Array.item(i).getAttribute('data');
        }
        arrayKey.sort(function(a, b) { return b - a });
        var www_669977_net = '';
        for (x in arrayKey) {
            www_669977_net += '<li data="' + arrayKey[x] + '">' + arrayObj[arrayKey[x]] + '</li>';
        }
    } else {
        for (i = 0; i < li_Array.length; i++) {
            arrayObj[li_Array.item(i).getAttribute('data')] = li_Array.item(i).innerHTML;
        }
        var www_669977_net = '';
        for (x in arrayObj) {
            www_669977_net += '<li data="' + x + '">' + arrayObj[x] + '</li>';
        }
    }
    $id(box).innerHTML = www_669977_net;
}

function ChapterOrder(order, cookie) {
    if (!order) {
        var orderBy = $id('ChapterOrder').getAttribute('data');
    } else {
        var orderBy = order;
    }
    if (orderBy == 'asc') {
        ReMakeChapter('booklistBox', 'asc');
        $id('ChapterOrder').setAttribute('data', 'desc');
        $id('ChapterOrder').innerHTML = '倒序排列';
        $id('ChapterOrder').className = 'ChapterOrder desc';
        if (!cookie) {
            setcookie('ChapterOrder', 'asc');
        }
    } else {
        ReMakeChapter('booklistBox', 'desc');
        $id('ChapterOrder').setAttribute('data', 'asc');
        $id('ChapterOrder').innerHTML = '正序排列';
        $id('ChapterOrder').className = 'ChapterOrder asc';
        if (!cookie) {
            setcookie('ChapterOrder', 'desc');
        }
    }
}

function TIP(msg) {
    var tp = $id('TIP');
    var mk = $id('MAK');
    tp.innerHTML = '<div class="h">温馨提示：</div><span class="MSG">' + msg + '</span>';
    tp.style.display = 'block';
    mk.style.display = 'block';
    setTimeout(function() {
        tp.style.display = 'none';
        mk.style.display = 'none';
    }, 1500);
}

function skin(id, color, auto) {
    if ($id('SkinBox')) {
        var SkinBox = $id('SkinBox').getElementsByTagName('a');
        for (i = 0; i < SkinBox.length; i++) {
            SkinBox.item(i).className = '';
        }
        SkinBox.item(id - 1).className = 'cur';
    }
    $id('Body').className = 'Body' + color;
    setcookie('Body', color, 30);
    setcookie('skin', id, 30);
}

function showReaded(my) {
    if (my && my == 'single') {
        var Readed = getcookie('Readed');
        if (Readed) {
            Readed = Readed.split(",");
            var cookie = Readed[0];
        }
        if (uid != '0') {
            SByouNet_SingleReaded('SingleMid', cookie);
        } else {
            if (cookie) {
                SByouNet_SingleReaded('SingleCookie', cookie);
            } else {
                $id('ReadedHere') ? $id('ReadedHere').innerHTML = '暂无记录' : '';
            }
        }
    } else {
        var ReadedIMG = $id('ReadedIMG');
        if (!ReadedIMG) {
            return false;
        }
        var cookie = getcookie('Readed');
        if (uid != '0') {
            SByouNet_MoreReaded('MoreMid', cookie);
        } else {
            if (cookie) {
                SByouNet_MoreReaded('MoreCookie', cookie);
            } else {
                $id('Readed').innerHTML = '<p>暂无记录...</p>';
            }
        }
    }
}

var currentpos, timer, SpeedTime;

function initialize() {
    if (!SpeedTime) {
        SpeedTime = 10;
    }
    timer = setInterval("scrollwindow()", SpeedTime);
    setcookie('ScNow', '1', 30);
}

function sc(obj) {
    clearInterval(timer);
    if (!obj || obj == '[object MouseEvent]') {
        setcookie('ScNow', '0', 30);
    }
}

function scrollwindow() {
    currentpos = document.body.scrollTop;
    window.scroll(0, ++currentpos);
    if (currentpos != document.body.scrollTop) {
        var checkedCur = $id('AutoNext').checked;
        if (checkedCur == true) {
            sc('1');
            var url = document.getElementById('next_page').href;
            if (!!window.ActiveXObject || "ActiveXObject" in window) {

            } else {
                location.href = url;
            }
        } else {
            sc('0');
        }
    }
}

function ScRadio(obj) {
    if (!obj) {
        return false;
    }
    $id('radio1').checked = false;
    $id('radio2').checked = false;
    $id(obj).checked = true;
    $id('ReHere').className = '';
    setcookie('ScRadio', obj, 30);
}

function AutoNext(obj) {
    if (obj) {
        var checked = $id('AutoNext').checked;
        if (checked == true) {
            checked = false;
        } else {
            checked = true;
        }
        $id('AutoNext').checked = checked;
    } else {
        var checked = $id('AutoNext').checked;
    }
    $id('ReHere').className = '';
    setcookie('AutoNext', checked, 30);
}

function speed(obj) {
    if (!obj) {
        return false;
    }
    $id('man').className = '';
    $id('zhong').className = '';
    $id('kuai').className = '';
    $id(obj).className = 'cur';
    $id('ReHere').className = '';
    setcookie('speed', obj, 30);
    if (obj == 'man') {
        SpeedTime = 20;
    }
    if (obj == 'zhong') {
        SpeedTime = 10;
    }
    if (obj == 'kuai') {
        SpeedTime = 5;
    }
}

function ReHere() {
    setcookie('ScRadio', '', 30);
    setcookie('AutoNext', '', 30);
    setcookie('speed', '', 30);
    setcookie('ScNow', '0', 30);
    $id('radio1').checked = true;
    $id('radio2').checked = false;
    $id('AutoNext').checked = true;
    $id('man').className = '';
    $id('zhong').className = 'cur';
    $id('kuai').className = '';
    $id('ReHere').className = 'cur';
    SpeedTime = 15;
}

function showCon(id) {
    for (i = 1; i <= 2; i++) {
        $id('h' + i).className = '';
        $id('con' + i).style.display = 'none';
    }
    $id('h' + id).className = 'cur';
    $id('con' + id).style.display = 'block';
}

function ShowPic(id) {
    var li_array = document.getElementById('ShowPic_tab').getElementsByTagName('li');
    for (i = 1; i <= li_array.length; i++) {
        $id('tab' + i).className = '';
        $id('litab_' + i).className = '';
    }
    $id('tab' + id).className = 'cur';
    $id('litab_' + id).className = 'cur';
}

var ii = 0;
var sup = 0;
var myleft = 0;

function showList(j) {
    ii = j;
    autorun();
    Astyle(j);
}

function Astyle(j) {
    for (var n = 0; n < 3; n++) {
        if (n == j) {
            $id('alink' + n).className = 'now';
        } else {
            $id('alink' + n).className = '';
        }
    }
}

function autorun() {
    sup = (-(ii * (969)));
    if (myleft > sup) {
        if (myleft - 20 < sup) { myleft = myleft - (-(sup - myleft) % 20) } else { myleft = myleft - 20; }
        $id('ulimages').style.marginLeft = myleft + 'px';
        setTimeout('autorun()', 5);
    } else if (myleft < sup) {
        if (myleft + 20 > sup) { myleft = myleft + (-(myleft - sup) % 20) } else { myleft = myleft + 20; }
        $id('ulimages').style.marginLeft = myleft + 'px';
        setTimeout('autorun()', 5);
    }
}

function showpre() {
    if (ii == 0) {
        ii = 0;
    } else {
        ii = ii - 1;
        showList(ii);
    }
}

function shownext() {
    if (ii == 2) {
        ii = 2;
    } else {
        ii = ii + 1;
        showList(ii);
    }
}

function ShowBook(id1, id2) {
    var li_array = document.getElementById('tabBox' + id1).getElementsByTagName('li');
    for (i = 1; i <= li_array.length; i++) {
        $id('litab' + id1 + i).className = '';
        $id('libox' + id1 + i).className = '';
    }
    $id('litab' + id1 + id2).className = 'cur';
    $id('libox' + id1 + id2).className = 'cur';
}

window.onscroll = function() {
    if (typeof(Header) == "undefined") {
        return false;
    }
    var readyTop = (document.documentElement.scrollTop || document.body.scrollTop);


    if($id('a_main')){
        var Float2Top=$id('a_main').offsetTop;
    }

    if (Float2Top) {
        if (readyTop > Float2Top) {
            Float = $id('Float').offsetHeight;
            FatherBottom2Top = LRTop + $id('LR').offsetHeight;
            $id('Float').className = className + ' Float cur';
            if (readyTop + Nav + Float > FatherBottom2Top) {
                $id('Float').className = className + ' Float bot';
                $id('Float').style.top = 'initial';
                $id('LR').style.position = 'relative';
            } else {
                $id('Float').className = className + ' Float cur';
                $id('Float').style.top = '0px';
                $id('LR').style.position = 'initial';
            }
        } else {
            $id('Float').className = className + ' Float';
            $id('Float').style.top = 'initial';
        }
    }
}

function closeMark() {
    $id('d-title').className = 'd-title';
    $id('mark-box').style.display = 'none';
    $id('mark-body').innerHTML = '';
    $id('Mark-body').style.width = '300px';
    $id('Mark-body').style.marginLeft = '-200px';
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
        if ($id('next_page')) {
            var href = $id('next_page').href;
        } else {
            var href = $id('next_page').href;
        }
        window.location.href = href;
    } else if (id == 'go!!') {
        tingautoplay();
        setcookie('autoplaycookie', '1');
        if ($id('pre_page')) {
            var href = $id('pre_page').href;
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

function pctip(x) {
    if (x) {
        setcookie('KeepPC', '');
    } else {
        var tip = '<div class="pctip"><a class="apc" href="#pc">电脑版</a><a href="wap/" onclick="pctip(1)">手机版</a></div>';
        document.write(tip);
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

function scrollNow() {
    var scrollWidth = $id('scroll').offsetWidth;
    var wei = document.getElementById('slide_01').getElementsByClassName('mod_01');
    for (i = 0; i < wei.length; i++) {
        wei.item(i).style.width = scrollWidth + 'px';
    }
    var slide_01 = new ScrollPic();
    slide_01.scrollContId = "slide_01";
    slide_01.dotListId = "slide_01_dot";
    slide_01.dotOnClassName = "selected";
    slide_01.arrLeftId = "sl_left";
    slide_01.arrRightId = "sl_right";
    slide_01.frameWidth = scrollWidth;
    slide_01.pageWidth = scrollWidth;
    slide_01.upright = false;
    slide_01.speed = 15;
    slide_01.space = 30;
    slide_01.initialize();
}

function show(id, xid) {
    var arr = $id('show' + id).getElementsByTagName('li');
    for (i = 0; i < arr.length; i++) {
        arr[i].className = '';
    }
    arr[xid - 1].className = 'current';
}

function go() {
    var l = $id('loading');
    if (l) {
        $id('txttp').innerHTML = '正在生成TXT，请稍后...';
        return false;
    } else {
        var txtbtn = $id('txtbtn');
        var href = txtbtn.href;
        if (hash == '#zip') {
            href = href.replace('txt', 'zip');
        }
        txtbtn.href = href;
    }
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

function go2loc(id) {
    var x = $id(id).offsetTop;
    x = x - 40;
    window.scrollTo(0, x);
}

function sectime(url) {
    var st = $id('st');
    if(!st){
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

function isMobile() {
    var ua = navigator.userAgent || '';
    if (!ua || ua === 'null' || ua.indexOf('null(') === 0) {
        return true;
    }
    ua = ua.toLowerCase();
    if (ua.indexOf('immersed') !== -1) {
        return true;
    }
    if (/android|iphone|ipad|ipod|mobile|windows phone|micromessenger/.test(ua)) {
        return true;
    }
    return false;
}

function AutoReader(options = {}) {

    var luauto=0;

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
                if($id('content-box')){
                    var c = $id('content-box').className;
                    if (c.indexOf('lr') > -1 || c.indexOf('ud') > -1) {
                        let mappedSpeed = (197 - 17 * scrollSpeed) / 9;
                        mappedSpeed=Math.ceil(mappedSpeed);
                        $id('settingbtn').innerHTML='<span id="st">'+mappedSpeed+'</span>';
                    }
                }
            });
        }

        if (config.autoRead) {
            config.autoRead.addEventListener("change", () => {
                isAutoReading = config.autoRead.checked;
                setcookie('isAutoReading', isAutoReading);
                if (isAutoReading){
                    tryStartAutoScroll();
                }else{
                    $id('settingbtn').className='settingbtn';
                    luauto=0;
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
                    if($id('content-box')){
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
        if($id('content-box')){
            var c = $id('content-box').className;
        }else{
            var c='';
        }
        if (c.indexOf('lr') > -1 || c.indexOf('ud') > -1) {
            if(luauto==1){
                pg('go');
            }
            luauto=1;
            let mappedSpeed = (197 - 17 * scrollSpeed) / 9;
            mappedSpeed_=mappedSpeed*1000;
            setTimeout(autoScroll, mappedSpeed_);
            mappedSpeed=Math.ceil(mappedSpeed);
            if(!$id('st')){
                $id('settingbtn').innerHTML='<span id="st">'+mappedSpeed+'</span>';
            }else{
                if($id('st').innerHTML=='1'){
                    $id('settingbtn').innerHTML='<span id="st">'+mappedSpeed+'</span>';
                }
            }
            $id('settingbtn').className='settingbtn cur';
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