<template>
    <div id="settings">
      
      <b-card title="Change Password">
        <b-card-text>
          <div class="w-100">
            <div class="alert row " v-if="message"
             v-bind:class="{
                'alert-success':  message.status==0,
                'alert-danger':  message.status==1,
                }">{{message.data}}</div>
          </div>
          <input type="_password" placeholder="old Password" name="_password" v-model="_password"   class="w-100" />
          <input type="password" placeholder="Password" name="password" v-model="password"   class="w-100" />
          <input type="password" placeholder="repeat Password" name="password2" v-model="password2"   class="w-100" />
          <button v-on:click="save()"  class="btn btn-outline-primary w-100">change password <font-awesome-icon icon="save" /></button>
        </b-card-text>

      </b-card>
    </div>
</template>

<style scoped>
</style>

<script>
export default {
  name: 'Settings',
  data: function() {
    return {
        _password: null,
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
                  '_password': this._password,
                  'password': this.password,
                  'password2': this.password2,
                }
            })
        );
        this.$root.$on('user:change:password', (result) => {
          console.log(result)
            this.message = {
              status: result.status,
              data: result.data,
            }
        });
    }
  },
  beforeDestroy () {
    this.$root.$off('user:change:password')
  },
  updated: function() {
  },
  created: function() {
  }
}
</script>