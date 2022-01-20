<template>
    <div id="contacts">
      <input type="text" class="w-100" placeholder="find contacts" v-on:keyup="find_contacts()" v-model="search" />
      <button type="button" v-for="user in contacts" :key="user.id" class="w-100 btn btn-outline-primary">{{user.username}}</button>

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
    find_contacts() {
        this.$root.connection.send(
            JSON.stringify({
                'action': 'app:contacts:search',
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
    this.$root.$on('app:contacts:search', (result) => {
            if (result.command=='app:contacts:search') {
                this.contacts = JSON.parse(result.data);
            }
     });
  }
}
</script>