import Vue from 'vue'

Vue.config.productionTip = false;

Vue.component('requests-summary', require('./components/home/RequestsSummary').default);

window.Vue = Vue;