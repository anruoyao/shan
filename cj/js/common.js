function $id(id) { return document.getElementById(id); }

var tt = 0;

function time() {
    if (tt == 30) {
        return false;
    }
    setTimeout(function() {
        if (!tt) {
            var href = document.location.href;
            var arr = href.split('?');
            href = arr[0];
            document.location.href = href + '?' + (new Date).getTime();
        } else {
            tt = tt - 1;
            $id('time').innerHTML = tt;
            time();
        }
    }, 1000);
}