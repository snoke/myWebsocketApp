<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
    <div id="layout " style="height:100vh;">
        <div class="container-fluid d-flex hcontainer flex-column p-0">
            <div class="row p-0">
                <div class="col p-0">
                    <ul class="nav nav-tabs p-0">
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
            </div>
              <div class="row flex-fill d-flex justify-content-start scrollable">
                  <div class="col portlet-container portlet-dropzone content p-3 pb-1">

                      <router-view></router-view>
               




                  </div> <div id="bottom">
  </div>
              </div>
             
                <div class="row ">
                    <div class="col input-clearfix" v-if="currentRoute('app_chat')">
                    </div>
                </div>
        </div>
    </div>
</template>

<style scoped>
#layout {
    height:100vh;
}
.hcontainer {
  height:100vh;
}
.input-clearfix {
  height:100px;
}
.scrollable {
    overflow-y: scroll;
  overflow-x: hidden;
}
.flex-fill {
    flex:1;
}
.row {
    margin-left:0px;
    margin-right:0px;
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
      },
  },
  mounted: function() {
  },
  updated: function() {
  },
  created: function() {
      this.$root.$on('chat:typing', (result) => {
        this.$root.$emit('Chat::chat:typing',result);
      });
      this.$root.$on('chat:message:status', (result) => {
        this.$root.$emit('ChatMessage::chat:message:status',result);
      });
      this.$root.$on('user:change:password', (result) => {
        this.$root.$emit('AppSettings::user:change:password',result);
      });
      this.$root.$on('chat:load:userchats', (result) => {
        this.$root.$emit('AppChats::chat:load:userchats',result);
      });
      this.$root.$on('contact:add', (result) => {
        this.$root.$emit('AppContacts::contact:add',result);
      });
      this.$root.$on('contact:search', (result) => {
        this.$root.$emit('AppContacts::contact:search',result);
      });
      this.$root.$on('user:contacts', (result) => {
        this.$root.$emit('AppContacts::user:contacts',result);
      });
      this.$root.$on('chat:unblock', (result) => {
        this.$root.$emit('AppChats::chat:unblock',result);
        this.$root.$emit('Chat::chat:unblock',result);
      });
      this.$root.$on('chat:block', (result) => {
        this.$root.$emit('AppChats::chat:block',result);
        this.$root.$emit('Chat::chat:block',result);
      });
      this.$root.$on('chat:message:send', (result) => {
        this.$root.$emit('Chat::chat:message:send',result);
      });
      this.$root.$on('chat:load', (result) => {
        this.$root.$emit('Chat::chat:load',result);
      });
      this.$root.$on('chat:load:messages', (result) => {
        this.$root.$emit('Chat::chat:load:messages',result);
      });
      this.$root.$on('file:upload', (result) => {
        this.$root.$emit('Chat::file:upload',result);
      });

        if (!("Notification" in window)) {
       //  alert("This browser does not support desktop notification");
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