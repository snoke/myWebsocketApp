<template>
    <div> 
        <div class="row pb-1">
            <div class="col"></div>
            <div class="col rounded" v-bind:class="{ 
                'alert-primary': sender.id==$root.claim.id,
                'alert-secondary': sender.id!=$root.claim.id
                }">
                {{message}}
                <ChatMessageFile :data="file" v-if="file"/>
                      <div><span>{{ sent | moment("dddd, MMMM Do YYYY hh:mm") }}</span></div>
                      <div  v-if="sender.id==$root.claim.id">
                          <font-awesome-icon class="check-icon" icon="check" v-if="seen" />
                          <font-awesome-icon class="check-icon" icon="check" v-if="sent" />
                      </div>
                </div>
            <div class="col"></div>
        </div>
    </div>
</template>

<style scoped>
.rounded {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
</style>

<script>
import  ChatMessageFile  from './ChatMessageFile.vue'
export default {
  name: 'ChatMessage',
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
      }
  },
  
  methods: {
      save() {
          this.$root.connection.send(
              JSON.stringify({
                  'action': 'chat:message:send',
                  'params': {
                      'senderId': this.$root.claim.id,    
                      'chatId': this.$route.params.id,    
                      'message': message,    
                  }
              })
          );
      }
  },
  mounted: function() {
  },
  updated: function() {
  },
  created: function() {
    if (this.sender.id!=this.$root.claim.id && this.status!='seen') {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'chat:message:status',
                'params': {
                    'messageId': this.id,
                    'status': 'seen',    
                }
            })
        );
    }

      this.$root.$on('chat:message:status', (result) => {
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