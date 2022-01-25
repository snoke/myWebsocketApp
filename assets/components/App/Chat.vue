<template>
    <div>
      <div v-if="chat" @dragover.prevent @drop.prevent> 
        <div class="chat-container" @drop="dragFile"> 
          <div v-for="chatMessage in chat.chatMessages" :key="chatMessage.id">
            <b-row class="pb-1">
              <b-col  v-bind:class="{ 'alert-primary': chatMessage.sender.id==$root.claim.id}"></b-col>
              <b-col md="auto" v-bind:class="{ 'alert-secondary':chatMessage.sender.id!=$root.claim.id,'alert-primary': chatMessage.sender.id==$root.claim.id}">
                <div class="alert" >
                    <div v-if="chatMessage.file!=null">
                      <img class="chat-image" :src="chatMessage.file.content" />
                    </div>
                    {{chatMessage.message}}
                </div>
              </b-col>
              <b-col  v-bind:class="{ 'alert-secondary':chatMessage.sender.id!=$root.claim.id}"></b-col>
            </b-row>
          </div>
          <hr />
          <textarea id="chat_input" class="w-100" placeholder="write a message" />
            <b-button-group class="w-100">
              <button type="button" class="w-100 btn btn-outline-primary" @click="send()">send <font-awesome-icon icon="paper-plane" /></button>
              <b-dropdown dropup menu-class="minw-none" variant="primary"  >
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
  margin-bottom:0px!important;
}
</style>

<script>
  import $ from 'jquery'
  export default {
    name: 'Chat',
    data: function() {
      return {        
        windowWidth: window.innerWidth,
        files: [],
        chat:null,
        messages:[],
        allowed_file_types:[
          'image/jpeg',
          'image/png'
        ],
        allowed_file_size:1024 * 1024 * 0.5 // 0.5 mb
      }
    },    
    methods: {
        onResize() {
          this.windowWidth = window.innerWidth
              $('.chat-image').css('maxWidth',window.innerWidth-150);
          },
        fileAdded(e) {
            this.files = this.$refs.file.files;
            this.uploadFile();
        },
        uploadFile() {
          if (this.allowed_file_types.includes(this.files[0]['type'])) {
              if (this.files[0].size<this.allowed_file_size) {
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
              } else { alert ("the file is too big.")}
          } else { alert ("the filetype is not allowed.")}
        },
        dragFile(e) {
          this.files = e.dataTransfer.files;
            this.uploadFile();
        },
      send() {
      var  msg = document.getElementById('chat_input').value
      if (msg) {
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
      }
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
            this.onResize();
    },
    mounted() {
            window.addEventListener('resize', this.onResize);
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
            this.onResize();
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
            this.onResize();
          }
      });
    }
  }
</script>