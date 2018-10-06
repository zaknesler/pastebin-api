<template>
  <div
    v-click-outside="hide"
    @keydown.esc.exact.prevent="hide"
    @keydown.space.exact.prevent="toggle"
    @keydown.enter.exact.prevent="toggle"
    @keydown.up.exact.prevent="selectPrevious"
    @keydown.down.exact.prevent="selectNext"
    @click="toggle"
    @blur="hide"
    class="relative flex items-center justify-between p-4 w-full h-auto select-none appearance-none text-base text-grey-darker bg-grey-lightest focus:outline-none focus:shadow-outline shadow-transition cursor-pointer rounded">
    <span class="leading-none mr-4" v-text="selected ? selected.name : items[0].name"></span>

    <svg class="w-4 h-4 flex-no-shrink text-grey fill-current pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>

    <div :id="`cont-${ _uid }`" :class="{ hidden: !show }" style="top: 100%" class="mt-1 mb-8 max-h-64 z-10 block absolute pin-l pin-t w-full rounded bg-white border overflow-hidden overflow-y-auto">
      <div
        :class="{ 'bg-green text-white': (item == selected) }" class="p-3 hover:text-grey-darker hover:bg-grey-lighter w-full cursor-pointer"
        v-for="item in items"
        @click="select(item)"
        :key="item.value"
        :id="`item-${ _uid }-${ item.value }`"
        v-text="item.name"
      ></div>
    </div>
  </div>
</template>

<script>
  import ClickOutside from 'vue-click-outside'
  import ScrollTo from 'vue-scrollto'

  export default {
    directives: { ClickOutside },
    components: { ScrollTo },

    props: ['items'],

    data: () => ({
      show: false,
      selected: null,
    }),

    mounted() {
      this.select(this.items.filter(item => item.default)[0])
    },

    methods: {
      hide() {
        this.show = false
      },

      toggle() {
        this.show = !this.show
      },

      select(item) {
        this.selected = item
        this.$emit('input', this.selected.value)
      },

      selectPrevious() {
        const index = this.items.indexOf(this.selected) - 1

        if (index >= 0) {
          this.select(this.items[index])
          this.scrollTo(this.items[index])
        }
      },

      selectNext() {
        const index = this.items.indexOf(this.selected) + 1

        if (index <= this.items.length - 1) {
          this.select(this.items[index])
          this.scrollTo(this.items[index])
        }
      },

      scrollTo(item) {
        ScrollTo.scrollTo(`#item-${ this._uid }-${ item.value }`, 1, { container: `#cont-${ this._uid }`, force: false })
      }
    }
  }
</script>
