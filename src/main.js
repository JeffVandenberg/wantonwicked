import Vue from 'vue'

Vue.config.productionTip = false;

Vue.component('requests-summary', require('./components/home/RequestsSummary').default);
Vue.component('plots-summary', require('./components/home/PlotSummary').default);
Vue.component('character-summary', require('./components/home/CharacterSummary').default);
Vue.component('scene-summary', require('./components/home/SceneSummary').default);

window.Vue = Vue;