<template>
    <div id="contacts">
      <input type="text" class="w-100" placeholder="find contacts" v-on:keyup="findContacts()" v-model="search" />
      <button type="button" v-for="user in contacts" :key="user.id" class="w-100 btn btn-outline-primary" @click="startChat(user)">Chat with {{user.username}}</button>

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
        contacts:null
      }
  },
  methods: {
    startChat(user) {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'app:chat:start',
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
  created: function() {
    this.$root.$on('app:chat:start', (result) => {
        if (result.command=='app:chat:start') {
          this.$router.push({ name: 'app_chat', params: { id: result.data }})
        }
     });
    this.$root.$on('app:user:search', (result) => {
        if (result.command=='app:user:search') {
            this.contacts = JSON.parse(result.data).filter((u) => {
              return u.username!=this.$root.claim.username
            });
        }
     });
  }
}
</script>