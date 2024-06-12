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

<script>
import App from './components/App';
import Auth from './components/Auth';

export default {
  name: 'Base',
  components: {App, Auth},
  data: function () {
    return {}
  },
  methods: {
    connect() {
      if (!this.$root.connection) {
        if (!this.$root.connected) {
          this.$root.connection = new WebSocket(this.$root.config.websocket_url)
          this.$root.connection.onopen = () => {
            this.$root.connected = true;
            this.$router.push({name: 'auth'})
          }
          this.$root.connection.onclose = () => {
            this.$root.connected = null;
            this.$root.connection = null;
            this.$root.$emit('Base::connection-lost', {});
          }

          this.$root.connection.onmessage = (event) => {
            let result = JSON.parse(event.data);
            this.$root.$emit(result.command, result)

          }
          setTimeout(() => {
            this.connect()
          }, 3000)
        }
      }
    },
    eventRouter() {
      this.$root.$on('chat:typing', (result) => {
        this.$root.$emit('Chat::chat:typing', result);
      });
      this.$root.$on('chat:message:status', (result) => {
        this.$root.$emit('ChatMessage::chat:message:status', result);
      });
      this.$root.$on('user:change:password', (result) => {
        this.$root.$emit('AppSettings::user:change:password', result);
      });
      this.$root.$on('chat:load:userchats', (result) => {
        this.$root.$emit('AppChats::chat:load:userchats', result);
      });
      this.$root.$on('contact:add', (result) => {
        this.$root.$emit('AppContacts::contact:add', result);
      });
      this.$root.$on('contact:search', (result) => {
        this.$root.$emit('AppContacts::contact:search', result);
      });
      this.$root.$on('user:contacts', (result) => {
        this.$root.$emit('AppContacts::user:contacts', result);
      });
      this.$root.$on('chat:unblock', (result) => {
        this.$root.$emit('AppChats::chat:unblock', result);
        this.$root.$emit('Chat::chat:unblock', result);
      });
      this.$root.$on('chat:block', (result) => {
        this.$root.$emit('AppChats::chat:block', result);
        this.$root.$emit('Chat::chat:block', result);
      });
      this.$root.$on('chat:message:send', (result) => {
        this.$root.$emit('Chat::chat:message:send', result);
      });
      this.$root.$on('chat:load', (result) => {
        this.$root.$emit('Chat::chat:load', result);
      });
      this.$root.$on('chat:load:messages', (result) => {
        this.$root.$emit('Chat::chat:load:messages', result);
      });
      this.$root.$on('file:upload', (result) => {
        this.$root.$emit('Chat::file:upload', result);
      });
    },
    permitNotifications() {
      if (!("Notification" in window)) {
        //  alert("This browser does not support desktop notification");
      } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then((permission) => {
          // If the user accepts, let's create a notification
          if (permission === "granted") {
            this.$root.notify_permission = true;
          }
        });
      } else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        this.$root.notify_permission = true;
      }
    }
  },
  beforeDestroy() {
    this.$root.$off('Base::connection-lost')
  },
  created: function () {
    this.eventRouter()
    this.permitNotifications()
    this.connect();
    this.$root.$on('Base::connection-lost', () => {
      this.connect();
    });
  },
  updated: function () {
  },
}
</script>