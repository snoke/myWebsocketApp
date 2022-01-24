<template>
    <div id="app">
        <ul class="nav nav-tabs">
                <li class="nav-item"  @click="go('app_contacts')">
                    <a  href="#" target="_self" class="nav-link" v-bind:class="{ active: currentRoute('app_contacts')}" >  
                        <font-awesome-icon icon="address-book" /> Contacts 
                    </a>
                </li>

                <li class="nav-item"  @click="go('app_chats')"  >
                    <a  href="#" target="_self" class="nav-link" v-bind:class="{ active: (currentRoute('app_chat') || currentRoute('app_chats')) }">
                        <font-awesome-icon icon="edit" /> Chats 
                    </a>
                </li>

                <li class="nav-item"  @click="go('app_settings')" >
                    <a  href="#" target="_self" class="nav-link" v-bind:class="{ active: currentRoute('app_settings')}">
                        <font-awesome-icon icon="cogs" />  Settings 
                    </a>
                </li>
            </ul>
        <div id="content" class="p-3">
            <router-view></router-view>
        </div>
    </div>
</template>

<style scoped>
.nav-item {
    border-left:1px solid white;
}
#content {
    background-color:white;
}
</style>

<script>
export default {
  name: 'App',
  data: function() {
    return {
        active:null,
      }
  },
  methods: {
      currentRoute(route) {
       return route==this.$router.currentRoute.name
      },
      go(page) {
        this.$router.push({ name: page})
      }
  },
  mounted: function() {
  },
  updated: function() {
  },
  created: function() {
  }
}
</script>