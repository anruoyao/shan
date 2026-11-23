(function () {
    var track = document.querySelector('.sh-wap-banner__track');
    if (track) {
        var slides = track.querySelectorAll('a');
        var dots = document.querySelectorAll('.sh-wap-banner__dots button');
        var frame = 0;
        var timer = null;
        var resumeTimer = null;
        var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function slideTarget(index) {
            var trackRect = track.getBoundingClientRect();
            var slideRect = slides[index].getBoundingClientRect();
            var paddingLeft = parseFloat(window.getComputedStyle(track).paddingLeft) || 0;
            return Math.max(0, track.scrollLeft + slideRect.left - trackRect.left - track.clientLeft - paddingLeft);
        }

        function activeIndex() {
            var trackRect = track.getBoundingClientRect();
            var paddingLeft = parseFloat(window.getComputedStyle(track).paddingLeft) || 0;
            var start = trackRect.left + track.clientLeft + paddingLeft;
            var current = 0;
            var distance = Infinity;
            var i;
            for (i = 0; i < slides.length; i++) {
                var nextDistance = Math.abs(slides[i].getBoundingClientRect().left - start);
                if (nextDistance < distance) {
                    current = i;
                    distance = nextDistance;
                }
            }
            return current;
        }

        function updateDots() {
            var current = activeIndex();
            var i;
            for (i = 0; i < dots.length; i++) {
                dots[i].setAttribute('aria-current', i === current ? 'true' : 'false');
            }
        }

        function onScroll() {
            if (frame) {
                return;
            }
            frame = window.requestAnimationFrame(function () {
                frame = 0;
                updateDots();
            });
        }

        function stopAutoplay() {
            window.clearInterval(timer);
            window.clearTimeout(resumeTimer);
            timer = null;
            resumeTimer = null;
        }

        function startAutoplay() {
            stopAutoplay();
            if (reducedMotion || slides.length < 2 || document.hidden) {
                return;
            }
            timer = window.setInterval(function () {
                var next = (activeIndex() + 1) % slides.length;
                track.scrollTo({ left: slideTarget(next), behavior: 'smooth' });
            }, 4500);
        }

        track.addEventListener('scroll', onScroll, { passive: true });
        track.addEventListener('pointerdown', stopAutoplay, { passive: true });
        track.addEventListener('touchstart', stopAutoplay, { passive: true });
        track.addEventListener('pointerup', function () {
            resumeTimer = window.setTimeout(startAutoplay, 5000);
        }, { passive: true });
        track.addEventListener('touchend', function () {
            resumeTimer = window.setTimeout(startAutoplay, 5000);
        }, { passive: true });
        window.addEventListener('resize', updateDots);
        document.addEventListener('visibilitychange', startAutoplay);

        Array.prototype.forEach.call(dots, function (dot, index) {
            dot.addEventListener('click', function () {
                stopAutoplay();
                track.scrollTo({ left: slideTarget(index), behavior: reducedMotion ? 'auto' : 'smooth' });
                resumeTimer = window.setTimeout(startAutoplay, 5000);
            });
        });

        updateDots();
        startAutoplay();
    }

    var feature = document.getElementById('shWapFeature');
    var refreshButton = document.getElementById('shWapFeatureRefresh');
    if (!feature || !refreshButton || !window.fetch) {
        return;
    }

    refreshButton.addEventListener('click', function () {
        if (refreshButton.disabled) {
            return;
        }

        var grid = document.getElementById('shWapFeatureGrid');
        var endpoint = 'ajax.php?my=home_feature&sex=' + encodeURIComponent(feature.getAttribute('data-sex') || '') +
            '&offset=' + encodeURIComponent(feature.getAttribute('data-offset') || '0');

        refreshButton.disabled = true;
        refreshButton.classList.add('is-loading');
        grid.classList.add('is-refreshing');

        window.fetch(endpoint, {
            credentials: 'same-origin',
            headers: { 'Accept': 'text/html' }
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Unable to load recommendations');
            }
            return response.text();
        }).then(function (html) {
            var parsed = new DOMParser().parseFromString(html, 'text/html');
            var nextFeature = parsed.getElementById('shWapFeature');
            var nextGrid = parsed.getElementById('shWapFeatureGrid');
            if (!nextFeature || !nextGrid) {
                throw new Error('Invalid recommendation response');
            }
            grid.innerHTML = nextGrid.innerHTML;
            feature.setAttribute('data-offset', nextFeature.getAttribute('data-offset') || '0');
        }).catch(function () {
            refreshButton.title = '加载失败，请重试';
        }).then(function () {
            grid.classList.remove('is-refreshing');
            refreshButton.classList.remove('is-loading');
            refreshButton.disabled = false;
        });
    });
}());
