<template>
    <div id="chats" v-if="chats!=null">
      
      <div v-if="chats.length==0">
         <p>you have no contacts added yet to chat with. </p>
       <button type="button" class="w-100 btn btn-outline-primary" @click="$router.push({ name: 'app_contacts'})">Find Contacts <font-awesome-icon icon="address-book" /></button>
      </div>
        <div v-for="chat in chats" :key="chat.id">  
     <b-button-group class="w-100"  v-if="chat.blockedBy!=null" >
              <button type="button" disabled class="w-75 btn btn-outline-danger"   > </button>
              <button type="button" class="w-25 btn btn-outline-danger"  @click="unblockChat(chat.id)"
              v-if="$root.claim.id==chat.blockedBy.id"
              >unblock  {{renderUsernames(chat.users)}} <font-awesome-icon icon="cog" /></button>
                <!--   
                  <b-dropdown-item><font-awesome-icon icon="camera" /></b-dropdown-item>
                  <b-dropdown-divider /> 
                -->
            </b-button-group>
          <button type="button" v-if="chat.blockedBy==null"
            class="w-100 btn btn-outline-primary"
            @click="startChat(chat.id)" >
         Chat with {{renderUsernames(chat.users)}}  <font-awesome-icon icon="comments" />
         </button>
        </div>
    </div>
</template>

<style scoped>
</style>

<script>
export default {
  name: 'Chats',
  data: function() {
    return {
      chats: []
      }
  },
  methods: {
    unblockChat(chatId) {
        this.$confirm({
          message: `Are you sure to unblock?`,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          callback: confirm => {
            if (confirm) {
              this.$root.connection.send(
                  JSON.stringify({
                      'action': 'chat:unblock',
                      'params': {
                          'chatId': chatId,    
                          'userId': this.$root.claim.id,    
                      }
                  })
              );
            }
          }
        })

              
    },
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
    this.$root.$off('chat:unblock') 
    this.$root.$off('chat:load:userchats')
  },
  created: function() {
        
    this.$root.$on('chat:load:userchats', (result) => {
        if (result.command=='chat:load:userchats') {
          this.chats = JSON.parse(result.data);
        }
     });

      this.$root.$on('chat:unblock', (result) => {
        for(var i in this.chats) {
          var data = JSON.parse(result.data)
          if (this.chats[i].id == data.id) {

            this.$root.connection.send(
                JSON.stringify({
                    'action': 'chat:load:userchats',
                    'params': {
                        'userId': this.$root.claim.id,    
                    }
                })
            );
          }
        }
      });

        this.$root.connection.send(
            JSON.stringify({
                'action': 'chat:load:userchats',
                'params': {
                    'userId': this.$root.claim.id,    
                }
            })
        );
  }
}
</script>