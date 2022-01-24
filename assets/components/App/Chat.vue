<template>
    <div>
      <div v-if="chat" @dragover.prevent @drop.prevent> 
      <div class="chat-container" @drop="dragFile"> 
        <div v-for="chatMessage in chat.chatMessages" :key="chatMessage.id">
           <div class="alert alert-primary" v-if="chatMessage.sender.id==$root.claim.id">
              <div v-if="chatMessage.file!=null">
                 <img :src="chatMessage.file.content" />
              </div>
              {{chatMessage.message}}
          </div>
           <div class="alert alert-secondary" v-if="chatMessage.sender.id!=$root.claim.id">
              <div v-if="chatMessage.file!=null">
                 <img :src="chatMessage.file.content" />
              </div>
              {{chatMessage.message}}
          </div>
        </div>
         <textarea id="chat_input" class="w-100" placeholder="write a message" />
          <b-button-group class="w-100">
            <button type="button" class="w-100 btn btn-outline-primary" @click="send()">send</button>
            <b-dropdown dropup menu-class="minw-none" >
              <template #button-content>
                <font-awesome-icon icon="file" />
              </template>
              <!--   
                <b-dropdown-item><font-awesome-icon icon="camera" /></b-dropdown-item>
                <b-dropdown-divider /> 
              -->
              <b-dropdown-item  @click="$refs.file.click()"><font-awesome-icon icon="image" /></b-dropdown-item>
            </b-dropdown>
          </b-button-group>
          <input type="file" ref="file" style="display: none" @change="fileAdded">
      </div>
      </div>
    </div>
</template>

<style>
.minw-none {
  min-width:3rem!important;
}
</style>
<style scoped>
.alert {
  padding: 0.5rem 0.5rem;
margin-bottom: 0.5rem;
}
.alert-primary {
  margin-left:50px;
}
.alert-secondary {
  margin-right:50px;
}
</style>

<script>
export default {
  name: 'Chat',
  data: function() {
    return {
      files: [],
      chat:null,
      messages:[],
      allowed_file_types:[
        'image/jpeg',
        'image/png'
      ],
      allowed_file_size:1024*4 //kb
    }
  },
  methods: {
      fileAdded(e) {
          this.files = this.$refs.file.files;
          this.uploadFile();
      },
      uploadFile() {
        if (this.allowed_file_types.includes(this.files[0]['type'])) {
            if (this.files[0].size/1024<this.allowed_file_size) {
              var reader = new FileReader();
                reader.onloadend =  () => {
                  this.$root.connection.send(
                      JSON.stringify({
                          'action': 'app:file:upload',
                          'params': {
                              'userId': this.$root.claim.id,    
                              'content': reader.result,    
                              'filename': this.files[0].name, 
                          }
                      })
                  );
                }
                reader.readAsDataURL(this.files[0]);
            }
        }
      },
      dragFile(e) {
        this.files = e.dataTransfer.files;
          this.uploadFile();
      },
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
          console.log(this.chat)
        }
     });

    this.$root.$on('app:file:upload', (result) => {
        if (result.command=='app:file:upload') {
          this.$root.connection.send(
              JSON.stringify({
                  'action': 'app:chat:send',
                  'params': {
                      'senderId': this.$root.claim.id,    
                      'chatId': this.$route.params.id,    
                      'message': '',   
                      'file': JSON.parse(result.data)
                  }
              })
          );
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