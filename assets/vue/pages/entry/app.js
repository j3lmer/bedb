import 'core-js/stable'
import 'regenerator-runtime/runtime'
import Vue from 'vue';
import Vuetify from 'vuetify';
import App from './App.vue';
import 'vuetify/dist/vuetify.min.css'

Vue.config.productionTip = false
Vue.use(Vuetify);

// eslint-disable-next-line no-new
const app = new Vue({
    vuetify: new Vuetify(),
    el: '#Entry',
    render: (h) => h(App),
});

export default app;
