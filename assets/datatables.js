import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-bs4';
import 'bootstrap';
import 'datatables.net-bs4/css/dataTables.bootstrap4.css';

// Initialisation globale
$(document).ready(function() {
    $('.datatable').DataTable();
});