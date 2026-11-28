(function () {
    'use strict';
    var data = JSON.parse(document.getElementById('wap-recommend-data').textContent);
    var books = data.books;
    var byId = {};
    var fields = {};
    var filtered = books;
    var currentPage = 1;
    var pageSize = 15;
    var feedback = document.getElementById('wap-recommend-feedback');
    books.forEach(function (book) { byId[String(book.id)] = book; });

    function selected(field) {
        return field.value.split(/[\s,，、]+/).filter(Boolean).filter(function (id, index, ids) { return ids.indexOf(id) === index; });
    }

    function button(label, action, disabled) {
        var element = document.createElement('button');
        element.type = 'button';
        element.textContent = label;
        element.disabled = !!disabled;
        element.addEventListener('click', action);
        return element;
    }

    function renderSlot(key) {
        var field = fields[key];
        var list = document.querySelector('[data-slot="' + key + '"] ol');
        list.textContent = '';
        var ids = selected(field);
        ids.forEach(function (id, index) {
            var row = document.createElement('li');
            var text = document.createElement('span');
            var book = byId[id];
            text.textContent = (index + 1) + '. ' + (book ? book.title : '无效或未上架的书籍') + '（' + id + '）';
            if (!book || (key === 'serial' && String(book.subtitle) === '1')) text.className = 'is-invalid';
            row.appendChild(text);
            function move(offset) {
                var other = index + offset;
                var copy = ids.slice();
                copy[index] = ids[other];
                copy[other] = id;
                field.value = copy.join(',');
                renderSlot(key);
            }
            var up = button('↑', function () { move(-1); }, index === 0);
            up.setAttribute('aria-label', '上移' + (book ? book.title : id));
            row.appendChild(up);
            var down = button('↓', function () { move(1); }, index === ids.length - 1);
            down.setAttribute('aria-label', '下移' + (book ? book.title : id));
            row.appendChild(down);
            row.appendChild(button('移除', function () {
                field.value = ids.filter(function (value) { return value !== id; }).join(',');
                renderSlot(key);
            }));
            list.appendChild(row);
        });
    }

    Object.keys(data.sections).forEach(function (key) {
        fields[key] = document.getElementById('wap-' + key);
        fields[key].addEventListener('input', function () { renderSlot(key); });
        renderSlot(key);
    });

    function addBook(key, book) {
        var ids = selected(fields[key]);
        var id = String(book.id);
        if (ids.indexOf(id) !== -1) { feedback.textContent = '该书已在' + data.sections[key] + '中'; return; }
        if (ids.length >= 20 || ids.concat(id).join(',').length > 200) { feedback.textContent = '每个推荐位最多 20 本书'; return; }
        fields[key].value = ids.concat(id).join(',');
        renderSlot(key);
        feedback.textContent = '已加入' + data.sections[key] + '，保存后生效';
    }

    function renderBooks() {
        var body = document.getElementById('wap-recommend-results');
        body.textContent = '';
        var pages = Math.max(1, Math.ceil(filtered.length / pageSize));
        currentPage = Math.min(currentPage, pages);
        filtered.slice((currentPage - 1) * pageSize, currentPage * pageSize).forEach(function (book) {
            var row = document.createElement('tr');
            [book.id, book.title, String(book.subtitle) === '1' ? '完本' : '连载', ''].forEach(function (value) {
                var cell = document.createElement('td');
                cell.textContent = value;
                row.appendChild(cell);
            });
            var author = document.createElement('small');
            author.textContent = book.author;
            row.children[1].appendChild(author);
            Object.keys(data.sections).forEach(function (key) {
                var add = button(data.sections[key], function () { addBook(key, book); }, key === 'serial' && String(book.subtitle) === '1');
                add.className = 'layui-btn layui-btn-primary layui-btn-xs';
                row.children[3].appendChild(add);
            });
            body.appendChild(row);
        });
        if (!filtered.length) {
            var empty = document.createElement('tr');
            var cell = document.createElement('td');
            cell.colSpan = 4;
            cell.textContent = '没有找到符合条件的作品';
            empty.appendChild(cell);
            body.appendChild(empty);
        }
        document.getElementById('wap-recommend-page').textContent = currentPage + ' / ' + pages + ' 页，共 ' + filtered.length + ' 本';
        document.getElementById('wap-recommend-prev').disabled = currentPage === 1;
        document.getElementById('wap-recommend-next').disabled = currentPage === pages;
    }

    document.getElementById('wap-recommend-search').addEventListener('input', function (event) {
        var keyword = event.target.value.trim().toLowerCase();
        filtered = books.filter(function (book) { return (book.id + ' ' + book.title + ' ' + book.author).toLowerCase().indexOf(keyword) !== -1; });
        currentPage = 1;
        renderBooks();
    });
    document.getElementById('wap-recommend-prev').addEventListener('click', function () { currentPage--; renderBooks(); });
    document.getElementById('wap-recommend-next').addEventListener('click', function () { currentPage++; renderBooks(); });
    renderBooks();
}());
