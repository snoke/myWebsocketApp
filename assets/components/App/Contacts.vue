<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
    <div id="contacts" class="h-100p">

      <input id="contact_search" type="text" class="w-100" placeholder="find new contacts" v-on:keyup="findContacts()" v-model="search" />
      <button type="button" v-for="user in contacts" :key="user.id" class="w-100 btn btn-outline-primary" @click="addContact(user)">Add {{user.username}}</button>

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
  name: 'AppContacts',
  data: function() {
    return {
      
        search:'',
        contacts:null,
        mycontacts:null
      }
  },
  methods: {
    addContact(user) {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'contact:add',
                'params': {
                  'token': this.$root.token,
                  'bob' :user.id,
                }
            })
        );
    },
    findContacts() {
      if (this.search!='') {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'contact:search',
                'params': {
                  'token': this.$root.token,
                    'username': this.search,    
                }
            })
        );
      }
    }
  },
  updated: function() {
  },
  mounted() {
     document.getElementById('contact_search').focus()
  },
  beforeDestroy () {
    this.$root.$off('AppContacts::contact:add')
    this.$root.$off('AppContacts::user:contacts')
    this.$root.$off('AppContacts::contact:search')
  },
  created: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'user:contacts',
                'params': {
                          'token': this.$root.token,
                }
            })
        );

    this.$root.$on('AppContacts::contact:add', (result) => {
        if (result.command=='contact:add') {
          this.$router.push({ name: 'app_chat', params: { id: result.data }})
        }
     });
    this.$root.$on('AppContacts::user:contacts', (result) => {
        if (result.command=='user:contacts') {
            this.mycontacts = JSON.parse(result.data);
        }
     });
    this.$root.$on('AppContacts::contact:search', (result) => {
        if (result.command=='contact:search') {
            this.contacts = JSON.parse(result.data).filter((u) => {
              if (u.username==this.$root.claim.username) {
                return false;
              }
              for(var contact of this.mycontacts) {
                if (u.username==contact.username) {
                  return false;
                }
              }
              return true;
            });
        }
     });
  }
}
</script>