<template>
    <div id="settings">
          <input type="text" placeholder="display Name" name="displayName" class="w-100" />
          <div  >
            <input type="checkbox" name="emoji" checked> 
            <label for="scales" v-b-tooltip.hover.right title="Replaces your Input Text Smiley Codes like `:)` with Emoji Icons">Place Emojis</label>
          </div>
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