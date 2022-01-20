<template>
    <div id="app">
        <div id="navbar">
            <b-nav tabs>
                <b-nav-item @click="go('app_contacts')" >
                    <font-awesome-icon icon="user-plus" /> Contacts
                </b-nav-item>
                <b-nav-item @click="go('app_chats')">
                    <font-awesome-icon icon="edit" /> Chats
                </b-nav-item>
                <b-nav-item @click="go('app_settings')">
                    <font-awesome-icon icon="cogs" /> Settings
                </b-nav-item>
            </b-nav>
        </div>
        <div id="content" class="p-3">
            <router-view></router-view>
        </div>
    </div>
</template>

<style scoped>
#content {
    background-color:white;
}
</style>

<script>
export default {
  name: 'App',
  data: function() {
    return {
      }
  },
  methods: {
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