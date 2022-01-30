<template>
    <div class="chat-wrapper">
      <div @dragover.prevent @drop.prevent> 
      <div class=" stickyButton">
        <b-dropdown dropdown menu-class="minw-none" variant="secondary"  >
          <template #button-content>
            <font-awesome-icon icon="cog" /> {{renderUsernames(users)}}
          </template>
          <b-dropdown-item  @click="clearChat()"><font-awesome-icon icon="eraser" /> Clear Chat</b-dropdown-item>
            <b-dropdown-divider /> 
          <b-dropdown-item  @click="blockChat()"><font-awesome-icon icon="ban" /> Block Chat</b-dropdown-item>
        </b-dropdown>
        </div>
        <div class="chat-container" @drop="dragFile" > 
          <div @click="hide('.icon-group')" class="chat-inner-container" >
            <div v-for="chatMessage in chatMessages" :key="chatMessage.id">
                <ChatMessage :data="chatMessage" />
            </div>
          </div>
          <b-button-group class="pt-3 w-100" >
            <b-dropdown dropup menu-class="minw-none" class="emoji-btn btn btn-outline-primary" variant="light" >
              <template #button-content>😊</template>
              <b-dropdown-item v-for="k,group in this.emojis" :key="group" @click="showgroup(group)"> {{group}}</b-dropdown-item>
            </b-dropdown>
              <div v-for="arr,group in this.emojis" :key="group" :class="group+' icon-group w-100'" style="display:none;position:absolute;">
                <div v-for="k,v in arr" :key="k" style="display:inline;" role='button' @click="addSmiley(v)">{{v}}</div>
              </div>
            <textarea rows="1" id="chat_input" class="border-primary w-100 " placeholder="write a message" v-on:keyup="replaceEmoji" @click="hide('.icon-group')"/>
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
  background: none!important;
  border: none!important;
}
.emoji-btn {
  padding:5px;
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  margin-right: -1px;
  margin-bottom: 5px!important;
}
.minw-none {
  min-width:3rem!important;
}
</style>

<style scoped>
.chat-inner-container{
  min-height:3rem;
}
.stickyButton {
  z-index:1;
  right: 1rem;
  position: fixed;
  top: 3.5rem;
}
hr {
margin-bottom:0.5em;margin-top:0.25em;
}
.border-primary {
  border: 1px solid #0d6efd;
}
.alert-secondary{
  margin-left:8em;
}
textarea {
  resize: none;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
.rounded {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
.icon-group {
  z-index:2;
  border:1px solid grey;
  padding:5px;
  border-radius:5px;
  background-color:white;
  border-bottom:0px;
  bottom:55px;

}
.row {
  margin-left:0px!important;
  margin-right:0px!important;
}
.alert {
  margin-bottom:4px!important;
  padding:0.5rem!important;
}
</style>

<script>
import  ChatMessage  from './ChatMessage.vue'
import { emojis } from '../emojis.json'
  import $ from 'jquery'
  export default {
    name: 'Chat',
    components: {ChatMessage},
    data: function() {
      return {       
        id:null,
        users:[],
        chatMessages:[],

        emojis,
        windowWidth: window.innerWidth,
        allowed_file_types:[
          'image/jpeg',
          'image/png'
        ],
        allowed_file_size:1024 * 1024 * 0.5, // 0.5 mb
      }
    },    
    methods: {
      getMessage(msgId) {
       for (var msg in messages) {
         if (msg.id == msgId) {
           return msg;
         }
       }
      },
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
                            'action': 'file:upload',
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
      setStatus(msg,status) {
        if (msg.status!=status) {
          this.$root.connection.send(
              JSON.stringify({
                  'action': 'chat:message:status',
                  'params': {
                      'messageId': msg.id,
                      'status': status,    
                  }
              })
          );
        }
      },
      send() {
        var  msg = document.getElementById('chat_input').value
        if (msg) {
          this.$root.connection.send(
              JSON.stringify({
                  'action': 'chat:message:send',
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
      beforeDestroy () {
        this.$root.$off('chat:load')
        this.$root.$off('file:upload')
        this.$root.$off('chat:message:send')
    },
    created: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'chat:load',
                'params': {
                    'chatId': this.$route.params.id,    
                }
            })
        );
      this.$root.$on('chat:load', (result) => {
          if (result.command=='chat:load') {
            var chat = JSON.parse(result.data);
            this.id = chat.id;
            this.chatMessages = chat.chatMessages;
            this.users = chat.users;
            this.onResize();
          }
      });

      this.$root.$on('file:upload', (result) => {
          if (result.command=='file:upload') {
            this.$root.connection.send(
                JSON.stringify({
                    'action': 'chat:message:send',
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

      this.$root.$on('chat:message:send', (result) => {
          if (result.command=='chat:message:send') {
            var msg = JSON.parse(result.data);
            this.chatMessages.push(msg);
          }
      });

    }
  }
</script>