

/**
 * Event Bus
 **/

// Create a Vue event bus. 
window.EventBus = new Vue();

// And attach it to the Vue model so each Vue component may access it easily. 
Object.defineProperties(Vue.prototype, {
    $bus: {
        get: function () {
            return EventBus;
        }
    }
});

// Notify components when JQuery is loaded
$(document).ready( function () {
    window.locale = document.documentElement.lang;
    window.token = document.head.querySelector('meta[name="csrf-token"]').content;
});

/**
 * Vue App.
 */
const app = new Vue({
    el: '#app'
});
