<template>
    <div id="app">
        <div id="navigation">
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
        </div>
        <div id="content">
            <div class="p-3">
                <router-view></router-view>
            </div>
        </div>
        <div id="bottom">
        </div>
    </div>
</template>

<style scoped>
#content {
  padding-top: 3rem;
}
.nav {
    position: fixed;
    background-color: white;
    width:100%;
}
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
      this.$root.$on('chat:message:send', (result) => {
            this.$root.$emit('chat:message:_send', result)
            var msg = JSON.parse(result.data);
            if (msg.sender.id!=this.$root.claim.id) {
              this.$root.notify(msg.sender.username + ": " + msg.message)
            }
      });
        if (!("Notification" in window)) {
          alert("This browser does not support desktop notification");
        }
        else if (Notification.permission !== "denied") {
          Notification.requestPermission().then((permission) => {
            // If the user accepts, let's create a notification
            if (permission === "granted") {
              this.$root.notify_permission = true;
            }
          });
        }
        else if (Notification.permission === "granted") {
          // If it's okay let's create a notification
              this.$root.notify_permission = true;
        }
  }
}
</script>