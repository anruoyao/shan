(function () {
    'use strict';
    var root = document.body;
    var input = document.getElementById('search-input');
    var form = document.getElementById('search-form');
    if (!root || !input || !form) return;
    var baseUrl = root.getAttribute('data-search-url');
    var historyEnabled = root.getAttribute('data-history-enabled') === '1';
    var historyLimit = Math.max(1, Math.min(30, parseInt(root.getAttribute('data-history-limit'), 10) || 20));
    var storageKey = 'shbook_wap_search_history';
    var historyList = document.getElementById('search-history-list');
    var historyEmpty = document.getElementById('search-history-empty');
    var clearButton = document.getElementById('search-history-clear');
    var resetButton = document.getElementById('search-reset');
    var status = document.getElementById('search-status');
    var composing = false;
    var storageAvailable = true;

    function normalize(value) {
        if (typeof value !== 'string') return '';
        return Array.from(value.replace(/[\s\x00-\x1f\x7f]+/g, ' ').trim()).slice(0, 60).join('');
    }

    function searchUrl(word) {
        return baseUrl + (baseUrl.indexOf('?') === -1 ? '?' : '&') + 'q=' + encodeURIComponent(word) + '&pg=1';
    }

    function readHistory() {
        if (!historyEnabled) return [];
        try {
            var saved = JSON.parse(window.localStorage.getItem(storageKey) || '[]');
            if (!Array.isArray(saved)) return [];
            var words = [];
            saved.forEach(function (value) {
                var word = normalize(value);
                if (word && words.indexOf(word) === -1 && words.length < historyLimit) words.push(word);
            });
            return words;
        } catch (error) {
            // 隐私模式或损坏的记录不阻断搜索。
            return [];
        }
    }

    function writeHistory(words) {
        if (!historyEnabled) return;
        try {
            window.localStorage.setItem(storageKey, JSON.stringify(words));
        } catch (error) {
            storageAvailable = false;
        }
    }

    var words = readHistory();

    function remember(word) {
        if (!historyEnabled || !word) return;
        words = [word].concat(words.filter(function (value) { return value !== word; })).slice(0, historyLimit);
        writeHistory(words);
    }

    function renderHistory() {
        if (!historyList) return;
        historyList.textContent = '';
        words.forEach(function (word) {
            var link = document.createElement('a');
            link.className = 'search-chip';
            link.href = searchUrl(word);
            link.textContent = word;
            link.setAttribute('data-search-word', word);
            historyList.appendChild(link);
        });
        historyEmpty.hidden = words.length > 0;
        clearButton.hidden = words.length === 0;
        if (!storageAvailable) status.textContent = '当前浏览器无法保存搜索历史';
    }

    function refresh() {
        words = readHistory();
        renderHistory();
        resetButton.hidden = input.value.length === 0;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (composing) return;
        var word = normalize(input.value);
        if (!word) {
            status.textContent = '请输入书名或作者';
            input.focus();
            return;
        }
        remember(word);
        window.location.assign(searchUrl(word));
    });
    input.addEventListener('compositionstart', function () { composing = true; });
    input.addEventListener('compositionend', function () { composing = false; });
    input.addEventListener('input', function () {
        resetButton.hidden = input.value.length === 0;
        status.textContent = '';
    });
    resetButton.addEventListener('click', function () {
        input.value = '';
        resetButton.hidden = true;
        if (root.getAttribute('data-search-query')) {
            window.location.assign(baseUrl);
        } else {
            input.focus();
        }
    });
    document.querySelector('.search-content').addEventListener('click', function (event) {
        var link = event.target.closest('[data-search-word]');
        if (link) remember(normalize(link.getAttribute('data-search-word')));
    });
    if (clearButton) clearButton.addEventListener('click', function () {
        words = [];
        writeHistory(words);
        renderHistory();
        status.textContent = storageAvailable ? '已清空搜索历史' : '当前浏览器无法清空已保存的历史';
    });
    var cancel = document.getElementById('search-cancel');
    try {
        var referrer = document.referrer ? new URL(document.referrer) : null;
        var sitePath = new URL(document.baseURI).pathname;
        if (referrer && referrer.origin === window.location.origin && referrer.pathname.indexOf(sitePath) === 0 && referrer.search.indexOf('pbcode/so') === -1) cancel.href = referrer.href;
    } catch (error) { /* 默认返回书城首页。 */ }
    window.addEventListener('pageshow', refresh);
    window.addEventListener('storage', function (event) {
        if (event.key === storageKey || event.key === null) {
            words = readHistory();
            renderHistory();
        }
    });
    remember(normalize(root.getAttribute('data-search-query')));
    refresh();
}());
