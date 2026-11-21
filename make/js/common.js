function $id(id) { return document.getElementById(id); }

function setcookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else var expires = "";
    document.cookie = name + "=" + escape(value) + expires + "; path=/";
}

function getcookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function closeMe(id) {
    var obj = document.getElementById(id);
    var className = obj.className;
    if (className == id) {
        obj.className = id + " cur";
        if (id == 'hid-box') {
            $id('more').innerHTML = '收起详情';
            $id('more-2').innerHTML = '收起详情';
            $id('tt-con').innerHTML = $id('t-con').innerHTML;
        }
    } else {
        obj.className = id;
        if (id == 'hid-box') {
            $id('more').innerHTML = '展开详情';
            $id('more-2').innerHTML = '展开详情';
        }
    }
}

function goBox(page, total, num) {
    var pageAll = Math.ceil(total / num);
    if (page > pageAll) {
        page = pageAll;
    }
    if (pageAll == 0 || !pageAll || typeof(pageAll) == 'undefined') {
        page = 1;
        pageAll = 1;
    }
    var x = page / pageAll;
    var w = $id('xw-box').offsetWidth;
    var xw = x * w;
    if (x == 1) {
        var ok = '<ok>ok</ok>';
    } else {
        var ok = '';
    }
    x = (x * 100);
    x = x.toFixed(2);
    $id('xw-box').innerHTML = ok + '<sss id="xw">' + x + '%&nbsp;&nbsp;</sss>';
    $id('xw').style.width = xw + 'px';
}

var tt = '';

function time() {
    setTimeout(function() {
        var t = $id('time').innerHTML;
        t = parseInt(t);
        if (t > 600) {
            var href = window.location.href;
            var arr = href.split('?');
            href = arr[0];
            var c01 = $id('jieqi2pbcode').checked;
            var c02 = $id('cj2local').checked;
            if (c01) {
                var x = '#jie';
            } else if (c02) {
                var x = '#cj';
            } else {
                var x = '';
            }
            if (x) {
                window.location.href = href + '?' + (new Date).getTime() + x;
            }
        }
        $id('time').innerHTML = t + 1;
        if (tt == '1') {
            time();
        }
    }, 1000);
}

function xtime() {
    var jt = $id('xtime');
    if (!jt) {
        return false;
    }
    var t = jt.innerHTML;
    t = t - 1;
    jt.innerHTML = t;
    if (t > 0) {
        setTimeout(function() {
            xtime();
        }, 1000);
    } else {
        gox();
    }
}

function gox() {
    var hash = window.location.hash;
    if (hash == '#jie') {
        $id('jieqi2pbcode').checked = 'checked';
    } else if (hash == '#cj') {
        $id('cj2local').checked = 'checked';
    } else {
        return false;
    }
    go(1);
}

function ocseo(oc, id) {
    if (!id || typeof(id) == 'undefined') {
        for (var i = 1; i < 10000; i++) {
            var x = 'box' + i;
            x = $id(x);
            if (!x) {
                break;
            }
            if (oc == 1) {
                x.className = 'box o';
            } else {
                x.className = 'box';
            }

        }
    } else {
        for (var i = 1; i < 10000; i++) {
            var x = 'box' + i;
            x = $id(x);
            if (!x) {
                break;
            }
            if (oc == 1) {
                x.className = 'box';
            } else {
                x.className = 'box o';
            }

        }
        if (oc == 1) {
            $id('box' + id).className = 'box o';
        } else {
            $id('box' + id).className = 'box';
        }

    }
}