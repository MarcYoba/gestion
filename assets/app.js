/* ==================== */
/* IMPORTS CSS (Ordre critique) */
/* ==================== */
// 1. Bibliothèques de base
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'animate.css/animate.min.css';
import Chart from 'chart.js/auto';
// 2. Thème SB Admin 2 (doit venir après les librairies qu'il surcharge)
import '../node_modules/startbootstrap-sb-admin-2/css/sb-admin-2.min.css';

// 3. Vos styles personnalisés (toujours en dernier)
import './styles/app.css';

/* ==================== */
/* IMPORTS JS (Ordre critique) */
/* ==================== */
// 1. jQuery (base)
import $ from 'jquery';
window.jQuery = $;
window.$ = $;

// 2. Extensions jQuery
import 'jquery.easing/jquery.easing.min.js';

// 3. Bootstrap (avec Popper)
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// 4. Autres bibliothèques
import WOW from 'wow.js';

/* ==================== */
/* INITIALISATION */
/* ==================== */
$(document).ready(function() {
  // Configuration globale
  window.Chart = Chart;

  // Initialisation WOW.js
  new WOW({
    offset: 100,
    mobile: true
  }).init();

  // Chargement dynamique de SB Admin 2
  import('../node_modules/startbootstrap-sb-admin-2/js/sb-admin-2.min.js')
    .then(() => {
      // Initialisation des composants
      initSBAdminComponents();
    })
    .catch(error => console.error('SB Admin 2 loading error:', error));
});

/* ==================== */
/* FONCTIONS D'INITIALISATION */
/* ==================== */
function initSBAdminComponents() {
  // Sidebar Toggle
  $('#sidebarToggle').off('click').on('click', function(e) {
    e.preventDefault();
    $('body').toggleClass('sidebar-toggled');
    $('.sidebar').toggleClass('toggled');
  });

  // Tooltips (avec vérification Bootstrap 5)
  if (typeof bootstrap !== 'undefined') {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el);
    });
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
      new bootstrap.Popover(el);
    });
  } else {
    // Fallback pour Bootstrap 4
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
  }
} 