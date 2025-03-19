/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
// Import CSS
import 'animate.css';

// Import et initialisation de WOW.js
import WOW from 'wow.js';
new WOW().init();

// start the Stimulus application
import './bootstrap';
// Import des dépendances
import 'bootstrap';
//import $ from 'jquery';
import '@fortawesome/fontawesome-free/js/all';
// import 'startbootstrap-sb-admin-2';
import '../node_modules/startbootstrap-sb-admin-2/css/sb-admin-2.min.css';

// JS
import '../node_modules/startbootstrap-sb-admin-2/js/sb-admin-2.min.js';
import 'bootstrap/dist/css/bootstrap.min.css';
$(document).ready(function() {
    // Activer les tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Activer les popovers
    $('[data-toggle="popover"]').popover();
    
    // Initialiser le template SB Admin 2
    $('#sideToggle').click(function() {
      $('body').toggleClass('sidebar-toggled');
      $('.sidebar').toggleClass('toggled');
    });
  });

// Configuration SB Admin 2


// Activer les tooltips Bootstrap
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
// Importez le CSS de Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// Importez les fichiers de polices (nécessaire pour Webpack)
import '@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2';
import '@fortawesome/fontawesome-free/webfonts/fa-brands-400.woff2';
// assets/app.js
import 'font-awesome/css/font-awesome.css';
// ... autres variantes si nécessaires

//import $ from 'jquery';
window.$ = window.jQuery = $; // Rend jQuery disponible globalement
// Optionnel : Importez le CSS de Bootstrap (si nécessaire)

//import 'bootstrap';
// Importez jQuery Easing
import 'jquery.easing';

