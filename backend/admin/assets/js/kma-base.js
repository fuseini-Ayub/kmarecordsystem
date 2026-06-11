/* ══════════════════════════════════════════════════════════════════
   KMA Base JS – Lightweight replacement for Bootstrap 4 JS
   Handles: navbar collapse, dropdowns, alerts dismissal
   ══════════════════════════════════════════════════════════════════ */
(function () {
  'use strict';

  /* ── Navbar Collapse ── */
  function initNavbarCollapse() {
    document.querySelectorAll('[data-toggle="collapse"]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var targetId = this.getAttribute('data-target') || this.getAttribute('href');
        var target = document.querySelector(targetId);
        if (!target) return;

        if (target.classList.contains('show')) {
          target.classList.remove('show');
          this.setAttribute('aria-expanded', 'false');
        } else {
          target.classList.add('show');
          this.setAttribute('aria-expanded', 'true');
        }
      });
    });

    // Close collapse when clicking outside
    document.addEventListener('click', function (e) {
      var navbar = document.querySelector('.navbar-collapse.show');
      if (!navbar) return;
      var toggler = document.querySelector('[data-toggle="collapse"][aria-expanded="true"]');
      if (!navbar.contains(e.target) && toggler && !toggler.contains(e.target)) {
        navbar.classList.remove('show');
        toggler.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ── Dropdowns ── */
  function initDropdowns() {
    document.querySelectorAll('[data-toggle="dropdown"]').forEach(function (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var menu = this.nextElementSibling;
        if (!menu || !menu.classList.contains('dropdown-menu')) return;

        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu.show').forEach(function (m) {
          if (m !== menu) m.classList.remove('show');
        });

        menu.classList.toggle('show');
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(function (m) {
          m.classList.remove('show');
        });
      }
    });

    // Prevent dropdown menu clicks from closing
    document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
      menu.addEventListener('click', function (e) {
        e.stopPropagation();
      });
    });
  }

  /* ── Alert Dismissal ── */
  function initAlertDismissal() {
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
      var closeBtn = alert.querySelector('.close');
      if (!closeBtn) {
        closeBtn = document.createElement('button');
        closeBtn.className = 'close';
        closeBtn.innerHTML = '&times;';
        closeBtn.setAttribute('aria-label', 'Close');
        alert.prepend(closeBtn);
      }
      closeBtn.addEventListener('click', function () {
        alert.style.transition = 'opacity 0.3s, transform 0.3s';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(function () { alert.remove(); }, 300);
      });
    });
  }

  /* ── Toast Auto-dismiss ── */
  function initToasts() {
    document.querySelectorAll('.toast').forEach(function (toast) {
      if (toast.classList.contains('show')) {
        var delay = parseInt(toast.getAttribute('data-delay')) || 5000;
        if (delay > 0) {
          setTimeout(function () {
            toast.style.transition = 'opacity 0.3s';
            toast.style.opacity = '0';
            setTimeout(function () { toast.classList.remove('show'); toast.style.opacity = ''; }, 300);
          }, delay);
        }
      }
    });
  }

  /* ── Tooltip (simple title-based) ── */
  function initTooltips() {
    document.querySelectorAll('[data-toggle="tooltip"]').forEach(function (el) {
      var title = el.getAttribute('title') || el.getAttribute('data-original-title');
      if (!title) return;
      el.removeAttribute('title');

      var tip = document.createElement('div');
      tip.className = 'tooltip';
      tip.innerHTML = '<div class="tooltip-inner">' + title + '</div>';
      tip.style.cssText = 'position:absolute;z-index:1060;display:none;pointer-events:none;';

      el.style.position = 'relative';
      el.appendChild(tip);

      el.addEventListener('mouseenter', function () {
        tip.style.display = 'block';
        tip.style.bottom = '100%';
        tip.style.left = '50%';
        tip.style.transform = 'translateX(-50%)';
        tip.style.marginBottom = '6px';
      });

      el.addEventListener('mouseleave', function () {
        tip.style.display = 'none';
      });
    });
  }

  /* ── Popover (simple) ── */
  function initPopovers() {
    document.querySelectorAll('[data-toggle="popover"]').forEach(function (el) {
      var content = el.getAttribute('data-content') || '';
      if (!content) return;

      var pop = document.createElement('div');
      pop.className = 'popover';
      pop.innerHTML = '<div class="popover-body">' + content + '</div>';
      pop.style.cssText = 'position:absolute;z-index:1060;display:none;top:100%;left:50%;transform:translateX(-50%);margin-top:6px;';

      el.style.position = 'relative';
      el.appendChild(pop);

      el.addEventListener('click', function (e) {
        e.stopPropagation();
        var isVisible = pop.style.display === 'block';
        document.querySelectorAll('.popover').forEach(function (p) { p.style.display = 'none'; });
        pop.style.display = isVisible ? 'none' : 'block';
      });
    });

    document.addEventListener('click', function () {
      document.querySelectorAll('.popover').forEach(function (p) { p.style.display = 'none'; });
    });
  }

  /* ── Form Validation (basic) ── */
  function initFormValidation() {
    document.querySelectorAll('form[novalidate]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        var isValid = true;
        form.querySelectorAll('[required]').forEach(function (input) {
          if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = 'var(--danger)';
            input.addEventListener('input', function handler() {
              input.style.borderColor = '';
              input.removeEventListener('input', handler);
            });
          }
        });
        if (!isValid) e.preventDefault();
      });
    });
  }

  /* ── Smooth scroll for anchor links ── */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
      link.addEventListener('click', function (e) {
        var targetId = this.getAttribute('href');
        if (targetId === '#' || targetId.length < 2) return;
        var target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  }

  /* ── Initialize everything on DOM ready ── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    initNavbarCollapse();
    initDropdowns();
    initAlertDismissal();
    initToasts();
    initTooltips();
    initPopovers();
    initFormValidation();
    initSmoothScroll();
  }
})();
