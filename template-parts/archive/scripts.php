<?php
/** Archive interactions — filter chip toggle + save/bookmark with localStorage. */
if (! defined('ABSPATH')) exit;
?>
<script>
(function () {
    document.querySelectorAll('.chip').forEach(function (c) {
        c.addEventListener('click', function () {
            c.parentElement.querySelectorAll('.chip').forEach(function (x) { x.classList.remove('active'); });
            c.classList.add('active');
        });
    });

    var saved = JSON.parse(localStorage.getItem('ra-saved') || '[]');
    var SAVED_OUT = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg> सेभ';
    var SAVED_IN  = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg> सेभ गरियो';
    function refresh() {
        document.querySelectorAll('.save-btn').forEach(function (b) {
            var id = b.dataset.id;
            if (saved.indexOf(id) > -1) { b.classList.add('saved'); b.innerHTML = SAVED_IN; }
            else { b.classList.remove('saved'); b.innerHTML = SAVED_OUT; }
        });
    }
    refresh();
    document.querySelectorAll('.save-btn').forEach(function (b) {
        b.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            var id = b.dataset.id;
            var i = saved.indexOf(id);
            if (i > -1) saved.splice(i, 1); else saved.push(id);
            localStorage.setItem('ra-saved', JSON.stringify(saved));
            refresh();
        });
    });
})();
</script>
