import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// Import pages
import Home from '../components/pages/Home.vue';

// Program's child pages

// Define routes to pages
const routes = [
  { path: '/', component: Home },
];

// Export router
export default new VueRouter({
  routes
})
