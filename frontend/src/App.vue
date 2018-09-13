<template>
  <div>
    <nav class="text-center flex items-center justify-center my-8">
      <router-link to="/" class="mr-6 text-green-dark no-underline hover:underline">Home</router-link>
      <router-link to="/pastes" class="text-green-dark no-underline hover:underline">Pastes</router-link>
    </nav>

    <router-view/>
    <vue-progress-bar></vue-progress-bar>
  </div>
</template>

<script>
  export default {
    metaInfo: {
      titleTemplate: (titleChunk) => {
        return titleChunk ? `${titleChunk} - Pastebin` : 'Pastebin'
      },
      meta: [
        { charset: 'utf-8' },
        { name: 'X-UA-Compatible', content: 'IE=Edge' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' }
      ],
      link: [
        { rel: 'favicon', href: '/favicon.ico' }
      ],
    },

    mounted() {
      this.$Progress.finish()
    },

    created() {
      this.$Progress.start()

      this.$router.beforeEach((to, from, next) => {
        if (to.meta.progress !== undefined) {
          this.$Progress.parseMeta(to.meta.progress)
        }

        this.$Progress.start()
        next()
      })

      this.$router.afterEach(() => {
        this.$Progress.finish()
      })
    }
  }
</script>
