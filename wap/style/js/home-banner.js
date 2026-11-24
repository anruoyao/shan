(function () {
    var carousel = document.querySelector('[data-wap-carousel]');
    if (!carousel) return;

    var slides = Array.prototype.slice.call(carousel.querySelectorAll('[data-wap-slide]'));
    var dots = Array.prototype.slice.call(carousel.querySelectorAll('[data-wap-dot]'));
    if (slides.length < 2 || dots.length !== slides.length) return;

    var current = 0;
    var timer = null;
    var startX = 0;
    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach(function (slide, slideIndex) {
            var active = slideIndex === current;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', active ? 'false' : 'true');
            Array.prototype.forEach.call(slide.querySelectorAll('a'), function (link) {
                link.tabIndex = active ? 0 : -1;
            });
        });
        dots.forEach(function (dot, dotIndex) {
            dot.setAttribute('aria-selected', dotIndex === current ? 'true' : 'false');
        });
    }

    function stop() {
        if (timer) window.clearInterval(timer);
        timer = null;
    }

    function start() {
        stop();
        if (!reducedMotion && !document.hidden) {
            timer = window.setInterval(function () { show(current + 1); }, 4500);
        }
    }

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            show(index);
            start();
        });
    });

    carousel.addEventListener('mouseenter', stop);
    carousel.addEventListener('mouseleave', start);
    carousel.addEventListener('focusin', stop);
    carousel.addEventListener('focusout', function (event) {
        if (!carousel.contains(event.relatedTarget)) start();
    });
    carousel.addEventListener('touchstart', function (event) {
        startX = event.changedTouches[0].clientX;
        stop();
    }, { passive: true });
    carousel.addEventListener('touchend', function (event) {
        var delta = event.changedTouches[0].clientX - startX;
        if (Math.abs(delta) > 35) show(current + (delta < 0 ? 1 : -1));
        start();
    }, { passive: true });
    document.addEventListener('visibilitychange', start);

    show(0);
    start();
})();
