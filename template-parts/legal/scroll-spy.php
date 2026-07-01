<?php
/** TOC scroll-spy — highlights the active `.toc a` based on which `.doc section`
 *  is currently in view, and smooth-scrolls when a TOC link is clicked. */
if (! defined('ABSPATH')) exit;
?>
<script>
(function () {
    var links = document.querySelectorAll('.toc a');
    var sections = document.querySelectorAll('.doc section');
    if (!links.length || !sections.length) return;
    function onScroll() {
        var active = sections[0];
        sections.forEach(function (s) { if (s.getBoundingClientRect().top < 120) active = s; });
        links.forEach(function (l) { l.classList.toggle('active', l.getAttribute('href') === '#' + active.id); });
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    links.forEach(function (l) {
        l.addEventListener('click', function (e) {
            e.preventDefault();
            var target = document.querySelector(l.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
})();
</script>
