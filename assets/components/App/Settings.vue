<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
    <div id="settings"  class=" h-100p">
      
          <div class="w-100">
            <div class="alert row " v-if="message"
             v-bind:class="{
                'alert-success':  message.status==0,
                'alert-danger':  message.status==1,
                }">{{message.data}}</div>
          </div>
          <input type="oldpassword" placeholder="old password" name="oldpassword" v-model="oldpassword"   class="w-100" />
          <input type="password" placeholder="new password" name="password" v-model="password"   class="w-100" />
          <input type="password" placeholder="repeat new password" name="password2" v-model="password2"   class="w-100" />
          <button v-on:click="save()"  class="btn btn-outline-primary w-100">change password <font-awesome-icon icon="save" /></button>

    </div>
</template>

<style scoped>
.h-100p {
  height:100%;
}
.card-title{
text-align: center;

}
</style>

<script>
export default {
  name: 'Settings',
  data: function() {
    return {
        oldpassword: null,
        password: null,
        password2: null,
        message: null,
      }
  },
  methods: {
    save: function() {
    
        this.$root.connection.send(
            JSON.stringify({
                'action': 'user:change:password',
                'params': {
                  'token': this.$root.token,
                  'oldpassword': this.oldpassword,
                  'password': this.password,
                  'password2': this.password2,
                }
            })
        );
        this.$root.$on('AppSettings::user:change:password', (result) => {
          console.log(result)
            this.message = {
              status: result.status,
              data: result.data,
            }
        });
    }
  },
  beforeDestroy () {
    this.$root.$off('AppSettings::user:change:password')
  },
  updated: function() {
  },
  created: function() {
  }
}
</script>