<template>
    <div id="settings">
          <input type="text" placeholder="displayName" name="loginName" v-model="register_loginName"  class="w-100" />
          <button v-on:click="save()"  class="btn btn-outline-primary w-100">save</button>
    </div>
</template>

<style scoped>
</style>

<script>
export default {
  name: 'Settings',
  data: function() {
    return {
      }
  },
  methods: {
    save: function() {

    }
  },
  updated: function() {
  },
  created: function() {
  }
}
</script>