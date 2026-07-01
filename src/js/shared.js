/* =====================================================
   Rastriya Aawaj — chrome interactions
   Header/footer markup is rendered by PHP (header.php / footer.php).
   This file wires up only the interactive bits: search drawer,
   dark-mode toggle, notification toast, and newsletter fake submit.
   ===================================================== */
(function () {
  function onReady(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  onReady(function () {
    // Search drawer toggle
    var sbtn = document.getElementById("ra-search-btn");
    var drawer = document.getElementById("ra-search-drawer");
    if (sbtn && drawer) {
      sbtn.addEventListener("click", function () {
        var open = drawer.style.display !== "block";
        drawer.style.display = open ? "block" : "none";
        if (open) {
          var input = drawer.querySelector("input");
          if (input) input.focus();
        }
      });
    }

    // Dark mode toggle (persisted in localStorage; theme styles not shipped yet)
    var dbtn = document.getElementById("ra-dark-btn");
    if (dbtn) {
      if (localStorage.getItem("ra-dark") === "1") {
        document.documentElement.classList.add("ra-dark");
      }
      dbtn.addEventListener("click", function () {
        document.documentElement.classList.toggle("ra-dark");
        localStorage.setItem(
          "ra-dark",
          document.documentElement.classList.contains("ra-dark") ? "1" : "0"
        );
      });
    }

    // Notification subscribe (toast stand-in)
    var nbtn = document.getElementById("ra-notif-btn");
    if (nbtn) {
      nbtn.addEventListener("click", function () {
        var toast = document.createElement("div");
        toast.textContent = "✓ नयाँ समाचार सूचना सक्षम गरियो";
        toast.style.cssText =
          "position:fixed;bottom:20px;right:20px;z-index:999;" +
          "background:var(--ink);color:#fff;padding:12px 18px;" +
          "font-family:var(--f-body-dn);font-size:14px;" +
          "border-left:3px solid var(--red);box-shadow:var(--shadow-2);";
        document.body.appendChild(toast);
        setTimeout(function () { toast.remove(); }, 2400);
      });
    }

    // Newsletter mini form (footer) — fake submit
    var nlForm = document.querySelector(".newsletter-mini");
    if (nlForm) {
      nlForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var btn = nlForm.querySelector("button");
        if (btn) btn.textContent = "✓ सब्स्क्राइब भयो";
      });
    }
  });
})();
