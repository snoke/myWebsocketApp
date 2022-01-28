<template>
    <div id="settings">
          <button v-on:click="save()"  class="btn btn-outline-primary w-100">save <font-awesome-icon icon="save" /></button>
    </div>
</template>

<style scoped>
</style>

<script>
export default {
  name: 'Settings',
  data: function() {
    return {
        user: {}
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