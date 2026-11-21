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
            var d = $id('go');
            var r = $id('rebox');
            if (cDATA) {
                $id(OBJ) ? $id(OBJ).innerHTML = cDATA : '';
                if (XID == 'cj') {
                    tt = 180;
                    time();
                    go();
                }
                if (XID == 'go') {
                    var inner = d.innerHTML;
                    d.innerHTML = inner + cDATA;
                    d.scrollTop = 669977;
                    if (cDATA.indexOf('norule') > -1) {
                        r.style.display = 'none';
                        tt = 5;
                    } else if (cDATA.indexOf('nodata') > -1) {
                        r.style.display = 'none';
                        tt = 5;
                    } else {
                        if (tt) {
                            tt = 180;
                            if (cjxxx) {
                                var x = cjxxx * 1000;
                                setTimeout(function() {
                                    go();
                                }, x);
                            } else {
                                go();
                            }
                        }
                    }
                }
            } else {
                if (XID == 'go') {
                    d.innerHTML = '<p>未知错误，稍后重试...</p>';
                    r.style.display = 'none';
                    tt = 5;
                }
            }
        }
    }
    xmlHttp.open('GET', URL00, true);
    xmlHttp.send(null);
}

function cj() {
    var url = 'cj/ajax.php?my=cj';
    x_get(url, 'cj', 'cj');
}

function go() {
    var url = 'cj/ajax.php?my=go';
    x_get(url, '', 'go');
}