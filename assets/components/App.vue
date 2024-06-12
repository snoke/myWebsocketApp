<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
  <div id="layout " style="height:100vh;">
    <div class="container-fluid d-flex hcontainer flex-column p-0">
      <div class="row p-0">
        <div class="col p-0">
          <ul class="nav nav-tabs p-0">
            <li class="nav-item" @click="go('app_contacts')">
              <a href="#" target="_self" class="nav-link" v-bind:class="{ active: currentRoute('app_contacts')}">
                <font-awesome-icon icon="address-book"/>
                Contacts
              </a>
            </li>
            <li class="nav-item" @click="go('app_chats')">
              <a href="#" target="_self" class="nav-link"
                 v-bind:class="{ active: (currentRoute('app_chat') || currentRoute('app_chats')) }">
                <font-awesome-icon icon="edit"/>
                Chats
              </a>
            </li>
            <li class="nav-item" @click="go('app_settings')">
              <a href="#" target="_self" class="nav-link" v-bind:class="{ active: currentRoute('app_settings')}">
                <font-awesome-icon icon="cogs"/>
                Settings
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row flex-fill d-flex justify-content-start scrollable">
        <div class="col portlet-container portlet-dropzone content p-3 pb-1">
          <router-view></router-view>
        </div>
        <div id="bottom"></div>
      </div>
      <div class="row ">
        <div class="col input-clearfix" v-if="currentRoute('app_chat')">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App',
  data: function () {
    return {
      active: null,
    }
  },
  methods: {
    currentRoute(route) {
      return route === this.$router.currentRoute.name
    },
    go(page) {
      this.$router.push({name: page})
    },
  },
  mounted: function () {
  },
  updated: function () {
  },
  created: function () {
  }
}
</script>