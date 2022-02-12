<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
  <div id="app"> 
    <div v-if="!$root.connected">
      connecting...
    </div>
    <div v-if="$root.connected">
    <vue-confirm-dialog></vue-confirm-dialog>
      <router-view></router-view>
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
    connect() {
      if (!this.$root.connection) {
        this.$root.connect();
        setTimeout(() => {
            this.connect()
        }, 3000)
    }
}
  },
  beforeDestroy () {
    this.$root.$off('Base::connection-lost')
  },
  created: function() {
        this.connect();
      this.$root.$on('Base::connection-lost', () => {
        this.connect();
        });
  },
  updated: function() {
  },
}
</script>