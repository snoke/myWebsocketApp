/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import Vue from 'vue';
import Base from './Base';
import Auth from './components/Auth';
import App from './components/App';
import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { faCogs } from '@fortawesome/free-solid-svg-icons'
import { faUserPlus } from '@fortawesome/free-solid-svg-icons'
import { faEdit } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faUserSecret)

library.add(faCogs)
library.add(faUserPlus)
library.add(faEdit)

Vue.component('font-awesome-icon', FontAwesomeIcon)
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import VueRouter from 'vue-router'
// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(VueRouter)
const router = new VueRouter({  
    mode:'history',
    components: {Base},
    routes: [
        { 
                name: "root",
                path: '/', 
                component:  Base,
                props: true,
        },
        { 
                name: "auth",
                path: '/auth', 
                component:  Auth,
                props: true,
        },
        { 
                name: "app",
                path: '/app', 
                component:  App,
                props: true,
        }
    ]
});
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)
new Vue({
    created: function() {
      this.connection = new WebSocket("ws://localhost:8080")
      
        this.connection.onopen =  () => {
            this.connected = true;
        }
        this.connection.onclose =  () => {
            this.connected = false;
        }
        
        this.connection.onmessage = (event) => {
            let result = JSON.parse(event.data);
            this.$emit(result.command, result)
            
      }
    },
    methods: {
    },
    data: function() {
      return {
        connection:null,
        connected:false,
        token:null,
        claim:null,
      }
    },
    el: '#app',
    render: h => h(Base),  router: router,
});