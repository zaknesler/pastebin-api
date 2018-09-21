<template>
  <div class="max-w-lg mx-auto">
    <h1 class="text-2xl text-center font-semibold text-grey-darkest mb-8" v-text="paste.name"></h1>

    <div v-if="paste.user != null" class="flex items-center justify-center mb-8">
      <img class="w-8 h-8 rounded-full user-select-none pointer-events-none mr-3" :src="paste.user.avatar" alt="Avatar">
      <div class="font-semibold" v-text="paste.user.name"></div>
    </div>

    <pre v-text="paste.body" class="p-6 bg-grey-lightest text-grey-darker text-sm font-mono overflow-auto rounded text-left"></pre>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    metaInfo () {
      return {
        title: this.paste.name
      }
    },

    data() {
      return {
        paste: {}
      }
    },

    mounted() {
      axios.get('/api/pastes/' + this.$route.params.slug)
        .then(({ data }) => {
          this.paste = data.data
        })
    }
  }
</script>
