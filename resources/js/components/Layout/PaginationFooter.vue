<template>
  <div :class="isMobile ? 'd-flex justify-content-center' : 'pagination-footer position-absolute'">
    <v-pagination
        :length="last_page"
        :total-visible="isMobile ? 1 : last_page"
        :value="current_page"
        @update:modelValue="callback"
        rounded="circle"

    ></v-pagination>
  </div>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
  name: 'PaginationFooter',

  data() {
    return {
      isMobile: window.innerWidth <= 768, // Az ablak szélessége alapján mobil nézet
    };
  },
  props: {
    callback: {
      required: true,
      type: Function,
    },
  },
  computed: {
    ...mapGetters('Table', ['last_page', 'current_page']),
  },

  created() {
    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize);
  },

  methods: {
    handleResize() {
      this.isMobile = window.innerWidth <= 768;
    },
  },
}
</script>

<style scoped>
.pagination-footer {
  left: 50%;
  right: 50%
}
</style>
