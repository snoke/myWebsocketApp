<!-- Author: Stefan Sander <mail@stefan-sander.online> -->
<template>
  <div>
    <img class="chat-image" :src="content"/>
  </div>
</template>

<script>
export default {
  name: 'AppChatChatsMessageFile',
  props: ['data'],
  data: function () {
    return {
      id: this.data.id,
      user: this.data.user,
      filename: this.data.filename,
      content: this.data.content,
      windowWidth: window.innerWidth,
      allowed_file_types: [
        'image/jpeg',
        'image/png'
      ],
      allowed_file_size: 1024 * 1024 * 0.5, // 0.5 mb
    }
  },
  methods: {
    save() {

    }
  },
  mounted: function () {
  },
  updated: function () {
  },
  beforeDestroy() {
  },
  created: function () {

  }
}
</script>