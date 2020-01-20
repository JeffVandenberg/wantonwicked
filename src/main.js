import Vue from 'vue'

Vue.config.productionTip = false;

Vue.component('requests-summary', require('./components/home/RequestsSummary').default);
Vue.component('plots-summary', require('./components/home/PlotSummary').default);

window.Vue = Vue;