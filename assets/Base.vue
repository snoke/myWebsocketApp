<template>
  <div id="app">
    <div v-if="$root.token==null">
      <Auth />
    </div>
    <div v-if="$root.claim!=null">
      <App />
    </div>
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
  },
  updated: function() {
  },
}
</script>