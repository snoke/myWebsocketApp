<template>
    <div id="contacts">
      <input id="contact_search" type="text" class="w-100" placeholder="find contacts" v-on:keyup="findContacts()" v-model="search" />
      <button type="button" v-for="user in contacts" :key="user.id" class="w-100 btn btn-outline-primary" @click="addContact(user)">Add {{user.username}}</button>

    </div>
</template>

<style scoped>
</style>

<script>
export default {
  name: 'Contacts',
  data: function() {
    return {
        search:null,
        contacts:null,
        mycontacts:null
      }
  },
  methods: {
    addContact(user) {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'app:contact:add',
                'params': {
                  'alice': this.$root.claim.id,
                  'bob' :user.id,
                }
            })
        );
    },
    findContacts() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'app:user:search',
                'params': {
                    'username': this.search,    
                }
            })
        );
    }
  },
  updated: function() {
  },
  mounted() {
     document.getElementById('contact_search').focus()
  },
  created: function() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'app:user:contacts',
                'params': {
                    'userId': this.$root.claim.id
                }
            })
        );

    this.$root.$on('app:contact:add', (result) => {
        if (result.command=='app:contact:add') {
          this.$router.push({ name: 'app_chat', params: { id: result.data }})
        }
     });
    this.$root.$on('app:user:contacts', (result) => {
        if (result.command=='app:user:contacts') {
            this.mycontacts = JSON.parse(result.data);
        }
     });
    this.$root.$on('app:user:search', (result) => {
        if (result.command=='app:user:search') {
            this.contacts = JSON.parse(result.data).filter((u) => {
              if (u.username==this.$root.claim.username) {
                return false;
              }
              for(var contact of this.mycontacts) {
                return u.username!=contact.username
              }
              return true;
            });
        }
     });
  }
}
</script>