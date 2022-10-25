import 'core-js/stable'
import 'regenerator-runtime/runtime'
import Vue from 'vue';
import Vuetify from 'vuetify';
import VueCookies from 'vue-cookies';
import App from './App.vue';
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.config.productionTip = false
Vue.use(Vuetify);
Vue.use(VueCookies);

// eslint-disable-next-line no-new
const app = new Vue({
    vuetify: new Vuetify(),
    el: '#Game',
    icons: {
        iconFont: 'mdi'
    },
    render: (h) => h(App),
});

export default app;
