
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    // require('bootstrap-sass');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL =  window.location.origin;

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

/**
 * Load the permissions to be used elsewhere in vue.
 */

let permissions = document.head.querySelector('meta[name="permissions"]');

if(permissions) {
    window.permissions = JSON.parse(permissions.content);
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

  $(document).ready(function(){
    
    $("#clear_br").on('click', function(){
    
        $("#vd_edit input[name=name]").val('');
        $("#vd_edit input[name=address]").val('');
        $("#vd_edit input[name=beneficiary_name]").val('');
        $("#vd_edit input[name=account_number]").val('');
        $("#vd_edit input[name=iban_code]").val('');
        $("#vd_edit input[name=swift_code]").val('');
        $("#vd_edit input[name=sort_code]").val('');
        $("#vd_edit input[name=currency]").val('');
      
    });
     
  });