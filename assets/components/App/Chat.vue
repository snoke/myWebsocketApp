<template>
    <div>
      <div v-if="chat" @dragover.prevent @drop.prevent> 
        <b-dropdown dropdown menu-class="minw-none" variant="secondary"  >
          <template #button-content>
            <font-awesome-icon icon="cog" /> Chat with {{renderUsernames(chat.users)}}
          </template>
          <b-dropdown-item  @click="clearChat()"><font-awesome-icon icon="eraser" /> Clear Chat</b-dropdown-item>
            <b-dropdown-divider /> 
          <b-dropdown-item  @click="blockChat()"><font-awesome-icon icon="ban" /> Block Chat</b-dropdown-item>
        </b-dropdown>
        <hr v-if="chat.chatMessages.length>0" style="margin-bottom:0.5em;margin-top:0.25em;" />
        <div class="chat-container" @drop="dragFile" > 
          <div @click="hide('.icon-group')">
            <div v-for="chatMessage in chat.chatMessages" :key="chatMessage.id">
                    <div class="row pb-1">
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
              </div>
            </div>
            <hr style="margin-bottom:0.5em;margin-top:0.25em;"  />
          </div>
          <b-button-group class="w-100" >
            <b-dropdown dropup menu-class="minw-none" class="emoji-btn" variant="light" >
              <template #button-content>😊</template>
              <b-dropdown-item v-for="k,group in this.emojis" :key="group" @click="showgroup(group)"> {{group}}</b-dropdown-item>
            </b-dropdown>
              <div v-for="arr,group in this.emojis" :key="group" :class="group+' icon-group w-100'" style="display:none;position:absolute;">
                <div v-for="k,v in arr" :key="k" style="display:inline;" @click="addSmiley(v)">{{v}}</div>
              </div>
            <textarea  rows="1" id="chat_input" class="w-100" placeholder="write a message" v-on:keyup="replaceEmoji" @click="hide('.icon-group')"/>
          </b-button-group>
          <div  @click="hide('.icon-group')">
            <b-button-group class="w-100"  >
              <button type="button" class="w-100 btn btn-outline-primary" @click="send()">send <font-awesome-icon icon="paper-plane" /></button>
              <b-dropdown dropup menu-class="minw-none" variant="primary" >
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
    </div>
</template>

<style>
.btn-light{
  margin-bottom:0px!important;
}
.emoji-btn {
  margin-right: 1px;
  border:1px solid grey;
  border-right:0px;
  margin-bottom: 5px!important;
}
.minw-none {
  min-width:3rem!important;
}
</style>

<style scoped>
.icon-group {
  background-color:white;
  border: 1px solid grey;
  border-bottom:0px;
  bottom:52px;

}
.row {
  margin-left:0px!important;
  margin-right:0px!important;
}
.alert {
  text-align:center;
  margin-bottom:0px!important;
}
</style>

<script>
import { emojis } from '../emojis.json'
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
        allowed_file_size:1024 * 1024 * 0.5, // 0.5 mb
        emojis,
      }
    },    
    methods: {
      hide(group) {
        $(group).hide();
      },
      addSmiley(smiley) {
       document.getElementById('chat_input').value = document.getElementById('chat_input').value + smiley
      },
      showgroup(group) {
        $('.icon-group').hide();
        $('.' + group).show();
      },
      replaceEmoji() {
       var txt = document.getElementById('chat_input').value
       for (var group in this.emojis) {
        for (var [code,smiley] in this.emojis[group]) {
            txt = txt.replace(this.emojis[group][code], code);
        }
      }
      document.getElementById('chat_input').value = txt
      },
      clearChat() {
        this.$confirm({
          message: `Are you sure to delete all previous messages?`,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          callback: confirm => {
            if (confirm) {
            }
          }
        })

      },
      blockChat() {
        this.$confirm({
          message: `Are you sure to close and block this chat?`,
          button: {
            no: 'No',
            yes: 'Yes'
          },
          callback: confirm => {
            if (confirm) {
            }
          }
        })

      },
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