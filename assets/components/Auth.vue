<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
    <div id="auth">
        <div id="auth_info" >
            <p v-if="!$root.connected">not connected</p>
        </div>
        <div class="accordion" role="tablist" >
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1" role="tab">
                    <b-button block v-b-toggle.accordion-1 variant="primary" class="w-100">Login</b-button>
                </b-card-header>
                <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
                    <b-card-body>
                        <div id="auth_login">
                            <div class="w-100">
                                <div class="alert row " v-if="login_message"
                                v-bind:class="{
                                    'alert-success':  login_message.status==0,
                                    'alert-danger':  login_message.status==1,
                                    }">{{login_message.data}}</div>
                            </div>
                            <input type="text" placeholder="loginName" name="loginName" v-model="login_loginName"  class="w-100"/>
                            <input type="password" placeholder="Password" name="password" v-model="login_password"    class="w-100"/>
                            <button v-on:click="login()"  class="btn btn-outline-primary  w-100">login <font-awesome-icon icon="sign-in-alt" /></button>
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
                            <div class="w-100">
                                <div class="alert row alert-danger" v-if="register_message && register_message.status==1">{{register_message.data}}</div>
                            </div>
                            <input type="text" placeholder="loginName" name="loginName" v-model="register_loginName"  class="w-100" />
                            <input type="password" placeholder="Password" name="password" v-model="register_password"   class="w-100" />
                            <input type="password" placeholder="repeat Password" name="password" v-model="register_password2"   class="w-100" />
                            <button v-on:click="register()"  class="btn btn-outline-primary w-100">register <font-awesome-icon icon="user-plus" /></button>
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
      register_message:null,
      login_message:null,
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
  beforeDestroy () {
    this.$root.$off('auth:login')
    this.$root.$off('auth:register')
    this.$root.$off('auth:token:decode')
  },
  created: function() {
    this.$root.$on('auth:login', (result) => {
        if (result.status==0) {
                    this.$root.token = result.data;
                    this.decodeToken();
        } else {
            this.login_message = {
                status: result.status,
                data: result.data,
            }

        }
     });
    this.$root.$on('auth:register', (result) => {
        this.register_message = {
            status: result.status,
            data: result.data,
        }
        if (result.status==0) {
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
     });
    this.$root.$on('auth:token:decode', (result) => {
        this.$root.claim = JSON.parse(result.data);
        this.$router.push({ name: 'app_chats'});
     });

  }
}
</script>