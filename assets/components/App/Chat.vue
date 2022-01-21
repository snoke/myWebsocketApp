<template>
    <div>
      <div v-if="chat">

        Chat with {{renderUsernames(chat.users)}}
        <div v-for="chatMessage in chat.chatMessages" :key="chatMessage.id">
          {{chatMessage.message}}
        </div>
         <textarea id="chat_input" class="w-100" placeholder="write a message" />
          <button type="button" class="w-100 btn btn-outline-primary" @click="send()">send</button>

      </div>
    </div>
</template>

<style scoped>
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

     //this may be fetched two times, once as default command return and once as return to all participants
    this.$root.$on('app:chat:send', (result) => {
        if (result.command=='app:chat:send') {
          this.chat.chatMessages.push(JSON.parse(result.data));
        }
     });
  }
}
</script>