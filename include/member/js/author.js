/* 作者端工作台交互：移动端侧栏开关 */
(function () {
    var toggle = document.getElementById('awMenuToggle');
    var sidebar = document.getElementById('awSidebar');
    if (!toggle || !sidebar) {
        return;
    }
    toggle.addEventListener('click', function () {
        var opened = sidebar.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', opened ? 'true' : 'false');
    });
    sidebar.addEventListener('click', function (event) {
        if (event.target.closest('a') && window.innerWidth <= 960) {
            sidebar.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
})();
