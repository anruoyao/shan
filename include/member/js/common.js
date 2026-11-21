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

function checkyc() {
    var yt = $id('yctitle');
    var ytv = yt.value;
    ytv = ytv.trim();
    var ys = $id('ycscode');
    var ysv = ys.value;
    ysv = ysv.trim();
    if (!ytv) {
        yt.focus();
        window.scrollTo(0, 0);
        alert('请输入标题！');
        return false;
    } else if (!ysv || ysv == '0') {
        ys.focus();
        window.scrollTo(0, 0);
        alert('请选择栏目！')
        return false;
    }
}

function sectime(url) {
    var st = $id('st');
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

function tipx(x) {
    if (x == 1) {
        x = '确定要删除吗？';
    } else if (x == 2) {
        x = '确定要清空所有数据吗？';
    } else if (x == 3) {
        x = '确定要取消订单吗？';
    } else if (x == 4) {
        x = '确定用积分订阅吗？';
    } else if (x == 5) {
        x = '确定用金币订阅吗？';
    } else if (x == 6) {
        x = '确定要兑换吗？';
    } else if (x == 7) {
        x = '确定要申请签约吗？';
    } else if (x == 8) {
        x = '确定要放弃签约吗？';
    } else if (x == 9) {
        x = '确定要提现吗？';
    } else {
        x = '确定要退出会员登录状态吗？';
    }
    var xxx = event.returnValue = confirm(x);
    return xxx;
}

function imgsrc(img) {
    for (var i of img) {
        if (i.isIntersecting) {
            var x = i.target;
            var crs = x.getAttribute('crs');
            if (crs) {
                x.setAttribute('src', crs);
            }
            observer.unobserve(x);
        }
    }
}

function ycimgx(x) {
    var file = x.files[0];
    if (!/image\/\w+/.test(file.type)) {
        alert('请上传一张图片~');
        return false;
    }
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(e) {
        $id('ycico2').value = x.value;
        $id('ycimg').src = this.result;
    }
}

function selectall(my) {
    for (var i = 1; i < 50000; i++) {
        var ck = $id("ck" + i);
        if (!ck) {
            break;
        }
        var c = ck.checked;
        if (my == 'fan') {
            if (c) {
                ck.checked = '';
            } else {
                ck.checked = 'checked';
            }
        } else if (my == 'yclist') {
            var value = '';
            for (var i = 1; i < 50000; i++) {
                var ck = $id("ck" + i);
                if (!ck) {
                    break;
                }
                var c = ck.checked;
                if (c) {
                    var v = ck.value;
                    value += v + ",";
                }
            }
            value = value.substring(0, value.length - 1);
            if (!value) {
                alert('请勾选需要删除的章节！');
                return false;
            }
            var href = document.location.href;
            href = href.replace('chapter', 'del');
            var url = href + value + '/';
            document.location.href = url;
        } else {
            ck.checked = 'checked';
        }
    }
}

function cke(x) {
    var re = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/
    if (re.test(x)) {
        var xxx = 1;
    } else {
        var xxx = 0;
    }
    return xxx;
}

function ckp(x) {
    var re = /^1\d{10}$/
    if (re.test(x)) {
        var xxx = 1;
    } else {
        var xxx = 0;
    }
    return xxx;
}

function trim(x) {
    var xxx = x.replace(/(^\s*)|(\s*$)/g, '');
    return xxx;
}

function chpay(x1, x2) {
    if (x1 == 1) {
        var paylx = $id('paylx');
        paylx.className = 'paylx lx' + x2;
        paylx.setAttribute('lx', x2);
    } else if (x1 == 2) {
        var payje = $id('payje_');
        payje.className = 'payje';
        payje.setAttribute('je', '');
        payje.setAttribute('jb', '');
        var payje = $id('payje');
        payje.className = 'payje je' + x2;
        if (x2 == 6) {
            var om = $id('om');
            om.focus();
            var x = om.value;
            if (!x) {
                x = 0;
            }
            payje.setAttribute('je', x);
            x = x * rmb2jinbi;
            payje.setAttribute('jb', x);
        } else {
            var x = payje.getElementsByTagName('li');
            x = x[x2 - 1].innerHTML;
            inner = x.replace('￥', '');
            x = inner.split('元');
            var je = x[0];
            je = trim(je);
            if (je != '') {
                je = parseFloat(je);
            } else {
                je = 0
            }
            x = inner.split('(');
            x = x[1]
            x = x.split('金');
            var jb = x[0];
            jb = trim(jb);
            if (jb != '') {
                jb = parseFloat(jb);
            } else {
                jb = 0
            }
            payje.setAttribute('je', je);
            payje.setAttribute('jb', jb);
        }
    } else if (x1 == 3) {
        var payje = $id('payje');
        payje.className = 'payje';
        payje.setAttribute('je', '');
        payje.setAttribute('jb', '');
        var payje = $id('payje_');
        payje.className = 'payje je' + x2;
        var x = payje.getElementsByTagName('li');
        x = x[x2 - 1].innerHTML;
        inner = x.replace('￥', '');
        x = inner.split('元');
        var je = x[0];
        je = trim(je);
        if (je != '') {
            // je = parseFloat(je);
        } else {
            je = 0
        }
        payje.setAttribute('je', je);
        payje.setAttribute('jb', '697' + x2);
    }
}

function chje() {
    var payje = $id('payje');
    var om = $id('om');
    var jb = $id('jb');
    var je = om.value;
    je = trim(je);
    je = je.replace(/[^\d.]/g, ""); //清除“数字”和“.”以外的字符
    je = je.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
    je = je.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
    je = je.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3'); //只能输入两个小数
    if (je.indexOf(".") < 0 && je != "") { //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
        je = parseFloat(je);
    }
    if (je < 1 || je == '.') {
        je = 1;
        om.value = je;
    }
    if (je) {
        var x = je * rmb2jinbi;
        x = parseInt(x);
        jb.innerHTML = x;
        payje.setAttribute('jb', x);
        om.value = je;
        payje.setAttribute('je', je);
    } else {
        if (je != '') {
            alert('Please enter a positive integer amount!');
        }
        om.value = '';
        om.focus();
        jb.innerHTML = 0;
        payje.setAttribute('je', 0);
        payje.setAttribute('jb', 0);
    }
}

function chpay_(xx) {
    var payje = $id('payje');
    payje.className = 'payje je' + xx;
    if (xx == 2) {
        var om = $id('om');
        om.focus();
        var x = om.value;
        if (!x) {
            x = 0;
        }
        payje.setAttribute('jb', x);
        x = x * jinbi2jifen;
        payje.setAttribute('jf', x);
    } else {
        var x = payje.getElementsByTagName('li');
        x = x[xx - 1].innerHTML;
        x = x.replace('金币：', '');
        x = x.replace('积分', '');
        var inner = x.replace(')', '');
        x = inner.split('(');
        var jb = x[0];
        var jf = x[1];
        jb = trim(jb);
        if (jb != '') {
            jb = parseFloat(jb);
        } else {
            jb = 0
        }
        jf = trim(jf);
        if (jf != '') {
            jf = parseFloat(jf);
        } else {
            jf = 0
        }
        payje.setAttribute('jb', jb);
        payje.setAttribute('jf', jf);
    }
}

function chje_() {
    var payje = $id('payje');
    var om = $id('om');
    var jf = $id('jf');
    var jb = om.value;
    jb = trim(jb);
    if (jb != '') {
        jb = parseFloat(jb);
    }
    var rep = /^\d+$/;
    if (jb != '0' && rep.test(jb)) {
        var max = $id('max').innerHTML;
        max = parseFloat(max);
        if (jb > max) {
            jb = max;
        }
        var x = jb * jinbi2jifen;
        jf.innerHTML = x;
        payje.setAttribute('jf', x);
        om.value = jb;
        payje.setAttribute('jb', jb);
    } else {
        if (jb != '') {
            alert('请输入正整数的金币量！');
        }
        om.value = '';
        om.focus();
        jf.innerHTML = 0;
        payje.setAttribute('jb', 0);
        payje.setAttribute('jf', 0);
    }
}

function chtx_(xx) {
    var payje = $id('payje');
    payje.className = 'payje je' + xx;
    if (xx == 2) {
        var om = $id('om');
        om.focus();
        var x = om.value;
        if (!x) {
            x = 0;
        }
        payje.setAttribute('jb', x);
        x = x / rmb2jinbi;
        payje.setAttribute('je', x);
    } else {
        var x = payje.getElementsByTagName('li');
        x = x[xx - 1].innerHTML;
        x = x.replace('金币：', '');
        x = x.replace('￥', '');
        var inner = x.replace(')', '');
        x = inner.split('(');
        var jb = x[0];
        var je = x[1];
        jb = trim(jb);
        if (jb != '') {
            jb = parseFloat(jb);
        } else {
            jb = 0
        }
        je = trim(je);
        if (je != '') {
            je = parseFloat(je);
        } else {
            je = 0
        }
        payje.setAttribute('jb', jb);
        payje.setAttribute('je', je);
    }
}

function chje__(up) {
    var payje = $id('payje');
    var om = $id('om');
    var je = $id('je');
    var jb = om.value;
    jb = trim(jb);
    if (jb != '') {
        jb = parseFloat(jb);
    }
    var rep = /^\d+$/;
    if (jb != '0' && rep.test(jb)) {
        var max = $id('max').innerHTML;
        max = parseFloat(max);
        if (jb > max) {
            jb = max;
        }
        var x = jb / rmb2jinbi;
        if (up == '1' && x < tx2rmb) {
            x = tx2rmb;
            jb = x * rmb2jinbi;
            if (jb > max) {
                jb = '';
                x = 0;
            }
        }
        var sxf = x * tx2sxf;
        sxf = Math.ceil(sxf);
        x = x - sxf;
        if (x < (tx2rmb - sxf)) {
            x = 0;
        }
        if (x < 0) {
            x = 0;
        }
        je.innerHTML = x;
        payje.setAttribute('je', x);
        om.value = jb;
        payje.setAttribute('jb', jb);
    } else {
        if (jb != '') {
            alert('请输入正整数的金币量！');
        }
        om.value = '';
        if (up != 1) {
            om.focus();
        }
        je.innerHTML = 0;
        payje.setAttribute('jb', 0);
        payje.setAttribute('je', 0);
    }
}

function chpayxx(id) {
    if (id != 'undefined') {
        $id('pay0' + id).focus();
    }
    if (id == 1 || id == 2) {
        var pay01 = $id('pay01');
        var v01 = pay01.value;
        v01 = v01.replace(/\s*/g, '');
        if (v01.indexOf('*') > -1) {
            v01 = '';
        }
        pay01.value = v01;
        var pay02 = $id('pay02');
        var v02 = pay02.value;
        v02 = v02.replace(/\s*/g, '');
        if (v02.indexOf('*') > -1) {
            v02 = '';
        }
        pay02.value = v02;
    } else if (id == 3 || id == 4) {
        var payxx = $id('pay0' + id);
        var vxx = payxx.value;
        vxx = vxx.replace(/\s*/g, '');
        payxx.value = vxx;
    }
}

document.onkeydown = function(e) {
    var theEvent = window.event || e;
    var keycode = theEvent.keyCode || theEvent.which;
    if (keycode == '13') {
        var curIDxx = event.srcElement.id;
        if (curIDxx == 'om' || curIDxx == 'pay01' || curIDxx == 'pay02' || curIDxx == 'pay03' || curIDxx == 'pay04') {
            pay();
        }
    }
}

function cplink(id) {
    var c = $id('copycontent');
    var link = $id('link' + id).href;
    c.innerHTML = '<textarea readonly="readonly" id="copytext">' + link + '</textarea>';
    x = $id('copytext');
    x.select();
    x = document.execCommand("Copy");
    if (x) {
        var btn=$id('btn'+id);
        btn.innerHTML='复制成功';
        setTimeout(function() {
            btn.innerHTML='复制';
        }, 500);
    } else {
        alert('数据有误，稍后重试...');
    }
    c.innerHTML = '';
}