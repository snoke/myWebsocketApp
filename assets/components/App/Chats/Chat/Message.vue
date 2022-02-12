<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
    <div> 
        <div class="row pb-1" ref="message" >
            <div class="" v-bind:class="{
                'col-1':  isSender(),
                'col-2':  !isSender(),
                }">
            </div>
            <div class="col-9 message-content" v-bind:class="{ 
                'alert-primary': isSender(),
                'alert-secondary': !isSender()
                }">
                {{message}}
                <ChatMessageFile :data="file" v-if="file" />
                <div  class="message-check" v-if="isSender()">
                    <font-awesome-icon class="check-icon" icon="check" v-if="seen" />
                    <font-awesome-icon class="check-icon" icon="check" v-if="sent" />
                </div>

                <div class="message-date">{{ sent | moment("D.MM.YYYY hh:mm") }} </div>
            </div>
            <div ref="sc" v-bind:class="{
                'col-1':  !isSender(),
                'col-2':  isSender(),
                }">
            </div>
        </div>
    </div>
</template>

<style scoped>
.message-content {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  white-space: break-spaces;
}
.message-check {
    margin-top:0.25rem;
    float:right;
    font-size:0.75rem
}
.message-date {
    margin-top:0.25rem;
    float:right;
    font-size:0.75rem
}
</style>

<script>
import  ChatMessageFile  from './Message/File.vue'
export default {
  name: 'Message',
  components: {ChatMessageFile},
  props: ['data'],
  data: function() {
    return {
        id:this.data.id,
        sender:this.data.sender,
        message:this.data.message,
        file:this.data.file,
        sent:this.data.sent,
        delivered:this.data.delivered,
        seen:this.data.seen,
        status:this.data.status,
      }
  },
  
  methods: {
      isSender() {
          return  this.sender.id==this.$root.claim.id
      },
      save() {
          this.$root.connection.send(
              JSON.stringify({
                  'action': 'chat:message:send',
                  'params': {
                          'token': this.$root.token,
                      'chatId': this.$route.params.id,    
                      'message': message,    
                  }
              })
          );
      }
  },
  mounted: function() {
   //document.getElementById('bottom').scrollIntoView({behavior: "smooth", block: "end"});  
  },
  updated: function() {
   //document.getElementById('bottom').scrollIntoView({behavior: "smooth", block: "end"});  
  },
  created: function() {
    if (this.sender.id!=this.$root.claim.id && this.status!='seen') {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'chat:message:status',
                'params': {
                          'token': this.$root.token,
                    'messageId': this.id,
                    'status': 'seen',    
                }
            })
        );
    }

      this.$root.$on('ChatMessage::chat:message:status', (result) => {
            if (result.command == 'chat:message:status') {
                var _message = JSON.parse(result.data);
                if (_message.id == this.id) {
                    this.id = _message.id;
                    this.sender = _message.sender;
                    this.message = _message.message;
                    this.file = _message.file;
                    this.sent = _message.sent;
                    this.delivered = _message.delivered;
                    this.seen = _message.seen;
                this.$forceUpdate();
                }
            }
      });  
      
  }
}
</script>