/* 山海书盟 · 读者端交互 */
(function () {
    'use strict';

    function $(id) {
        return document.getElementById(id);
    }

    function on(el, evt, fn) {
        if (el) {
            el.addEventListener(evt, fn, false);
        }
    }

    /* ---------- 移动端导航 ---------- */

    var menuBtn = $('shMenuBtn');
    var mobileNav = $('shMobileNav');
    on(menuBtn, 'click', function () {
        if (mobileNav) {
            var open = mobileNav.classList.toggle('is-open');
            menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    });

    /* 首页横幅：支持手动切换，聚焦或悬停时暂停自动播放。 */
    var homeCarousel = document.querySelector('[data-sh-home-carousel]');
    if (homeCarousel) {
        var homeSlides = homeCarousel.querySelectorAll('[data-sh-home-slide]');
        var homeDots = homeCarousel.querySelectorAll('[data-sh-home-go]');
        var homeIndex = 0;
        var homeTimer = null;
        var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function showHomeSlide(index) {
            homeIndex = (index + homeSlides.length) % homeSlides.length;
            for (var i = 0; i < homeSlides.length; i++) {
                homeSlides[i].hidden = i !== homeIndex;
                if (i === homeIndex) {
                    homeSlides[i].setAttribute('aria-current', 'true');
                } else {
                    homeSlides[i].removeAttribute('aria-current');
                }
            }
            for (var j = 0; j < homeDots.length; j++) {
                homeDots[j].setAttribute('aria-pressed', j === homeIndex ? 'true' : 'false');
            }
        }

        function stopHomeTimer() {
            if (homeTimer) {
                window.clearInterval(homeTimer);
                homeTimer = null;
            }
        }

        function startHomeTimer() {
            stopHomeTimer();
            if (!reducedMotion && homeSlides.length > 1 && document.visibilityState !== 'hidden'
                && !homeCarousel.matches(':hover') && !homeCarousel.contains(document.activeElement)) {
                homeTimer = window.setInterval(function () {
                    showHomeSlide(homeIndex + 1);
                }, 7000);
            }
        }

        on(homeCarousel.querySelector('[data-sh-home-prev]'), 'click', function () {
            showHomeSlide(homeIndex - 1);
            startHomeTimer();
        });
        on(homeCarousel.querySelector('[data-sh-home-next]'), 'click', function () {
            showHomeSlide(homeIndex + 1);
            startHomeTimer();
        });
        for (var d = 0; d < homeDots.length; d++) {
            on(homeDots[d], 'click', function () {
                showHomeSlide(Number(this.getAttribute('data-sh-home-go')));
                startHomeTimer();
            });
        }
        on(homeCarousel, 'mouseenter', stopHomeTimer);
        on(homeCarousel, 'mouseleave', startHomeTimer);
        on(homeCarousel, 'focusin', stopHomeTimer);
        on(homeCarousel, 'focusout', function (event) {
            if (!homeCarousel.contains(event.relatedTarget)) {
                startHomeTimer();
            }
        });
        on(document, 'visibilitychange', function () {
            if (document.visibilityState === 'hidden') {
                stopHomeTimer();
            } else {
                startHomeTimer();
            }
        });
        showHomeSlide(0);
        startHomeTimer();
    }

    /* 首页推荐：由读者选择封面切换。 */
    var feature = document.querySelector('[data-sh-feature]');
    if (feature) {
        var items = feature.querySelectorAll('[data-sh-feature-item]');
        var picks = feature.querySelectorAll('[data-sh-feature-pick]');
        var pickBar = feature.querySelector('.sh-feature__picks');
        if (pickBar) {
            pickBar.hidden = false;
        }
        feature.addEventListener('click', function (event) {
            var pick = event.target.closest('[data-sh-feature-pick]');
            if (!pick) {
                return;
            }
            var index = Number(pick.getAttribute('data-sh-feature-pick'));
            for (var i = 0; i < items.length; i++) {
                items[i].hidden = i !== index;
                picks[i].setAttribute('aria-pressed', i === index ? 'true' : 'false');
            }
        });
    }

    /* 目录：保留初始顺序，切换排序与搜索时重新计算可见章节。 */
    var chapters = $('shChapters');
    var chapterSearch = $('shChapterSearch');
    var chapterMore = $('shChapterMore');
    var chapterActions = $('shChapterActions');
    var chapterEmpty = $('shChapterEmpty');
    var orderBtns = document.querySelectorAll('[data-chapter-order]');
    if (chapters && chapterSearch && chapterMore && chapterActions && chapterEmpty) {
        var original = Array.prototype.slice.call(chapters.querySelectorAll('.sh-chapter'));
        var expanded = false;
        var limit = 30;

        function updateCatalog() {
            var key = chapterSearch.value.trim().toLowerCase();
            var shown = 0;
            var list = chapters.querySelectorAll('.sh-chapter');
            for (var i = 0; i < list.length; i++) {
                var title = (list[i].getAttribute('data-title') || '').toLowerCase();
                var match = !key || title.indexOf(key) !== -1;
                list[i].hidden = !match || (!key && !expanded && shown >= limit);
                if (match) {
                    shown++;
                }
            }
            chapterEmpty.hidden = !key || shown > 0;
            chapterActions.hidden = !!key || shown <= limit;
            chapterMore.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            chapterMore.textContent = expanded ? '收起目录' : '展开全部目录';
        }

        on(chapterSearch, 'input', updateCatalog);
        for (var i = 0; i < orderBtns.length; i++) {
            on(orderBtns[i], 'click', function () {
                for (var j = 0; j < orderBtns.length; j++) {
                    var active = orderBtns[j] === this;
                    orderBtns[j].classList.toggle('is-active', active);
                    orderBtns[j].setAttribute('aria-pressed', active ? 'true' : 'false');
                }
                var order = this.getAttribute('data-chapter-order');
                var list = order === 'desc' ? original.slice().reverse() : original;
                var fragment = document.createDocumentFragment();
                for (var k = 0; k < list.length; k++) {
                    fragment.appendChild(list[k]);
                }
                chapters.appendChild(fragment);
                updateCatalog();
            });
        }
        on(chapterMore, 'click', function () {
            expanded = !expanded;
            updateCatalog();
            if (!expanded) {
                chapterSearch.focus();
            }
        });
        updateCatalog();
    }

    /* ---------- 阅读页设置 ---------- */

    var read = $('shRead');
    if (read) {
        var root = document.documentElement;
        var sizeKey = 'sh_read_size';
        var lineKey = 'sh_read_line';
        var themeKey = 'sh_read_theme';
        var size = parseFloat(localStorage.getItem(sizeKey) || '19');
        var line = parseFloat(localStorage.getItem(lineKey) || '2');
        var theme = localStorage.getItem(themeKey) || 'paper';

        function applyRead() {
            size = isFinite(size) ? Math.min(24, Math.max(15, size)) : 19;
            line = isFinite(line) ? Math.min(2.4, Math.max(1.6, line)) : 2;
            $('shLineBtn').textContent = '行距 ' + line.toFixed(1);
            read.style.setProperty('--sh-read-size', size + 'px');
            read.style.setProperty('--sh-read-line', line);
            read.classList.remove('is-sepia', 'is-night');
            document.body.classList.remove('is-night');
            if (theme === 'sepia') {
                read.classList.add('is-sepia');
            } else if (theme === 'night') {
                read.classList.add('is-night');
                document.body.classList.add('is-night');
            }
            var tBtns = document.querySelectorAll('[data-read-theme]');
            for (var i = 0; i < tBtns.length; i++) {
                var active = tBtns[i].getAttribute('data-read-theme') === theme;
                tBtns[i].classList.toggle('is-active', active);
                tBtns[i].setAttribute('aria-pressed', active ? 'true' : 'false');
            }
            if (root) {
                root.style.colorScheme = theme === 'night' ? 'dark' : 'light';
            }
        }

        on($('shFontMinus'), 'click', function () {
            size -= 1;
            applyRead();
            localStorage.setItem(sizeKey, size);
        });
        on($('shFontPlus'), 'click', function () {
            size += 1;
            applyRead();
            localStorage.setItem(sizeKey, size);
        });
        on($('shLineBtn'), 'click', function () {
            line = line >= 2.3 ? 1.7 : line + 0.15;
            applyRead();
            localStorage.setItem(lineKey, line.toFixed(2));
        });

        var themeBtns = document.querySelectorAll('[data-read-theme]');
        for (var t = 0; t < themeBtns.length; t++) {
            on(themeBtns[t], 'click', function () {
                theme = this.getAttribute('data-read-theme');
                localStorage.setItem(themeKey, theme);
                applyRead();
            });
        }

        applyRead();

        /* 阅读进度条 */
        var bar = $('shReadProgress');
        function updateProgress() {
            if (!bar) {
                return;
            }
            var h = document.documentElement.scrollHeight - window.innerHeight;
            var top = window.pageYOffset || document.documentElement.scrollTop || 0;
            var p = h > 0 ? Math.min(100, Math.max(0, (top / h) * 100)) : 0;
            bar.style.width = p + '%';
        }
        window.addEventListener('scroll', updateProgress, { passive: true });
        window.addEventListener('resize', updateProgress);
        updateProgress();
    }

    /* ---------- 搜索结果高亮 ---------- */

    var hl = document.querySelectorAll('[data-sh-key]');
    if (hl.length) {
        for (var h = 0; h < hl.length; h++) {
            var key = hl[h].getAttribute('data-sh-key');
            if (!key) {
                continue;
            }
            var label = hl[h].textContent;
            var safe = key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            var pattern = new RegExp(safe, 'gi');
            var match;
            var end = 0;
            var fragment = document.createDocumentFragment();
            while ((match = pattern.exec(label)) !== null) {
                fragment.appendChild(document.createTextNode(label.slice(end, match.index)));
                var mark = document.createElement('em');
                mark.className = 'sh-hl';
                mark.textContent = match[0];
                fragment.appendChild(mark);
                end = pattern.lastIndex;
            }
            if (end) {
                fragment.appendChild(document.createTextNode(label.slice(end)));
                hl[h].textContent = '';
                hl[h].appendChild(fragment);
            }
        }
    }
})();
