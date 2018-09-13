<template>
  <div class="max-w-lg mx-auto p-6">
    <h2 class="text-2xl text-center font-semibold text-grey-darkest mb-8">Create Paste</h2>

    <div class="w-full mb-4">
      <div class="w-full">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="name">Name</label>

        <input v-model="form.name" id="name" class="w-full h-auto appearance-none text-base text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded" placeholder="Name" type="text">
      </div>
    </div>

    <div class="w-full flex flex-col sm:flex-row">
      <div class="w-full sm:w-1/3 mb-4 sm:mr-2">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="visibility">Visibility</label>

        <div class="relative">
          <select v-model="form.visibility" id="visibility" class="w-full h-auto appearance-none text-base text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded">
            <option value="public">Public</option>
            <option value="unlisted">Unlisted</option>
            <option value="private" :disabled="true">Private</option>
          </select>

          <div class="absolute pr-4 pin-r pin-y pointer-events-none select-none flex items-center justify-end">
            <svg class="w-4 h-4 text-grey fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
          </div>
        </div>
      </div>

      <div class="w-full sm:w-1/3 mb-4 sm:mx-2">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="language">Language</label>

        <div class="relative">
          <select v-model="form.language" id="language" class="w-full h-auto appearance-none text-base text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded">
            <option value="">None</option>
            <option v-for="(display, value) in languages" :key="value" :value="value" v-text="display"></option>
          </select>

          <div class="absolute pr-4 pin-r pin-y pointer-events-none select-none flex items-center justify-end">
            <svg class="w-4 h-4 text-grey fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
          </div>
        </div>
      </div>

      <div class="hidden w-full sm:w-1/3 mb-4 sm:ml-2">
        <label class="inline-block text-grey text-sm font-semibold mb-2" for="expiration">Expiration</label>

        <select disabled v-model="form.expires_at" id="expiration" class="w-full h-auto appearance-none text-base text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded">
          <option value="">Never</option>
          <option value="">5 Minutes</option>
          <option value="">10 Minutes</option>
          <option value="">30 Minutes</option>
          <option value="">1 Hour</option>
          <option value="">1 Day</option>
          <option value="">1 Week</option>
          <option value="">2 Weeks</option>
          <option value="">1 Month</option>
          <option value="">6 Months</option>
          <option value="">1 Year</option>
        </select>
      </div>
    </div>

    <div class="w-full mb-4">
      <label class="inline-block text-grey text-sm font-semibold mb-2" for="body">Body</label>

      <textarea v-model="form.body" id="body" rows="20" class="w-full h-full whitespace-no-wrap overflow-auto resize-y leading-normal font-mono appearance-none text-sm text-grey-darker border-0 bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition p-4 rounded"></textarea>
    </div>

    <div class="text-right">
      <input @click.prevent="submit" class="inline-block px-6 py-3 rounded cursor-pointer appearance-none w-auto h-auto text-base text-white bg-green hover:bg-green-dark focus:outline-none focus:shadow-outline shadow-transition" type="submit" value="Create">
    </div>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    data() {
      return {
        form: {
          name: '',
          visibility: 'public',
          language: '',
          expires_at: '',
          body: '',
        },

        languages: []
      }
    },

    mounted() {
      axios.get('/api/supported-languages')
        .then(({ data }) => {
          this.languages = data.data.message.languages
        })
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
