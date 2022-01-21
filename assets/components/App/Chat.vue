<template>
    <div>
      <div v-if="chat"> 
        <b-dropdown  class="right">
          <template #button-content>
            <font-awesome-icon icon="cog" /> {{renderUsernames(chat.users)}}
          </template>
          <div class="dropdown-divider"></div>
          <b-dropdown-item href="#" class="">Block {{renderUsernames(chat.users)}}</b-dropdown-item>
        </b-dropdown>
  
      <div class="chat-container"> 
        <div v-for="chatMessage in chat.chatMessages" :key="chatMessage.id">
           <div class="alert alert-primary" v-if="chatMessage.sender.id==$root.claim.id">
              {{chatMessage.message}}
          </div>
           <div class="alert alert-secondary" v-if="chatMessage.sender.id!=$root.claim.id">
              {{chatMessage.message}}
          </div>
        </div>
         <textarea id="chat_input" class="w-100" placeholder="write a message" />
          <button type="button" class="w-100 btn btn-outline-primary" @click="send()">send</button>

      </div>
      </div>
    </div>
</template>

<style scoped>
.chat-container {
  padding-top:50px;
}
.right {
  float: right;
  margin-left: auto; 
  margin-right: 0;
}
.alert-primary {
  margin-left:50px;
}
.alert-secondary {
  margin-right:50px;
}
textarea {
  margin-bottom:-2px;
}
</style>

<script>
export default {
  name: 'Chat',
  data: function() {
    return {
      chat:null,
      messages:[]
      }
  },
  methods: {
    send() {
     var  msg = document.getElementById('chat_input').value
      this.$root.connection.send(
          JSON.stringify({
              'action': 'app:chat:send',
              'params': {
                  'senderId': this.$root.claim.id,    
                  'chatId': this.$route.params.id,    
                  'message': msg,    
              }
          })
      );
      document.getElementById('chat_input').value = "";
    },
    renderUsernames(users) {
      var names = [];
      for(var user of users) {
        if (user.id!=this.$root.claim.id) {
            names.push(user.username)
        }
      }
      return names.join(', ')
    },
  },
  updated: function() {
  },

  created: function() {
      this.$root.connection.send(
          JSON.stringify({
              'action': 'app:chat',
              'params': {
                  'chatId': this.$route.params.id,    
              }
          })
      );
    this.$root.$on('app:chat', (result) => {
        if (result.command=='app:chat') {
          this.chat = JSON.parse(result.data);
        }
     });

    this.$root.$on('app:chat:send', (result) => {
        if (result.command=='app:chat:send') {
          this.chat.chatMessages.push(JSON.parse(result.data));
        }
     });
  }
}
</script>