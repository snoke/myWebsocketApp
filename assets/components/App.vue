<template>
    <div id="app">
        <div id="navbar">
            <b-nav tabs>
                <b-nav-item >Contacts</b-nav-item>
                <b-nav-item active>Chats</b-nav-item>
                <b-nav-item>Settings</b-nav-item>
            </b-nav>
        </div>
        <div id="content">
            Hello {{$root.claim.username}}
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
  },
  updated: function() {
  },
  created: function() {
  }
}
</script>