<?php
/** Interactive bits for single article: reading progress, like/bookmark/follow,
 *  font-size cycle, comment-like, post-comment. Inline so it lives with the markup. */
if (! defined('ABSPATH')) exit;
?>
<script>
(function () {
    var bar = document.getElementById('readBar');
    var story = document.getElementById('storyBody');
    function updateProgress() {
        if (!story) return;
        var rect = story.getBoundingClientRect();
        var total = story.offsetHeight - window.innerHeight + 200;
        var scrolled = Math.max(0, -rect.top + 200);
        var pct = Math.min(100, Math.max(0, (scrolled / total) * 100));
        bar.style.width = pct + '%';
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    // Like — synchronises across all rails on the page.
    var likeBtns   = document.querySelectorAll('.js-like');
    var likeCounts = document.querySelectorAll('.js-like-count');
    var liked = false;
    likeBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            liked = !liked;
            likeBtns.forEach(function (b)   { b.classList.toggle('active', liked); });
            likeCounts.forEach(function (c) { c.textContent = liked ? '1' : '0'; });
        });
    });

    // Bookmark — persists in localStorage.
    var bmBtns = document.querySelectorAll('.js-bookmark');
    var bmKey  = 'ra-bm-' + (location.pathname || '/');
    var bmSaved = localStorage.getItem(bmKey) === '1';
    function paintBookmarks() {
        bmBtns.forEach(function (b) {
            b.classList.toggle('active', bmSaved);
            var tip = b.querySelector('.tip');
            if (tip) tip.textContent = bmSaved ? '✓ सेभ गरियो' : 'सेभ गर्नुहोस्';
        });
    }
    paintBookmarks();
    bmBtns.forEach(function (b) {
        b.addEventListener('click', function () {
            bmSaved = !bmSaved;
            localStorage.setItem(bmKey, bmSaved ? '1' : '0');
            paintBookmarks();
        });
    });

    // Copy link.
    document.querySelectorAll('.js-copy').forEach(function (b) {
        b.addEventListener('click', function () {
            var url = b.getAttribute('data-permalink') || location.href;
            var tip = b.querySelector('.tip');
            try {
                navigator.clipboard.writeText(url);
                if (tip) tip.textContent = '✓ कपी भयो';
                setTimeout(function () { if (tip) tip.textContent = 'लिङ्क कपी'; }, 1500);
            } catch (e) {}
        });
    });

    // Follow author.
    var fb = document.getElementById('followBtn');
    if (fb) {
        fb.addEventListener('click', function () {
            fb.classList.toggle('followed');
            fb.textContent = fb.classList.contains('followed') ? '✓ फलो भयो' : '+ फलो';
        });
    }

    // Font-size cycle.
    var fontStep = 0;
    var steps = [18, 19, 20, 22];
    document.querySelectorAll('.js-font').forEach(function (btn) {
        btn.addEventListener('click', function () {
            fontStep = (fontStep + 1) % steps.length;
            if (story) story.style.fontSize = steps[fontStep] + 'px';
        });
    });

    // "+N more" overflow → open bottom-sheet modal (no inline reveal, no CLS).
    var modal = document.getElementById('rail-modal');
    if (modal) {
        var openers = document.querySelectorAll('.article-rail .rail-more');
        var closers = modal.querySelectorAll('[data-rail-modal-close]');
        var lastFocus = null;

        function openModal(trigger) {
            lastFocus = trigger;
            modal.hidden = false;
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('rail-modal-open');
            // Focus first action for keyboard users.
            var first = modal.querySelector('.rail-modal__action');
            if (first) first.focus();
        }
        function closeModal() {
            modal.hidden = true;
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('rail-modal-open');
            if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
        }

        openers.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                openModal(toggle);
            });
        });
        closers.forEach(function (el) {
            el.addEventListener('click', closeModal);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });
    }

    document.querySelectorAll('.like-c').forEach(function (b) {
        b.addEventListener('click', function () {
            b.classList.toggle('liked');
            var match = b.textContent.match(/\d+/);
            var n = parseInt(match ? match[0] : '0', 10);
            var newN = b.classList.contains('liked') ? n + 1 : n - 1;
            b.innerHTML = b.innerHTML.replace(/\d+/, newN);
        });
    });

    window.postComment = function () {
        var t = document.getElementById('commentText').value.trim();
        var n = document.getElementById('commentName').value.trim() || 'अतिथि पाठक';
        if (!t) { alert('कृपया प्रतिक्रिया लेख्नुहोस्'); return; }
        var list = document.getElementById('commentList');
        var ava = n.substring(0, 1);
        var html = '<div class="comment" style="background: var(--red-soft); padding: 12px 14px; border-radius: 2px; border-bottom: none;">'
            + '<div class="ava">' + ava + '</div>'
            + '<div>'
            + '<div class="head"><span class="name">' + n + '</span><span class="badge">तपाईंको</span><span class="time">अहिले</span></div>'
            + '<div class="body">' + t + '</div>'
            + '<div class="actions" style="margin-top: 8px; font-family: var(--f-ui); font-size: 11px; color: var(--red);">✓ प्रतिक्रिया प्राप्त भयो — समीक्षा पछि प्रकाशित हुनेछ</div>'
            + '</div></div>';
        list.insertAdjacentHTML('afterbegin', html);
        document.getElementById('commentText').value = '';
    };
})();
</script>
