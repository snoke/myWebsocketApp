<template>
    <div id="chats" v-if="chats!=null">
      
      <div v-if="chats.length==0">
         <p>you have no contacts added yet to chat with. </p>
       <button type="button" class="w-100 btn btn-outline-primary" @click="$router.push({ name: 'app_contacts'})">Find Contacts <font-awesome-icon icon="address-book" /></button>
      </div>
       <button type="button" v-for="chat in chats" :key="chat.id" class="w-100 btn btn-outline-primary" @click="startChat(chat.id)">Chat with {{renderUsernames(chat.users)}}  <font-awesome-icon icon="comments" /></button>
    </div>
</template>

<style scoped>
</style>

<script>
export default {
  name: 'Chats',
  data: function() {
    return {
      chats: null
      }
  },
  methods: {
    startChat(chatId) {
          this.$router.push({ name: 'app_chat', params: { id: chatId }})
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
  mounted: function() {
  },
  beforeDestroy () {
    this.$root.$off('app:user:chats')
  },
  created: function() {
    
        this.$root.connection.send(
            JSON.stringify({
                'action': 'app:user:chats',
                'params': {
                    'userId': this.$root.claim.id,    
                }
            })
        );
        
    this.$root.$once('app:user:chats', (result) => {
        if (result.command=='app:user:chats') {
          this.chats = JSON.parse(result.data);
        }
     });
  }
}
</script>