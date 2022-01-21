<template>
    <div>
      <div v-for="chat in chats" :key="chat.id">
        Chat with {{renderUsernames(chat.users)}}
         <textarea id="chat_input" class="w-100" placeholder="write a message" />
          <button type="button" class="w-100 btn btn-outline-primary" >send</button>

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
      chats:null
      }
  },
  methods: {
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
          this.chats = [JSON.parse(result.data)];
        }
     });
  }
}
</script>