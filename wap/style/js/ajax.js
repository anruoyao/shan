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
                if (XID == 'v') {
                    var x = cDATA.replace('ok', '');
                    if (x) {
                        var xc = $id(XID + ELSE + '_');
                        if (xc) {
                            xc.innerHTML = x;
                        }
                    }
                    if (OTHER == 'no') {
                        var x = getcookie(XID + ELSE);
                        if (x != '1') {
                            setcookie(XID + ELSE, '2');
                        }
                    } else {
                        if (!OTHER) {
                            var c = getcookie(XID + ELSE);
                            if (c == '1') {
                                var x = '已经贡献过热度啦！';
                            } else {
                                var x = '成功贡献一个热度！';
                            }
                            alert(x);
                        }
                        setcookie(XID + ELSE, '1');
                    }
                }
                if (XID == 'l' || XID == 'o') {
                    var x = cDATA.replace('ok', '');
                    if (x) {
                        $id(XID + ELSE + '_').innerHTML = x;
                    }
                    if (XID == 'l') {
                        var x = '已经成功推荐！';
                    } else {
                        var x = '成功加油助威！';
                    }
                    var c = getcookie('lo' + ELSE);
                    if (c == '1') {
                        x = '已经推荐或加油过啦！';
                    }
                    alert(x);
                    setcookie('lo' + ELSE, '1');
                }
                if (XID == 'f') {
                    if (cDATA == 'login') {
                        alert('登录才可收藏哦！');
                        var url = PC_URL + '?member/login/';
                        if (typeof openAuthModal === 'function') openAuthModal('login');
                        else location.href = url;
                    } else if (cDATA == 'ok') {
                        alert('成功收藏啦！');
                    }
                }
                if (XID == 'sitemap') {
                    var tt = (new Date).getTime();
                    setcookie('sitemap_wap', tt);
                }
                if (XID == 'chapte4pg') {
                    $id('cboxul').innerHTML = cDATA;
                    pgchapterloc();
                }
                if (XID == 'pgsq') {
                    $id('pgsq').innerHTML = '添加成功';
                    $id('pgsq').style.color = 'red';
                }
                if (XID == 'ccbox') {
                    $id('ccbox').innerHTML = cDATA;
                    $id('cIMG').style.display = 'none';
                    if (ELSE == 'yes') {
                        var x = $id('CommentBox').offsetTop;
                        window.scrollTo(0, x);
                    }
                }
                if (XID == 'url2disk') {
                    var arr = cDATA.split('{697}');
                    var content = arr[0];
                    var id = arr[1];
                    $id('bodybox').innerHTML = content;
                    bodybox();
                    tingplay();
                }
                if (XID == 'mark-body') {
                    $id('mark-body').innerHTML = '<div class="ios-iframe" id="ios-iframe">' + cDATA + '</div>';;
                }
                if (XID == 'axd_free') {
                    location.reload();
                }
                if (XID == 'hf') {
                    if (cDATA == 'reload') {
                        location.reload();
                    } else {
                        if (ELSE == 'go') {
                            var recombtn = $id('recombtn');
                            if (cDATA == 're') {
                                alert('您今天已经回复过啦！');
                                recombtn.value = '提交回复';
                            } else if (cDATA == 'zj') {
                                alert('不能回复自己哦！');
                                recombtn.value = '提交回复';
                            } else {
                                recombtn.value = '回复成功';
                                setTimeout(function() {
                                    recombtn.value = '提交回复';
                                    $id('recom').value = '';
                                    hf(OTHER);
                                }, 1000);
                            }
                        } else if (ELSE == 'del') {
                            hf(OTHER);
                        } else {
                            $id('recomment').innerHTML = cDATA;
                        }
                    }
                }
                if (XID == 'read_xxx') {
                    setcookie('read_xxx_' + ELSE, 1);
                }
                if (XID == 'xxxaxd') {
                    var arr = cDATA.split('{x}');
                    cDATA = arr[0];
                    var url = arr[1];
                    if (!url) {
                        var x = location.href;
                        x = x.split('#');
                        url = x[0];
                    }
                    if (cDATA == 'ok' || cDATA == 'dis' || cDATA == 'login') {
                        location.href = url;
                    }
                }
                if (XID == 'ajaxcontent') {
                    var arr = cDATA.split('{697}');
                    cDATA = arr[0];
                    xxidxx = arr[1];
                    var url = arr[2];
                    var url1 = arr[3];
                    var url2 = arr[4];
                    var xtitle = arr[5];
                    arr = xxidxx.split(',');
                    id = arr[0];
                    xid = arr[1];
                    const content = $id('bodybox');
                    if(ajax_content==1){
                        content.innerHTML += cDATA;
                    }else{
                        content.innerHTML = cDATA;
                    }
                    if (id && xid) {
                        vlof('v', id, xid);
                    }
                    history.pushState({}, '', url);
                    $id('pre_page').href = url1;
                    $id('next_page').href = url2;
                    $id('pgs') ? $id('pgs').href = url1 : '';
                    $id('pgx') ? $id('pgx').href = url2 : '';
                    xtitle ? document.title = xtitle : '';
                    $id('li' + ELSE) ? $id('li' + ELSE).className = '' : '';
                    $id('li' + xid) ? $id('li' + xid).className = 'cur' : '';
                    checkContentHeight();
                    ajax_content=1;
                }
                if(XID=='sutodychapter'){
                    if(cDATA=='login'){
                        var url='?member/login/';
                        if (typeof openAuthModal === 'function') openAuthModal('login');
                        else location.href=url;
                    }else if(cDATA=='ok'){
                        location.reload();
                    }else if(cDATA.indexOf('pay,http')>-1){
                        var x=cDATA.split(',');
                        var url=x[1];
                        location.href=url;
                    }else{
                        $id('tip_')?$id('tip_').style.display='none':'';
                        $id('bodybox').innerHTML=cDATA;
                        bodybox();
                    }
                }
                if (XID == 'xidcmt') {
                    $id('ulrec').innerHTML=cDATA;
                    $id('idx').value=ELSE;
                }
            } else {
                if (XID == 'chapte4pg') {
                    alert('系统有误，请刷新重试...');
                }
                if (XID == 'pgsq') {
                    alert('系统有误，请刷新重试...');
                }
                if (XID == 'ccbox') {
                    $id('cIMG').style.display = 'none';
                    $id('ulc').innerHTML = '<div class="errorCom">数据有误，稍后重试...</div>';
                    var x = $id('CommentBox').offsetTop;
                    window.scrollTo(0, x);
                }
                if (XID == 'url2disk') {
                    $id('bodybox').innerHTML = '<p>转码失败，请稍后重试...</p>';
                }
            }
        }
    }
    xmlHttp.open('GET', URL, true);
    xmlHttp.send(null);
}

function read_xxx(xid) {
    var x = getcookie('read_xxx_' + xid);
    if (x == '1') {
        return false;
    }
    var url = 'ajax.php?my=read_xxx&xid=' + xid;
    x_get(url, '', 'read_xxx', xid);
}

function vlof(my, id, xid) {
    if (typeof(uid) == 'undefined') {
        uid = 0;
        username = '';
        register_time = '';
    }
    if (typeof(xid) == 'undefined') {
        xid = '';
    }
    read_xxx(xid);
    var xxx_cookie = getcookie(my + id);
    if (my == 'l' || my == 'o') {
        xxx_cookie = getcookie('lo' + id);
    }
    url = 'ajax.php?my=' + my + '&id=' + id + '&xid=' + xid + '&xxx_cookie=' + xxx_cookie + '&uid=' + uid + '&username=' + username + '&register_time=' + register_time;
    x_get(url, '', my, id, xid);
}

var per = '5';

function tingcontrols(id, x) {
    var content = $id('contentplayer');
    if (!content) {
        setTimeout(function() {
            tingplay();
            tingcontrols(id, x);
        }, 1000);
        return false;
    }
    if (id == 'play') {
        var src = content.src;
        var tingspdnum = getcookie('tingspdnum');
        if (tingspdnum) {
            tingspdnum = tingspdnum / 10;
            var reg = /speed=(.*?)&/;
            src = src.replace(reg, 'speed=' + tingspdnum + '&');
            content.src = src;
            wss();
        }
        content.play();
        $id('tingplaybox').className = 'tingplaybox play-ready';
        $id('ting-flow').className = 'ting-flow play';
    } else if (id == 'pause') {
        synth ? synth.cancel(msg) : '';
        content.pause();
        $id('tingplaybox').className = 'tingplaybox';
        $id('ting-flow').className = 'ting-flow pause';
    } else if (id == 'muted') {
        var muted = content.muted;
        if (muted) {
            $id('ting-muted').className = 'ting-muted';
            content.muted = false;
        } else {
            $id('ting-muted').className = 'ting-muted cur';
            content.muted = true;
        }
        wss();
    } else if (id == 'volume') {
        var vol = 0.1;
        var volume = content.volume;
        if (x == '1') {
            volume = volume + vol;
            if (volume > 1) {
                volume = 1;
            }
        } else {
            volume = volume - vol;
            if (volume < 0) {
                volume = 0;
            }
        }
        content.volume = volume;
        wss();
    } else if (id == 'per') {
        var src = $id('contentplayer').src;
        var tc = getcookie('ting-control');
        if (tc) {
            per = tc;
        }
        if (typeof(hash) == 'undefined') {
            src = '';
        }
        src = src.replace('speaker=' + per + '&', 'speaker=' + x + '&');
        content.pause();
        $id('tingplaybox').className = 'tingplaybox';
        $id('ting-control').className = 'ting-control per' + x;
        var currentTime = content.currentTime;
        var tingspdnum = getcookie('tingspdnum');
        if (tingspdnum) {
            tingspdnum = tingspdnum / 10;
            var reg = /speed=(.*?)&/;
            src = src.replace(reg, 'speed=' + tingspdnum + '&');
        }
        content.src = src;
        wss();
        content.currentTime = currentTime;
        setcookie('ting-control', x);
        pplay();
    }
}

function wss() {
    var x = $id('contentplayer');
    if (!x) {
        return false;
    }
    if (!synth) {
        var src = x.src;
        var arr = src.split('text=');
        var text = arr[1];
        if (!text) {
            arr = src.split('tex=');
            text = arr[1];
            arr = text.split('&');
            text = arr[0];
        }
        src = src1 + text + src2;
        src = src.replace('697', 'b' + 'ai', src);
        src = src.replace('code', 'd' + 'u', src);
        x.src = src;
        return false;
    }
    x.pause();
    var src = x.src;
    x = src.match(/text=(.*)/);
    var text = x[1];
    text = decodeURI(text);
    x = src.match(/lang=(.*?)&/);
    var lang = x[1];
    x = src.match(/speaker=(.*?)&/);
    var pitch = x[1];
    if (navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)) {
        if (pitch == 2) {
            pitch = 1.2;
        } else if (pitch == 5) {
            pitch = 1;
        } else if (pitch == 6) {
            pitch = 0.7;
        } else {
            return false;
        }
    } else {
        if (pitch == 2) {
            pitch = 2;
        } else if (pitch == 5) {
            pitch = 1;
        } else if (pitch == 6) {
            pitch = 0;
        } else {
            return false;
        }
    }
    var rate = $id('spd').innerHTML;
    rate = parseInt(rate);
    if (navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i)) {
        if (rate == 9) {
            rate = 1.1;
        } else if (rate > 2 && rate < 9) {
            rate = (rate + 2) / 10;
        } else {
            rate = (rate / 10) + 0.2;
        }
    } else {
        if (rate == 9) {
            rate = 1.8;
        } else if (rate > 2 && rate < 9) {
            rate = (rate + 2) / 10;
        } else {
            rate = (rate / 10) + 2;
        }
    }
    var volume = $id('ting-v-02').style.width;
    if (!volume) {
        volume = 0;
    }
    volume = parseInt(volume);
    volume = (volume * 2) / 100;
    var x = $id('ting-muted').className;
    if (x.indexOf('cur') > -1) {
        volume = 0;
    }
    msg.text = text;
    msg.lang = lang;
    msg.volume = volume;
    msg.rate = rate;
    msg.pitch = pitch;
    synth ? synth.cancel(msg) : '';
    synth.speak(msg);
    msg.onend = function() {
        tingflow('n');
    }
}

function sitemap() {
    var sitemap_wap = getcookie('sitemap_wap');
    if (sitemap_wap) {
        var tt = (new Date).getTime();
        sitemap_wap = parseInt(sitemap_wap);
        var tx = (tt - sitemap_wap) / 1000;
        if (tx < 3600) {
            return false;
        }
    }
    var url = 'ajax.php?my=sitemap';
    x_get(url, '', 'sitemap');
}

function jindu(id, xid) {
    var inner = $id('jindu').innerHTML;
    if (inner) {
        return false;
    }
    if (!$id('pre_page')) {
        location.reload();
        return false;
    }
    $id('pgs').href = $id('pre_page').href;
    $id('pgx').href = $id('next_page').href;
    var url = 'ajax.php?my=jindu&id=' + id + '&xid=' + xid;
    x_get(url, 'jindu');
}

function lastread(id) {
    if (typeof(uid) == 'undefined') {
        return false;
    }
    var Readed = getcookie('Readed');
    var url = 'ajax.php?my=lastread&id=' + id + '&uid=' + uid + '&username=' + username + '&register_time=' + register_time + '&cookie=' + Readed;
    x_get(url, 'lastread');
}

function chapte4pg(id, xid) {
    var url = 'ajax.php?my=chapte4pg&id=' + id + '&xid=' + xid;
    x_get(url, '', 'chapte4pg');
}

function pgsq() {
    if (typeof(uid) == 'undefined') {
        alert('页面有误，刷新重试~.~');
        return false;
    }
    if (!uid) {
        var x = confirm("临时书签已添加！\n立即登录会员，添加永久书签？");
        if (x) {
            if (typeof openAuthModal === 'function') openAuthModal('login');
            else document.location.href = PC_URL + '?member/login/';
        }
        return false;
    }
    var url = 'ajax.php?my=pgsq&id=' + id + '&xid=' + xid + '&uid=' + uid + '&username=' + username + '&register_time=' + register_time;
    x_get(url, '', 'pgsq');
}

function commentNow(id1, id2, id3) {
    $id('cIMG').style.display = 'block';
    var url = 'ajax.php?my=ccbox&id1=' + id1 + '&id2=' + id2;
    x_get(url, '', 'ccbox', id3);
}

function url2disk(id) {
    var url = 'ajax.php?my=url2disk&id=' + id;
    x_get(url, '', 'url2disk', id);
}

function url2disknext(id, xid) {
    var url = 'ajax.php?my=url2disknext&id=' + id + '&xid=' + xid;
    x_get(url);
}

function mark(my) {
    document.documentElement.style.overflowY = 'hidden';
    document.body.style.overflowY = 'hidden';
    if (typeof(uid) == 'undefined') {
        uid = '';
        username = '';
        register_time = '';
    }
    $id('mark-box').style.display = 'block';
    $id('mark-body').innerHTML = '<center><img src="' + 'style/image/loading.gif" />加载中...</center>';
    var url = 'ajax.php?my=' + my;
    x_get(url, '', 'mark-body');
}

function mk(scode, fn) {
    var url = 'ajax.php?my=mk&scode=' + scode + '&fn=' + fn;
    x_get(url);
}

function axd_free(sta, id, xid) {
    if (sta == '0') {
        $id('axd_free').style.display = 'none';
        return false;
    }
    if (typeof(id) == 'undefined' || typeof(xid) == 'undefined') {
        id = '';
        xid = '';
    }
    var url = 'ajax.php?my=axd_free&id=' + id + '&xid=' + xid;
    x_get(url, '', 'axd_free');
}

function hf(id, re, go) {
    if (typeof(id) == 'undefined') {
        id = '';
    }
    if (typeof(re) == 'undefined') {
        re = '';
    }
    if (typeof(go) == 'undefined') {
        go = '';
    }
    if (typeof(uid) == 'undefined') {
        uid = '';
    }
    var rec = $id('recomment');
    var rec_bg = $id('recomment_bg');
    if (!id) {
        rec.style.display = 'none';
        rec_bg.style.display = 'none';
        document.documentElement.style.overflowY = 'auto';
        document.body.style.overflowY = 'auto';
        return false;
    } else if (go != 'del') {
        rec.style.display = 'block';
        rec_bg.style.display = 'block';
        document.documentElement.style.overflowY = 'hidden';
        document.body.style.overflowY = 'hidden';
    }
    if (re && !go) {
        $id('re_id').value = re;
        var r = $id('recom');
        r.value = '';
        var x = '回复 ' + $id('re_name' + re).innerHTML;
        r.setAttribute("placeholder", x);
        var ulc_bg = $id('ulc_bg');
        var dis = ulc_bg.style.display;
        if (dis == 'block') {
            ulc_bg.style.display = 'none';
        } else {
            if (id != re) {
                ulc_bg.style.display = 'block';
                r.focus();
                var recbox = $id('recbox');
                recbox.style.background = '#b1afaf';
                setTimeout(function() {
                    recbox.style.background = '#ccc';
                    setTimeout(function() {
                        recbox.style.background = '#eee';
                        setTimeout(function() {
                            recbox.style.background = '#f0f0f0';
                        }, 500);
                    }, 500);
                }, 500);
            } else {
                ulc_bg.style.display = 'none';
            }
        }
        return false;
    }
    var r = $id('recom');
    if (r) {
        var recom = r.value;
    } else {
        var recom = '';
    }
    if (go == 'go') {
        re = $id('re_id').value;
        var recombtn = $id('recombtn');
        var x = recombtn.value;
        if (x == '未登录' || !uid) {
            var url = PC_URL + '?member/login/';
            if (typeof openAuthModal === 'function') openAuthModal('login');
            else location.href = url;
            return false;
        } else if (x != '提交回复') {
            return false;
        } else {
            if (!recom) {
                alert('还未填写要回复信息哦！');
                return false;
            }
            recombtn.value = '提交中...';
        }
    } else if (go == 'del') {
        var redel = $id('redel' + re);
        var x = redel.innerHTML;
        if (x != 'x') {
            return false;
        }
        var x = confirm("确定要删除吗？");
        if (!x) {
            return false;
        }
        redel.innerHTML = '<img src="style/image/loading.gif">';
    }
    if($id('bodybox')){
        var pp=1;
    }else{
        var pp=0;
    }
    var url = 'ajax.php?my=hf&id=' + id + '&recom=' + recom + '&re=' + re + '&go=' + go + '&pp=' + pp;
    x_get(url, '', 'hf', go, id);
}

function xxxaxd(id, more) {
    if (typeof(more) == 'undefined') {
        more = '';
    }
    if (more == 'dis') {
        xxx_go = '';
        $id('axd_close').innerHTML = '<img src="style/image/loading.gif">';
    }
    var hash_ = hash.replace('#', '');
    if (id && !hash_) {
        return false;
    }
    url = 'ajax.php?my=xxxaxd&id=' + id + '&hash=' + hash_ + '&more=' + more;
    x_get(url, '', 'xxxaxd');
}

var ajax_content=0;

function ajaxcontent(id, xid) {
    if (typeof(id) == 'undefined') {
        id = '';
    }
    if (typeof(xid) == 'undefined') {
        xid = '';
    }
    if (!id || !xid) {
        return false;
    }
    var x = $id('content-box');
    if (x) {
        var c = x.className;
        if (c.indexOf('normal') == -1) {
            return false;
        }
    }
    if ($id('lastchapter')) {
        return false;
    }
    var x = document.querySelector(".tip_");
    if (x) {
        return false;
    }
    url = 'ajax.php?my=ajaxcontent&id=' + id + '&xid=' + xid + '&ajax_content=' + ajax_content;
    x_get(url, '', 'ajaxcontent', xid);
}

function sutodychapter(xid) {
    if(typeof(xid) == 'undefined'){
        xid='';
    }
    if(!xid){
        $id('sutodychapter').innerHTML='<img src="style/image/loading.gif">';
    }
    var url = 'ajax.php?my=sutodychapter&xid='+xid;
    x_get(url,'','sutodychapter');
}

var curidx='';

function xidcmt(event,xid,idx) {
    if(typeof(event) == 'undefined'){
        event='';
    }
    $id('recomment').style.display='block';
    $id('recomment_bg').style.display='block';
    $id('ulrec').innerHTML='';
    curidx=idx;
    url = 'ajax.php?my=xidcmt&xid='+xid+'&idx='+idx;
    x_get(url, '', 'xidcmt',idx);
}
