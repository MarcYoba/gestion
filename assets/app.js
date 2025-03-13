/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
// Importez le CSS de Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// Importez les fichiers de polices (nécessaire pour Webpack)
import '@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2';
import '@fortawesome/fontawesome-free/webfonts/fa-brands-400.woff2';
// ... autres variantes si nécessaires
import 'startbootstrap-sb-admin-2/css/sb-admin-2.min.css';
import $ from 'jquery';
window.$ = window.jQuery = $; // Rend jQuery disponible globalement
// Optionnel : Importez le CSS de Bootstrap (si nécessaire)
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
// Importez jQuery Easing
import 'jquery.easing';
import 'startbootstrap-sb-admin-2/js/sb-admin-2.min.js';
