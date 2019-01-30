window.Popper = require('popper.js').default;
import 'sweetalert2/dist/sweetalert2.min.js';
import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/widgets/autocomplete.js';
import 'jquery-validation/dist/jquery.validate.min.js';

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {        
    window.$ = window.jQuery = require('jquery');    
    window.swal = require('sweetalert2');
    require('bootstrap');    

} catch (e) {}