<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
    <div>
      <div id="loading" v-if="ready==false">
        loading
      </div>
    <div class="chat-wrapper">
      <div @dragover.prevent @drop.prevent> 
      <div class=" stickyButton">
        <b-dropdown dropdown menu-class="minw-none" variant="secondary"  >
          <template #button-content>
            <font-awesome-icon icon="cog" /> {{renderUsernames(users)}}
          </template>
          <!--   
            <b-dropdown-item  @click="clearChat()"><font-awesome-icon icon="eraser" /> Clear</b-dropdown-item>
            <b-dropdown-divider /> 
          -->
          <b-dropdown-item class="alert-danger"  @click="blockChat()"><font-awesome-icon icon="ban" /> Block</b-dropdown-item>
        </b-dropdown>
        </div>
        <div class="chat-container" @drop="dragFile" > 
          <div @click="hide('.icon-group')" class="chat-inner-container" >
            <div v-for="(chatMessage,index) in chatMessages" :key="chatMessage.id">
                <ChatMessage :data="chatMessage" v-if="chatMessages.length-visible<index" />
            </div>
            <div>
                <div v-if="timerCount>0">
                    <div class="row pb-1" ref="message">
                        <div class="col-3" >
                        </div>
                        <div class="col-6 alert alert-info rounded" >
                            <font-awesome-icon icon="keyboard" /> 
                            {{typingUser}} is typing <font-awesome-icon icon="spinner" spin />
                        </div>
                        <div class="col-3">
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
          <div class="stickyInput">
            <b-button-group class="w-100" >
              <b-dropdown dropup menu-class="minw-none" class="emoji-btn btn btn-outline-primary" variant="light" >
                <template #button-content>😊</template>
                <b-dropdown-item v-for="k,group in this.emojis" :key="group" @click="showgroup(group)"> {{group}}</b-dropdown-item>
              </b-dropdown>
                <div v-for="arr,group in this.emojis" :key="group" :class="group+' icon-group w-100'" style="display:none;position:absolute;">
                  <div v-for="k,v in arr" :key="k" style="display:inline;" role='button' @click="addSmiley(v)">{{v}}</div>
                </div>
              <textarea rows="1" id="chat_input" class="disabled border-primary w-100 " placeholder="write a message" v-on:keydown="isTyping()" v-on:keyup="replaceEmoji" @click="hide('.icon-group')" />
            </b-button-group>
            <div  @click="hide('.icon-group')">
              <b-button-group class="w-100"  >
                <button type="button" class="w-100 btn btn-outline-primary " @click="send()" >send <font-awesome-icon icon="paper-plane" /></button>
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
</template>

<style>
.h-100p {
  height:100%;
}
.rounded {
    
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
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
.stickyInput {
  height:100px;
  padding-left:1em;
  padding-right:1em;
    z-index:1;
    bottom:0px;
    width:100%;
    position: fixed;
    left: 0;
}
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
  border-left: 0!important;
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
import moment from 'moment';
import  ChatMessage  from './Chat/Message.vue'
import { emojis } from './Chat/emojis.json'
  import $ from 'jquery'
  export default {
    name: 'Chat',
    components: {ChatMessage},
    data: function() {
      return {       
        visible:99999,
        page:0,
        typingUser:null,
        timerCount:0,
        ready:false,
        id:null,
        users:[],
        chatMessages:[],
        blockedBy:null,
        emojis,
        windowWidth: window.innerWidth,
        allowed_file_types:[
          'image/jpeg',
          'image/png'
        ],
        allowed_file_size:1024 * 1024 * 5, // 0.5 mb
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
      isTyping() {
              this.$root.connection.send(
                  JSON.stringify({
                      'action': 'chat:typing',
                      'params': {
                          'token': this.$root.token,
                          'chatId': this.id,   
                      }
                  })
              );
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
              this.$root.connection.send(
                  JSON.stringify({
                      'action': 'chat:block',
                      'params': {
                          'token': this.$root.token,
                          'chatId': this.id,    
                      }
                  })
              );
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
                          'token': this.$root.token,
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
                          'token': this.$root.token,
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
                          'token': this.$root.token, 
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
        this.$root.$off('Chat::chat:load')
        this.$root.$off('Chat::file:upload')
        this.$root.$off('Chat::chat:message:send')
        this.$root.$off('Chat::chat:block')
        this.$root.$off('Chat::chat:typing')
        this.$root.$off('Chat::chat:unblock')
        this.$root.$off('Chat::chat:load:messages')
    },
    
        watch: {
            timerCount: {
                handler(value) {
                    if (value > 0) {
                        setTimeout(() => {
                                    this.timerCount--;
                        }, 1);
                    }
                },
            }
        },
    created: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'chat:load',
                'params': {
                          'token': this.$root.token,
                    'chatId': this.$route.params.id,    
                }
            })
        );
      this.$root.$on('Chat::chat:typing', (result) => {
       var data = JSON.parse(result.data);
        if (data.chat.id==this.id) {
            if (data.user.id!=this.$root.claim.id) {
                this.timerCount = 50;
                this.typingUser = data.user.username;                

              setTimeout(function() {    
                document.getElementById('bottom').scrollIntoView({behavior: "smooth", block: "end"});  
              
              }, 1);
            }
        }
      })
      this.$root.$on('Chat::chat:unblock', (result) => {
       var data = JSON.parse(result.data);
        if (data.id==this.id) {
          this.$root.connection.send(
              JSON.stringify({
                  'action': 'chat:load',
                  'params': {
                          'token': this.$root.token,
                      'chatId': this.$route.params.id,    
                  }
              })
          );
        }
      });
      this.$root.$on('Chat::chat:block', (result) => {
       var data = JSON.parse(result.data);
        if (data.id==this.id) {
          this.$router.push({ name: "app_chats"})
        }
      });
      this.$root.$on('Chat::chat:load', (result) => {
            var chat = JSON.parse(result.data);
            this.id = chat.id;
            this.page = 0;
            this.chatMessages = [];
            this.users = chat.users;
            this.ready=true;   
            this.$root.connection.send(
                JSON.stringify({
                    'action': 'chat:load:messages',
                    'params': {
                          'token': this.$root.token,
                        'chatId': this.$route.params.id,   
                        'page': this.page,
                        'steps': 1,
                    }
                })
            );
      });
      this.$root.$on('Chat::chat:load:messages', (result) => {
            var messages = JSON.parse(result.data);
            if (messages.length==1) {
              this.page++;
              var message = messages[0];
              this.chatMessages.unshift(message);     
              document.getElementById('bottom').scrollIntoView({behavior: "auto", block: "end"}); 
                  this.$root.connection.send(
                    JSON.stringify({
                        'action': 'chat:load:messages',
                        'params': {
                          'token': this.$root.token,
                            'chatId': this.$route.params.id,   
                            'page': this.page,
                            'steps': 1,
                        }
                    })
                );
            }
      });

      this.$root.$on('Chat::chat:message:send', (result) => {
            var msg = JSON.parse(result.data);
            this.chatMessages.push(msg);
            
              setTimeout(function() {    
                document.getElementById('bottom').scrollIntoView({behavior: "smooth", block: "end"});  
              
              }, 1);
              if (msg.sender.id!=this.$root.claim.id) {
                this.$root.notify(msg.sender.username + ": " + msg.message)
              }
      });
      this.$root.$on('Chat::file:upload', (result) => {
            this.$root.connection.send(
                JSON.stringify({
                    'action': 'chat:message:send',
                    'params': {
                          'token': this.$root.token,
                        'chatId': this.$route.params.id,    
                        'message': '',   
                        'file': JSON.parse(result.data)
                    }
                })
            );
      });

    }
  }
</script>