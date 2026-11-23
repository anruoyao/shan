(function () {
    'use strict';

    function authMode(url) {
        var value;
        try {
            value = decodeURIComponent(new URL(url, window.location.href).pathname + new URL(url, window.location.href).search);
        } catch (error) {
            value = String(url || '');
        }
        var match = value.match(/(?:^|[/?&])(?:pbcode\/)?member\/(login|register)(?:\/|\.|[?&#]|$)/i);
        return match ? match[1].toLowerCase() : '';
    }

    function popupUrl(url, message) {
        var target = new URL(url, window.location.href);
        var params = target.search.slice(1).split('&').filter(function (entry) {
            if (!entry) return false;
            var name = entry.split('=', 1)[0];
            try {
                name = decodeURIComponent(name.replace(/\+/g, ' '));
            } catch (error) {}
            return name !== 'auth_modal' && name !== 'auth_parent_origin' && name !== 'auth_message';
        });
        params.push('auth_modal=1');
        params.push('auth_parent_origin=' + encodeURIComponent(window.location.origin));
        if (message) params.push('auth_message=' + encodeURIComponent(message));
        target.search = params.length ? '?' + params.join('&') : '';
        return target.href;
    }

    var modal = document.getElementById('shAuthModal');
    if (!modal) {
        if (document.body && document.body.classList.contains('auth-popup') && window.parent !== window) {
            function notifyParent(message) {
                var targetOrigin = '*';
                try {
                    targetOrigin = new URLSearchParams(window.location.search).get('auth_parent_origin') || new URL(document.referrer).origin;
                } catch (error) {}
                window.parent.postMessage(message, targetOrigin);
            }

            window.shAuthPostMessage = notifyParent;

            document.addEventListener('click', function (event) {
                var link = event.target.closest('a[href]');
                if (!link) return;
                var mode = authMode(link.href);
                if (!mode) return;
                event.preventDefault();
                notifyParent({
                    type: 'sh-auth-switch',
                    mode: mode,
                    url: link.href
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') notifyParent({type: 'sh-auth-close'});
            });

            function reportHeight() {
                notifyParent({
                    type: 'sh-auth-height',
                    height: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                });
            }

            window.addEventListener('load', reportHeight);
            window.addEventListener('resize', reportHeight);
            if (window.ResizeObserver) {
                new ResizeObserver(reportHeight).observe(document.body);
            }
        }
        return;
    }

    var frame = document.getElementById('shAuthModalFrame');
    var closeButtons = modal.querySelectorAll('[data-auth-modal-close]');
    var previousFocus = null;
    var allowedAuthOrigins = [modal.getAttribute('data-login-url'), modal.getAttribute('data-register-url')].map(function (url) {
        try {
            return new URL(url, window.location.href).origin;
        } catch (error) {
            return '';
        }
    });

    function isAllowedAuthUrl(url) {
        try {
            return allowedAuthOrigins.indexOf(new URL(url, window.location.href).origin) !== -1;
        } catch (error) {
            return false;
        }
    }

    function authLinkUrl(url, mode) {
        var source;
        try {
            source = new URL(url, window.location.href);
        } catch (error) {
            return '';
        }
        if (isAllowedAuthUrl(source.href)) return source.href;
        if (source.origin !== window.location.origin) return '';

        var target = new URL(modal.getAttribute('data-' + mode + '-url'), window.location.href);
        var extraParams = source.search.slice(1).split('&').filter(function (entry) {
            if (!entry) return false;
            var name = entry.split('=', 1)[0];
            try {
                name = decodeURIComponent(name.replace(/\+/g, ' '));
            } catch (error) {}
            return !/^(?:pbcode\/)?member\/(?:login|register)\/?$/i.test(name) &&
                name !== 'auth_modal' && name !== 'auth_parent_origin' && name !== 'auth_message';
        });
        if (extraParams.length) {
            target.search = target.search + (target.search ? '&' : '?') + extraParams.join('&');
        }
        return target.href;
    }

    function setFrame(mode, url, message) {
        var fallback = modal.getAttribute('data-' + mode + '-url');
        if (!isAllowedAuthUrl(url || fallback)) url = fallback;
        frame.title = mode === 'register' ? '注册账号' : '账号登录';
        frame.style.height = '';
        frame.src = popupUrl(url || fallback, message || '');
    }

    function openAuthModal(mode, url, message) {
        mode = mode === 'register' ? 'register' : 'login';
        if (modal.hidden) {
            previousFocus = document.activeElement;
        }
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('sh-auth-open');
        document.documentElement.classList.add('sh-auth-open');
        setFrame(mode, url, message);
        var close = modal.querySelector('.sh-auth-modal__close');
        if (close) close.focus();
    }

    function closeAuthModal() {
        if (modal.hidden) return;
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('sh-auth-open');
        document.documentElement.classList.remove('sh-auth-open');
        frame.src = 'about:blank';
        frame.style.height = '';
        if (previousFocus && previousFocus.focus) previousFocus.focus();
    }

    window.openAuthModal = openAuthModal;
    window.closeAuthModal = closeAuthModal;

    closeButtons.forEach(function (button) {
        button.addEventListener('click', closeAuthModal);
    });

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link || modal.contains(link)) return;
        var mode = authMode(link.href);
        if (!mode) return;
        var url = authLinkUrl(link.href, mode);
        if (!url) return;
        event.preventDefault();
        openAuthModal(mode, url);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeAuthModal();
    });

    document.addEventListener('focusin', function (event) {
        if (!modal.hidden && !modal.contains(event.target)) {
            var close = modal.querySelector('.sh-auth-modal__close');
            if (close) close.focus();
        }
    });

    window.addEventListener('message', function (event) {
        if (modal.hidden || event.source !== frame.contentWindow || !event.data || !isAllowedAuthUrl(frame.src)) return;
        if (event.origin !== new URL(frame.src).origin) return;
        var message = event.data;
        if (message.type === 'sh-auth-close') {
            closeAuthModal();
        } else if (message.type === 'sh-auth-height' && !modal.hidden && Number.isFinite(message.height)) {
            var isMobile = window.matchMedia('(max-width: 600px)').matches;
            var viewportHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;
            var maxHeight = isMobile ? Math.max(180, Math.min(440, viewportHeight - 120)) : Math.max(280, viewportHeight - 48);
            frame.style.height = Math.min(Math.max(message.height, Math.min(320, maxHeight)), maxHeight) + 'px';
        } else if (message.type === 'sh-auth-switch' && (message.mode === 'login' || message.mode === 'register')) {
            setFrame(message.mode, message.url, message.message);
        } else if (message.type === 'sh-auth-login') {
            var syncFrame = document.createElement('iframe');
            syncFrame.setAttribute('aria-hidden', 'true');
            syncFrame.tabIndex = -1;
            syncFrame.style.cssText = 'position:absolute;width:1px;height:1px;left:-9999px;border:0;';
            syncFrame.src = message.syncUrl || 'about:blank';
            document.body.appendChild(syncFrame);
            window.setTimeout(function () {
                syncFrame.remove();
                closeAuthModal();
                if (message.url) {
                    window.location.assign(message.url);
                } else {
                    window.location.reload();
                }
            }, 700);
        }
    });
})();
