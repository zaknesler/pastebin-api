<template>
  <div class="max-w-lg mx-auto p-6">
    <h2 class="text-2xl text-center font-semibold text-grey-darkest mb-8">Create Paste</h2>

    <div class="w-full mb-4">
      <div class="w-full">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="name">Name</label>

        <input autofocus tabindex="1" v-model="form.name" id="name" class="w-full h-auto appearance-none text-base text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded" placeholder="Name" type="text">
      </div>
    </div>

    <div class="w-full flex flex-col sm:flex-row">
      <div class="w-full sm:w-1/3 mb-4 sm:mr-2">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="visibility">Visibility</label>

        <dropdown tabindex="2" v-model="form.visibility" :items="dropdowns.visibilities" id="visibility"></dropdown>
      </div>

      <div class="w-full sm:w-1/3 mb-4 sm:mx-2">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="language">Language</label>

        <dropdown tabindex="3" v-model="form.language" :items="dropdowns.languages" id="language"></dropdown>
      </div>

      <div class="w-full sm:w-1/3 mb-4 sm:ml-2">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="expires_at">Expiration</label>

        <dropdown tabindex="4" v-model="form.expires_at" :items="dropdowns.expirations" id="expires_at"></dropdown>
      </div>
    </div>

    <div class="w-full mb-4">
      <label class="inline-block text-grey text-sm font-semibold mb-2" for="body">Body</label>

      <textarea tabindex="5" v-model="form.body" id="body" rows="20" class="w-full h-full whitespace-no-wrap overflow-auto resize-y leading-normal font-mono appearance-none text-sm text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded"></textarea>
    </div>

    <div class="text-right">
      <input tabindex="6" @click.prevent="submit" class="inline-block px-6 py-3 rounded cursor-pointer appearance-none w-auto h-auto text-base text-white bg-green hover:bg-green-dark focus:outline-none focus:shadow-outline shadow-transition" type="submit" value="Create">
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import pastebin from '@/pastebin.js'

  import Dropdown from '@/components/Dropdown.vue'

  export default {
    components: { Dropdown },

    data: () => ({
      form: {
        name: '',
        language: '',
        visibility: '',
        expires_at: '',
        body: '',
      },
      dropdowns: {
        visibilities: null,
        languages: null,
        expirations: null,
      },
    }),

    created() {
      this.dropdowns.visibilities = pastebin.visibilities
      this.dropdowns.languages = pastebin.languages
      this.dropdowns.expirations = pastebin.expirations
    },

    methods: {
      submit() {
        axios.post('/api/pastes', this.form)
          .then(({ data }) => {
            if (data.error) {
              return
            }

            this.$router.push({ name: 'pastes.show', params: { slug: data.data.slug } })
          })
      }
    }
  }
</script>
