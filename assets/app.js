/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

import './styles/app.css';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import Vue from 'vue';
import Base from './Base';
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import routes from './routes.js'
import VueConfirmDialog from 'vue-confirm-dialog'
import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import device from "vue-device-detector"
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {library} from '@fortawesome/fontawesome-svg-core'
import {
    faArrowUp,
    faBan,
    faCheck,
    faEraser,
    faKeyboard,
    faLock,
    faSpinner,
    faUnlock,
    faUserSecret,
    faCogs,
    faCog,
    faUserPlus,
    faEdit,
    faFile,
    faImage,
    faCamera,
    faPaperPlane,
    faComments,
    faSave,
    faSignInAlt,
    faSignOutAlt,
    faAddressBook,
    faQuestion
} from '@fortawesome/free-solid-svg-icons'

library.add(faUserSecret)
library.add(faCogs)
library.add(faCog)
library.add(faUserPlus)
library.add(faEdit)
library.add(faFile)
library.add(faImage)
library.add(faCamera)
library.add(faPaperPlane)
library.add(faComments)
library.add(faSave)
library.add(faSignInAlt)
library.add(faSignOutAlt)
library.add(faAddressBook)
library.add(faQuestion)
library.add(faBan)
library.add(faEraser)
library.add(faCheck)
library.add(faLock)
library.add(faUnlock)
library.add(faKeyboard)
library.add(faSpinner)
library.add(faArrowUp)

Vue.use(VueConfirmDialog)
Vue.use(require('vue-moment'), {
    moment
})
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueAxios, axios)
Vue.use(device)
Vue.use(VueRouter)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('vue-confirm-dialog', VueConfirmDialog.default)

const moment = require('moment')

let router = {
    components: {Base},
    routes: routes
};

const config = JSON.parse(document.getElementById('_appConfig').innerHTML);
if (config.client === 'web') {
    router.mode = 'history'
}
router = new VueRouter(router);

new Vue({
    data: function () {
        return {
            config: [],
            connection: null,
            connected: null,
            token: null,
            claim: null,
            notify_permission: null,
        }
    },
    created: function () {
        this.config = config
    },
    methods: {
        notify: function (message) {
            if (this.notify_permission) {
                console.log("got permissions, sending notification")
                const notification = new Notification(message);
            } else {
                console.log("no permissions")
            }
        },
        created() {
        },
    },
    el: '#app',
    render: h => h(Base), router: router,
});