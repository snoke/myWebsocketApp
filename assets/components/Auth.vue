<template>
    <div id="auth">
        <div id="auth_info" >
            <p v-if="!$root.connected">not connected</p>
        </div>
        <div id="auth_login" v-if="$root.connected">
            <input type="text" placeholder="loginName" name="loginName" v-model="login_loginName" />
            <input type="password" placeholder="Password" name="password" v-model="login_password"   />
            <button v-on:click="login()">login</button>
        </div>
        <div id="auth_register" v-if="$root.connected">
            <input type="text" placeholder="loginName" name="loginName" v-model="register_loginName" />
            <input type="password" placeholder="Password" name="password" v-model="register_password"   />
            <input type="password" placeholder="repeat Password" name="password" v-model="register_password2"   />
            <button v-on:click="register()">register</button>
        </div>
    </div>
</template>

<style>
</style>


<script>
export default {
  name: 'Auth',
  data: function() {
    return {
      login_loginName:'alice',
      login_password:'test',
      register_loginName:'',
      register_password:'',
      register_password2:'',
      }
  },
  methods: {
    decodeToken: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action':'auth:token:decode',
                'params': {
                    'token': this.$root.token,  
                }
            })
        );
    },
    register: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'auth:register',
                'params': {
                    'loginName': this.register_loginName,    
                    'password': this.register_password, 
                    'password2': this.register_password2, 
                }
            })
        );
    },
    login: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'auth:login',
                'params': {
                    'loginName': this.login_loginName,    
                    'password': this.login_password, 
                }
            })
        );
    }
  },
  updated: function() {
  },
  created: function() {
    this.$root.$on('auth:login', (result) => {
            if (result.command=="auth:login") {
                if (result.success==true) {
                    this.$root.token = result.data;
                    this.decodeToken();
                }
            }
     });
    this.$root.$on('auth:token:decode', (result) => {
            if (result.command=="auth:token:decode") {
                this.$root.claim = JSON.parse(result.data);
                this.$router.push({ name: 'app_chats'})
            }
     });

  }
}
</script>