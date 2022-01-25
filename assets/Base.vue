<template>
  <div id="app"> <vue-confirm-dialog></vue-confirm-dialog>
    <router-view></router-view>
  </div>
</template>

<style>
</style>


<script>
import App from './components/App';
import Auth from './components/Auth';
export default {
  name: 'Base',
    components: {App,Auth},
  data: function() {
    return {
    }
  },
  methods: {
  },
  created: function() {
    if (this.$root.token==null) {

      this.$router.push({ name: 'auth'})
    } else {
      this.$router.push({ name: 'app'})
    }
  },
  updated: function() {
  },
}
</script>