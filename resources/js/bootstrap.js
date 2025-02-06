window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


import Popper from 'popper.js/dist/umd/popper.js';

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.$ = window.jQuery = require('jquery');
//window.Popper = require('popper.js/dist/umd/popper.js').default;
window.Popper = Popper;
require('bootstrap');
require('bootstrap-fileinput')
require('bootstrap-fileinput/themes/fa4/theme.min')
require('bootstrap-select/dist/js/bootstrap-select.min');

import Prism from 'prismjs';
require('prismjs/plugins/toolbar/prism-toolbar.js')
require('prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.js')
Prism.highlightAll();

require('croppie/croppie.js');



/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: socket_io_server,
    wsPort: socket_io_port,
    forceTLS: false,
    disableStats: true,
});

window.Echo.channel("broadcast").listen('Broadcast', (e)=>{
    alert(e.title+':\n'+e.content);
});
