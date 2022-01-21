<template>
    <div id="auth">
        <div id="auth_info" >
            <p v-if="!$root.connected">not connected</p>
        </div>
        <div class="accordion" role="tablist"  v-if="$root.connected">
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-1 variant="primary" class="w-100">Login</b-button>
                </b-card-header>
                <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <div id="auth_login">
                            <input type="text" placeholder="loginName" name="loginName" v-model="login_loginName"  class="w-100"/>
                            <input type="password" placeholder="Password" name="password" v-model="login_password"    class="w-100"/>
                            <button v-on:click="login()"  class="btn btn-outline-primary  w-100">login</button>
                        </div>
                    </b-card-body>
                </b-collapse>
            </b-card>
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-2 variant="primary" class="w-100">Register</b-button>
                </b-card-header>
                <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                    
                        <div id="auth_register" >
                            <input type="text" placeholder="loginName" name="loginName" v-model="register_loginName"  class="w-100" />
                            <input type="password" placeholder="Password" name="password" v-model="register_password"   class="w-100" />
                            <input type="password" placeholder="repeat Password" name="password" v-model="register_password2"   class="w-100" />
                            <button v-on:click="register()"  class="btn btn-outline-primary w-100">register</button>
                        </div>
                    </b-card-body>
                </b-collapse>
            </b-card>
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
    this.$root.$on('auth:register', (result) => {
            if (result.command=="auth:register") {
                if (result.success==true) {
                    this.$root.connection.send(
                        JSON.stringify({
                            'action': 'auth:login',
                            'params': {
                                'loginName': this.register_loginName,    
                                'password': this.register_password, 
                            }
                        })
                    );
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