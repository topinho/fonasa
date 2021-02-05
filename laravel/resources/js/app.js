/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import App from './App.vue';
import ElementUI from 'element-ui';
import axios from 'axios';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);
Vue.prototype.axios = axios;

const app = new Vue({
    el: '#app',
    render: h => h(App),
});
