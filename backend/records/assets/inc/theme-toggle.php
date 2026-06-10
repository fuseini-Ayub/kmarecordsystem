<?php
if (!defined('INCLUDE_CHECK')) {
    die('You are not allowed to call this page directly.');
}
?>
<button id="themeToggle" class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
    <span class="icon">☀️</span>
</button>

<script>
function syncThemeIcon() {
    var theme = document.documentElement.getAttribute('data-theme') || 'light';
    var icon = document.querySelector('#themeToggle .icon');
    if (icon) {
        icon.textContent = theme === 'dark' ? '🌙' : '☀️';
    }
}

document.addEventListener('DOMContentLoaded', syncThemeIcon);
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    syncThemeIcon();
}

var themeObserver = new MutationObserver(function() {
    syncThemeIcon();
});
var htmlEl = document.documentElement;
themeObserver.observe(htmlEl, { attributes: true, attributeFilter: ['data-theme'] });
</script>
