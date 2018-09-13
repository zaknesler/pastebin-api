<template>
  <div class="text-center">
    <router-link :to="{ name: 'pastes.create' }" class="inline-block bg-green hover:bg-green-dark focus:outline-none focus:shadow-outline shadow-transition px-6 py-3 rounded text-white no-underline mb-8">Create Paste</router-link>

    <h2 class="text-2xl font-semibold text-grey-darkest mb-4">Recent pastes</h2>

    <ul class="list-reset" v-show="pastes">
      <li
        class="mb-2"
        v-for="paste in pastes"
        :key="paste.slug"
      >
        <router-link
        class="text-green-dark no-underline hover:underline"
        :to="{ name: 'pastes.show', params: { slug: paste.slug } }"
        v-text="paste.name"
        ></router-link>
      </li>
    </ul>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    metaInfo: {
      title: 'Pastes',
      meta: [
        { description: 'An index of the most recently created pastes' }
      ]
    },

    data() {
      return {
        pastes: []
      }
    },

    mounted() {
      axios.get('/api/pastes')
        .then(({ data }) => {
          this.pastes = data.data
        })
    }
  }
</script>
