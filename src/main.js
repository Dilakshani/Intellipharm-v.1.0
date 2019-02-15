/**
 * The application entrypoint.
 */

 // Import jQuery plugin
import $ from 'jquery'
window.jQuery = $;
window.$ = $;

// Import bootstrap plugin
import boostrap from 'bootstrap'

// Import styles
import './scss/app.scss'

// Import Vue and Vue router plugins
import Vue from 'vue';
import router from './router';

// Import root component
import App from './components/App.vue';

Vue.config.productionTip = false;

// Vue instance
new Vue({
  el: "#app",
  router,
  render: h => h(App)
});

// Theme libraries
require('../static/theme/js/jquery.dcjqaccordion.2.7.js');
require('../static/theme/js/jquery.scrollTo.min.js');
require('../static/theme/js/common-scripts.js');



